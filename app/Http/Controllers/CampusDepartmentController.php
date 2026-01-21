<?php

namespace App\Http\Controllers;

use App\Models\CampusDepartment;
use App\Models\Department;
use App\Models\Campus;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\TraitFunctions;
use Yajra\DataTables\Facades\DataTables;

class CampusDepartmentController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('managecampusdepartment-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('managecampusdepartment-read', $role_data);

    //     if($check_read_permission == true || $check_all_permission == true){
    //         $write_permission = $this->checkPermissions('managecampusdepartment-write', $role_data);
    //         $edit_permission = $this->checkPermissions('managecampusdepartment-edit', $role_data);
    //         $delete_permission = $this->checkPermissions('managecampusdepartment-delete', $role_data);
    //         $campusDepartments = CampusDepartment::all();
    //         return view('campusdepartment.manage', compact('campusDepartments','write_permission','edit_permission','delete_permission','check_all_permission'));
    //     }else{
    //     $error = "403";
    //     $heading = "Oops! Forbidden";
    //     $message = "You don't have permission to access this module";
    //     return view('errors.error',compact('message','error','heading'));
    //     }
    // }

    public function index(Request $request)
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('managecampusdepartment-all', $role_data);
        $check_read_permission = $this->checkPermissions('managecampusdepartment-read', $role_data);
        if ($check_read_permission || $check_all_permission) {
            $write_permission  = $this->checkPermissions('managecampusdepartment-write',  $role_data);
            $edit_permission   = $this->checkPermissions('managecampusdepartment-edit',   $role_data);
            $delete_permission = $this->checkPermissions('managecampusdepartment-delete', $role_data);

            if ($request->ajax()) {
                $campusDepartments = CampusDepartment::with(['campus', 'department'])->select('campus_departments.*');

                return DataTables::of($campusDepartments)
                    ->filter(function ($query) use ($request) {
                        if ($search = $request->get('search')['value'] ?? null) {
                            $query->where(function($q) use ($search) {
                                $q->where('campus_departments.id', 'like', "%{$search}%")
                                ->orWhere('campus_departments.status', 'like', "%{$search}%")
                                ->orWhereHas('campus', function($q2) use ($search) {
                                    $q2->where('name', 'like', "%{$search}%");
                                })
                                ->orWhereHas('department', function($q3) use ($search) {
                                    $q3->where('department_name', 'like', "%{$search}%");
                                });
                            });
                        }
                        $columns = $request->get('columns');
                        if(!empty($columns[1]['search']['value'])) {
                            $campusSearch = $columns[1]['search']['value'];
                            $query->whereHas('campus', function($q) use ($campusSearch) {
                                $q->where('name', 'like', "%{$campusSearch}%");
                            });
                        }
                        if(!empty($columns[2]['search']['value'])) {
                            $departmentSearch = $columns[2]['search']['value'];
                            $query->whereHas('department', function($q) use ($departmentSearch) {
                                $q->where('department_name', 'like', "%{$departmentSearch}%");
                            });
                        }
                    })
                    ->addColumn('campus', function ($row) {
                        return $row->campus->name ?? '';
                    })
                    ->addColumn('department', function ($row) {
                        return $row->department->department_name ?? '';
                    })
                    ->addColumn('status', function ($row) {
                        return $row->status == 1
                            ? '<div class="badge badge-light-success">Active</div>'
                            : '<div class="badge badge-light-danger">Inactive</div>';
                    })
                    ->addColumn('action', function ($row) use ($edit_permission, $check_all_permission) {
                        $btn = '';
                        if ($edit_permission || $check_all_permission) {
                            $btn .= '<a href="'.route('editcampusdepartment', $row->id).'" class="btn btn-sm btn-success">Edit</a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
            return view('campusdepartment.manage', compact(
                'write_permission','edit_permission','delete_permission','check_all_permission'
            ));
        }
        return view('errors.error', [
            'error'   => '403',
            'heading' => 'Oops! Forbidden',
            'message' => "You don't have permission to access this module"
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $deparments = Department::where('status', 1)->get();
        $campuses = Campus::where('status', 1)->get();
        return view('campusdepartment.add', compact('deparments', 'campuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'campus_id' => 'required',
    //         'deparment_id' => 'required',
    //         'program_id' => 'required',
    //         'status'=> 'required',
    //     ],
    //     [
    //         'deparment_id'=> 'Department is required',
    //     ]);

    //     CampusDepartment::create([
    //         'campus_id'    => $request->campus_id,
    //         'deparment_id' => json_encode($request->deparment_id),
    //         'program_id'=> json_encode($request->program_id),
    //         'status'       => $request->status,
    //         'created_by'   => Auth::id(),
    //     ]);

    //     return redirect()->route('managecampusdepartment')->with('success', 'Data Saved Successfully');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'campus_id'    => 'required',
            'department_id' => 'required|array',
            'status'       => 'required',
        ],
        [
            'department_id.required' => 'Department is required',
        ]);

        foreach ($request->department_id as $dept) {
            CampusDepartment::create([
                'campus_id'    => $request->campus_id,
                'department_id' => $dept,
                'status'       => $request->status,
                'created_by'   => Auth::user()->employee_id,
            ]);
        }

        return redirect()->route('managecampusdepartment')->with('success', 'Data Saved Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(CampusDepartment $campusDepartment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(CampusDepartment $campusDepartment, $id)
    // {
    //     $campusDepartments = CampusDepartment::findOrFail($id);
    //     $deparments = Department::where('status', 1)->get();
    //     $campuses = Campus::where('status', 1)->get();

    //     $selectedDepartments = json_decode($campusDepartments->deparment_id, true);

    //     return view('campusdepartment.edit', compact('campusDepartments', 'deparments', 'campuses', 'selectedDepartments'));
    // }

    public function edit($id)
    {
        $campusDepartments = CampusDepartment::findOrFail($id);
        $deparments = Department::where('status', 1)->get();
        $campuses = Campus::where('status', 1)->get();

        $selectedDepartments = [$campusDepartments->department_id];

        return view('campusdepartment.edit', compact('campusDepartments', 'deparments', 'campuses', 'selectedDepartments',
        ));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CampusDepartment $campusDepartment)
    {
        $request->validate([
            'campus_id'    => 'required',
            'department_id' => 'required|array',
            'status'       => 'required',
        ],
        [
            'department_id.required' => 'Department is required',
        ]);

        foreach ($request->department_id as $dept) {
            $update_data = [
                'campus_id'    => $request->campus_id,
                'department_id' => $dept,
                'status'       => $request->status,
            ];
        }
        CampusDepartment::whereid($request->id)->update($update_data);
        return redirect()->route('managecampusdepartment')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CampusDepartment $campusDepartment)
    {
        //
    }
}
