<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Audit;
use App\Models\Program;
use App\Models\Checklist;
use App\Models\User;
use App\Models\Campus;
use App\Models\Department;
use App\Models\CampusDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\TraitFunctions;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class ScheduleController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('manageschedule-all', $role_data);
        $check_read_permission = $this->checkPermissions('manageschedule-read', $role_data);

        if (($check_read_permission || $check_all_permission)) {
            $write_permission = $this->checkPermissions('manageschedule-write', $role_data);
            $edit_permission = $this->checkPermissions('manageschedule-edit', $role_data);
            $delete_permission = $this->checkPermissions('manageschedule-delete', $role_data);

            if ($request->ajax()) {
                $schedules = Schedule::with('aditor')->select('schedules.id','schedules.title','schedules.start_date','schedules.status','schedules.aditor_id');
                return DataTables::of($schedules)
                    ->filter(function ($query) use ($request) {
                        if ($search = $request->input('search.value')) {
                            $query->where(function($q) use ($search) {
                                $q->where('schedules.id', 'like', "%{$search}%")
                                ->orWhere('schedules.title', 'like', "%{$search}%")
                                ->orWhereHas('aditor', function($q2) use ($search) {
                                    $q2->where('name', 'like', "%{$search}%");
                                });
                            });
                        }
                        if ($columns = $request->input('columns')) {
                            foreach ($columns as $col) {
                                $colName = $col['name'];
                                $colSearch = $col['search']['value'] ?? null;
                                if ($colSearch) {
                                    if ($colName == 'aditor.name') {
                                        $query->whereHas('aditor', function($q2) use ($colSearch) {
                                            $q2->where('name', 'like', "%{$colSearch}%");
                                        });
                                    } else {
                                        $query->where("schedules.$colName", 'like', "%{$colSearch}%");
                                    }
                                }
                            }
                        }
                    })
                    ->addColumn('aditor', function ($row) {
                        return optional($row->aditor)->name ?? '-';
                    })
                    ->addColumn('status', function ($row) {
                        return $row->status == 1
                            ? '<div class="badge badge-light-success">Active</div>'
                            : '<div class="badge badge-light-danger">Inactive</div>';
                    })
                    ->addColumn('action', function ($row) {
                        return '<a href="'.route('editschedule', $row->id).'" class="btn btn-sm btn-success me-1">Edit</a>';
                    })
                ->rawColumns(['status','action'])
                ->make(true);
            }
            return view('schedule.manage', compact('write_permission','edit_permission','delete_permission','check_all_permission'));
        } else {
            $error = "403";
            $heading = "Oops! Forbidden";
            $message = "You don't have permission to access this module";
            return view('errors.error', compact('message','error','heading'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $audits = Audit::where('status', 1)->get();
        $aditorusers = User::where('role_id', 3)->get();
        $checklists = Checklist::where('status',1)->get();

        $campusDepartments = CampusDepartment::where('status', 1)
            ->with('campus')
            ->select('id', 'campus_id', 'department_id')
            ->get()->unique('campus_id');

        return view("schedule.add",compact('audits', 'campusDepartments', 'checklists', 'aditorusers'));
    }

    public function getCampusDetails($campusID)
    {
        $campusRows = CampusDepartment::where('campus_id', $campusID)
            ->where('status', 1)
            ->with('department')
            ->get();
        // ---------- DEPARTMENTS ----------
        $departments = $campusRows->map(function ($row) {
            if ($row->department) {
                return [
                    'id' => $row->department->id,
                    'name' => $row->department->department_name,
                ];
            }
        })->filter()->unique('id')->values();
        // ---------- PROGRAMS ----------
        $departmentIDs = $departments->pluck('id')->toArray();

        $programs = Program::whereIn('department_id', $departmentIDs)
            ->where('status', 1)
            ->get(['id', 'program_name', 'department_id'])
            ->map(function ($program) {
                return [
                    'id' => $program->id,
                    'name' => $program->program_name,
                    'department_id' => $program->department_id,
                ];
            });

        return response()->json([
            'departments' => $departments,
            'programs' => $programs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            'audit_id' => 'required',
            'checklist_id' => 'required',
            'aditor_id'=> 'required',
            'campus_id'=> 'required',
            'department_id'=> 'required',
        ],
        [
            'campus_id.required' => 'Campus is required',
            'department_id.required' => 'Department is required',
            'audit_id' => 'Audit title is required',
            'checklist_id' => 'Check List is required',
            'aditor_id'=> 'Aditor User is required',
        ]);

        $selectedUser = User::find($request->aditor_id);

        if (!$selectedUser) {
            return back()->withErrors(['aditor_id' => 'Selected user not found']);
        }

        Schedule::create([
            'title'=> $request->title,
            'campus_id'=> $request->campus_id,
            'program_id' => json_encode($request->program_id),
            'department_id' => json_encode($request->department_id),
            'status' => $request->status,
            'audit_id'=> $request->audit_id,
            'start_date' => $request->start_date,
            'checklist_id' => json_encode($request->checklist_id),
            'aditor_id'=> $selectedUser->employee_id,
            'created_by' => Auth::user()->employee_id,
        ]);

        return redirect()->route('manageschedule')->with('success', 'Data Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Schedule $schedule)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $schedules = Schedule::findOrFail($id);

        $audits = Audit::where('status', 1)->get();
        $checklists = Checklist::where('status', 1)->get();
        $aditorusers = User::where('role_id', 3)->get();
        $campusDepartments = CampusDepartment::where('status', 1)
            ->with('campus')
            ->select('id', 'campus_id', 'department_id')
            ->get()
            ->unique('campus_id');

        $selectedDepartments = $schedules->department_id ? json_decode($schedules->department_id, true) : [];
        $selectedPrograms = $schedules->program_id ? json_decode($schedules->program_id, true) : [];
        return view('schedule.edit', compact(
            'schedules', 'audits', 'checklists', 'aditorusers', 'campusDepartments', 'selectedDepartments', 'selectedPrograms',
        ));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedules)
    {
        $request->validate([
            'start_date' => 'required',
            'audit_id' => 'required',
            'checklist_id' => 'required',
            'aditor_id'=> 'required',
            'campus_id'=> 'required',
            'department_id'=> 'required',
            'status' => 'required',
        ],
        [
            'campus_id.required' => 'Campus is required',
            'department_id.required' => 'Department is required',
            'audit_id' => 'Audit title is required',
            'checklist_id' => 'Check List is required',
            'aditor_id'=> 'Aditor User is required',
        ]);

            $selectedUser = User::find($request->aditor_id);

        if (!$selectedUser) {
            return back()->withErrors(['aditor_id' => 'Selected user not found']);
        }

        $update_data = [
            'title' => $request->title,
            'start_date' => $request->start_date,
            'campus_id'=> $request->campus_id,
            'department_id' => json_encode($request->department_id),
            'status' => $request->status,
            'program_id' => json_encode($request->program_id),
            'audit_id'=> $request->audit_id,
            'checklist_id' => json_encode($request->checklist_id),
            'aditor_id'=> $selectedUser->employee_id,
        ];
        Schedule::whereid($request->id)->update($update_data);
        return redirect()->route('manageschedule')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedules)
    {
        //
    }
}
