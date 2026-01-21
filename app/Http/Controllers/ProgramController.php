<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Traits\TraitFunctions;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProgramController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('manageprogram-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('manageprogram-read', $role_data);
    //     if($check_read_permission == true || $check_all_permission == true){
    //         $write_permission = $this->checkPermissions('manageprogram-write', $role_data);
    //         $edit_permission = $this->checkPermissions('manageprogram-edit', $role_data);
    //         $delete_permission = $this->checkPermissions('manageprogram-delete', $role_data);
    //         $programs = Program::with('department')->get();
    //         return view('program.manage',compact('programs', 'write_permission','edit_permission','delete_permission','check_all_permission'));
    //     }else{
    //       $error = "403";
    //       $heading = "Oops! Forbidden";
    //       $message = "You don't have permission to access this module";
    //       return view('errors.error',compact('message','error','heading'));
    //     }
    // }
    public function index(Request $request)
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('manageprogram-all', $role_data);
        $check_read_permission = $this->checkPermissions('manageprogram-read', $role_data);

        if ($check_read_permission || $check_all_permission) {
            $write_permission = $this->checkPermissions('manageprogram-write', $role_data);
            $edit_permission = $this->checkPermissions('manageprogram-edit', $role_data);
            $delete_permission = $this->checkPermissions('manageprogram-delete', $role_data);

            if ($request->ajax()) {
                $programs = Program::query()
                    ->leftJoin('departments', 'programs.department_id', '=', 'departments.id')
                    ->select(['programs.id','programs.program_name','programs.status','departments.department_name']);

                return datatables()->of($programs)
                    ->addColumn('status', function ($item) {
                        return $item->status == 1
                            ? '<span class="badge badge-light-success">Active</span>'
                            : '<span class="badge badge-light-danger">Inactive</span>';
                    })
                    ->addColumn('action', function ($item) use ($edit_permission, $check_all_permission) {
                        $btn = '';
                        if ($edit_permission || $check_all_permission) {
                            $btn .= '<a href="'.route('editprogram', $item->id).'"
                                    class="btn btn-sm btn-success">Edit</a>';
                        }
                        return $btn;
                    })
                ->rawColumns(['status', 'action'])
                ->make(true);
            }

            return view('program.manage', compact('write_permission', 'edit_permission', 'delete_permission', 'check_all_permission'
            ));
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
        $departments = Department::where('status', 1)->get();
        return view('program.add', compact('departments'));
    }

    /**
     * the form for Import a new resource.
     */
    // public function importPrograms(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|mimes:xls,xlsx',
    //     ]);

    //     $file = $request->file('excel_file');
    //     $spreadsheet = IOFactory::load($file->getPathname());
    //     $sheet = $spreadsheet->getActiveSheet();
    //     $rows = $sheet->toArray();

    //     unset($rows[0]);
    //     foreach ($rows as $row) {
    //         if (empty($row[0]) || empty($row[1])) {
    //             continue;
    //         }
    //         $department = Department::where('department_name', trim($row[1]))->where('status', 1)->first();
    //         // echo '<pre>';
    //         // print_r($department);
    //         // exit;
    //         if (!$department) {
    //             continue;
    //         }
    //         Program::updateOrCreate(
    //             [
    //                 'program_name' => trim($row[0]),
    //                 'department_id' => $department->id,
    //             ],
    //             [
    //                 'status' => $row[2] ?? 1,
    //                 'created_by' => Auth::user()->employee_id,
    //             ]
    //         );
    //     }
    //     return redirect()->route('manageprogram')->with('success', 'Programs imported successfully');
    // }
    public function importPrograms(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);
        $file = $request->file('excel_file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        unset($rows[1]);
        $imported = false;
        foreach ($rows as $row) {
            $programName    = trim($row['A'] ?? '');
            $departmentName = trim($row['B'] ?? '');
            $status         = $row['C'] ?? 1;

            if (!$programName || !$departmentName) {
                continue;
            }

            $department = Department::whereRaw(
                    'LOWER(department_name) = ?',
                    [strtolower($departmentName)]
                )->where('status', 1)->first();

            if (!$department) {
                continue;
            }
            Program::updateOrCreate(
                [
                    'program_name'  => $programName,
                    'department_id' => $department->id,
                ],
                [
                    'status'     => $status,
                    'created_by' => Auth::user()->employee_id,
                ]
            );
            $imported = true;
        }
        if ($imported) {
            return redirect()->route('manageprogram')->with('success', 'Programs imported successfully.');
        }
        return redirect()->route('manageprogram')->with('error', 'No programs were imported. Please check Excel file.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'program_name' => 'required',
            'department_id' => 'required',
            'status' => 'required',
        ],
        [
            'program_name.required' => 'Program Name is required',
            'department_id.required' => 'Department Name required',
        ]);
        Program::create([
            'program_name' => $request->program_name,
            'status' => $request->status,
            'department_id' => $request->department_id,
            'created_by'   => Auth::user()->employee_id,
        ]);
        return redirect()->route('manageprogram')->with('success', 'Data Added Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Program $programs)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Program $programs, $id)
    {
        $programs = Program::findOrFail($id);
        $departments = Department::where('status', 1)->get();
        return view('program.edit', compact('programs', 'departments'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Program $programs)
    {
        $request->validate([
            'program_name' => 'required',
            'department_id' => 'required',
            'status' => 'required',
        ],
        [
            'program_name.required' => 'Program Name is required',
            'department_id.required' => 'Department Name required',
        ]);

        $update_data = [
            'program_name' => $request->program_name,
            'department_id' => $request->department_id,
            'status' => $request->status,
        ];
        Program::whereid($request->id)->update($update_data);
        return redirect()->route('manageprogram')->with('success', 'Data Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Program $programs)
    {
        //
    }
}
