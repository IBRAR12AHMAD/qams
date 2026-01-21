<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CampusDepartment;
use App\Models\StudentSession;
use App\Models\Employee;
use App\Imports\UsersImport;
use App\Traits\TraitFunctions;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Session\Session;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    use TraitFunctions;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('user-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('user-read', $role_data);
    //     if($check_read_permission == true || $check_all_permission == true){
    //       $write_permission = $this->checkPermissions('user-write', $role_data);
    //       $edit_permission = $this->checkPermissions('user-edit', $role_data);
    //       $delete_permission = $this->checkPermissions('user-delete', $role_data);
    //       $users = User::latest()->get();
    //       return view('users.manage',compact('users','write_permission','edit_permission','delete_permission','check_all_permission'));
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
        $check_all_permission = $this->checkPermissions('user-all', $role_data);
        $check_read_permission = $this->checkPermissions('user-read', $role_data);

        if ($check_read_permission == true || $check_all_permission == true) {
            $write_permission = $this->checkPermissions('user-write', $role_data);
            $edit_permission = $this->checkPermissions('user-edit', $role_data);
            $delete_permission = $this->checkPermissions('user-delete', $role_data);

            // if ($request->ajax()) {
            //     $query = User::select(['id', 'name', 'designation', 'status']);

            //     $dataTable = DataTables::of($query)
            //         ->addIndexColumn()
            //         ->addColumn('status', function ($row) {
            //             return $row->status === 1
            //                 ? '<span class="badge badge-light-success">Active</span>'
            //                 : '<span class="badge badge-light-danger">Inactive</span>';
            //         })
            //         ->addColumn('action', function ($row) use ($edit_permission, $check_all_permission, $delete_permission) {
            //             $buttons = '';

            //             if ($edit_permission || $check_all_permission) {
            //                 $buttons .= '<a href="'.route('edituser', $row->id).'" class="btn btn-sm btn-success">Edit</a>';
            //             }

            //             return $buttons ?: '<span class="text-muted">No actions</span>';
            //         })
            //         ->rawColumns(['status', 'action']);

            //     if ($request->has('columns')) {
            //         foreach ($request->columns as $column) {
            //             if ($column['searchable'] == "true" && !empty($column['search']['value'])) {
            //                 $searchValue = $column['search']['value'];
            //                 $columnIndex = $column['data'];

            //                 if ($columnIndex === 'id') {
            //                     $dataTable->filterColumn('id', function($query, $keyword) {
            //                         $query->where('id', 'like', "%{$keyword}%");
            //                     });
            //                 } elseif ($columnIndex === 'name') {
            //                     $dataTable->filterColumn('name', function($query, $keyword) {
            //                         $query->where('name', 'like', "%{$keyword}%");
            //                     });
            //                 } elseif ($columnIndex === 'email') {
            //                     $dataTable->filterColumn('email', function($query, $keyword) {
            //                         $query->where('email', 'like', "%{$keyword}%");
            //                     });
            //                 } elseif ($columnIndex === 'status') {
            //                     $status = strtolower($searchValue) === 'active' ? 1 : 0;
            //                     $dataTable->filterColumn('status', function($query) use ($status) {
            //                         $query->where('status', $status);
            //                     });
            //                 }
            //             }
            //         }
            //     }
            //     return $dataTable->make(true);
            // }
            if ($request->ajax()) {

                $query = User::select(['id', 'name', 'email', 'designation', 'status', 'role_id']);

                if (Auth::user()->role_id != 5) {
                    $query->where('role_id', '!=', 5);
                }

                $dataTable = DataTables::of($query)
                    ->addIndexColumn()
                    ->addColumn('status', function ($row) {
                        return $row->status === 1
                            ? '<span class="badge badge-light-success">Active</span>'
                            : '<span class="badge badge-light-danger">Inactive</span>';
                    })
                    ->addColumn('action', function ($row) use ($edit_permission, $check_all_permission) {
                        $buttons = '';

                        if ($edit_permission || $check_all_permission) {
                            $buttons .= '<a href="'.route('edituser', $row->id).'" class="btn btn-sm btn-success">Edit</a>';
                        }

                        return $buttons ?: '<span class="text-muted">No actions</span>';
                    })
                ->rawColumns(['status', 'action']);
                return $dataTable->make(true);
            }
            return view('users.manage', compact('edit_permission', 'check_all_permission', 'delete_permission', 'write_permission', 'check_read_permission'));
        } else {
            $error = "403";
            $heading = "Oops! Forbidden";
            $message = "You don't have permission to access this module";
            return view('errors.error', compact('message', 'error', 'heading'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::first();
        // $roles = Role::select('id','name')->where('status', 1)->get();

        $rolesQuery = Role::select('id', 'name')->where('status', 1);

        if (Auth::user()->role_id != 5) {
            $rolesQuery->where('id', '!=', 5);
        }

        $roles = $rolesQuery->get();

        $campusDepartments = CampusDepartment::where('status', 1)
            ->with('campus')
            ->select('id', 'campus_id', 'department_id')
            ->get()
            ->unique('campus_id');

        return view('users.add', compact('user','roles','campusDepartments'));
    }

    public function getDepartments($campus_id)
    {
        $departments = CampusDepartment::where('campus_id', $campus_id)
            ->where('status', 1)
            ->with('department')
            ->get()
            ->map(function($cd) {
                return [
                    'id' => $cd->department->id ?? null,
                    'name' => $cd->department->department_name ?? 'N/A', // use 'name' key
                ];
            })
            ->filter(fn($d) => $d['id'] !== null)
            ->values();

        return response()->json($departments);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'employee_id' => 'required|numeric',
            'email' => 'required|email',
            'designation' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/'
            ],
            'password_confirmation' => 'required|min:8',
            'role_id' => 'required|integer|exists:roles,id',
            'su_head' => 'required',
            'phone_number' => 'required',
            'status' => 'required|in:0,1',
            'gender' => 'required',
            'address' => 'required',
            'strategic_unit' => 'required',
            ], [
                'name.required' => 'Full Name is required',
                'employee_id.required' => 'Employee ID is required',
                'email.required' => 'Email is required',
                'password.regex' => 'Password must include at least one uppercase letter, one number, and one special character (!@#$%^&*).',
                'role_id.required' => 'Please select a role',
                'role_id.exists' => 'The selected role is invalid',
                'su_head.required' => 'SU/FU head must be required',
                // 'phone_number.numeric' => 'Phone Number should be numeric',
                // 'phone_number.regex' => 'Phone Number must start with +92 and be exactly 13 digits long',
                'status.required' => 'Status is required',
                'gender.required' => 'Gender is required',
                'address.required' => 'Address is required',
                'status.required' => 'Status is required',
                'status.in' => 'Invalid status selected',
            ]);

        // $employeeIdExists = User::where('employee_id', $request->employee_id)->exists();

        // if ($employeeIdExists) {
        //     return redirect()->route('manageuser')->with('error', 'Invalid Employee ID');
        // }

        // $emailExists = User::where('email', $request->email)->exists();
        // if ($emailExists) {
        //     return redirect()->route('manageuser')->with('error', 'Email already exists.');
        // }

        $user_date = User::create([
            'name' => $request->name,
            'employee_id' => $request->employee_id,
            'designation' => $request->designation,
            'password' => Hash::make($request->password),
            'email' => $request->email,
            'campus_id' => json_encode($request->campus_id),
            'department_id' => json_encode($request->department_id),
            'status' => $request->status,
            'su_head' => $request->su_head,
            'role_id' => $request->role_id,
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'strategic_unit' => $request->strategic_unit,
            'gender' => $request->gender,
            'address' => $request->address,
            'created_by'   => Auth::user()->employee_id,
        ]);

        $model_array = array(
            'model_type' => 'App\Models\User',
            'role_id'   => $request->input('role_id'),
            'model_id'   => $user_date->id,

        );
        DB::table('model_has_roles')->insert($model_array);

        return redirect()->route('manageuser')->with('success','user Added Successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     $user = User::find($id);

    //     // $userRole = $user->roles->toArray();
    //     $result = DB::table('model_has_roles')->select('role_id')->where('model_id', '=' ,$id )->get();
    //     $userRole = '';
    //     if(!$result->isEmpty()){
    //         $userRole = $result[0]->role_id;
    //     }
    //     $roles = Role::select('id','name')->wherestatus(1)->get();
    //     return view('users.edit',compact('user','userRole','roles'));
    // }


    public function edit($id)
    {
        $user = User::find($id);

        // $roles = Role::select('id','name')->where('status', 1)->get();

        $rolesQuery = Role::select('id', 'name')->where('status', 1);

        if (Auth::user()->role_id != 5) {
            $rolesQuery->where('id', '!=', 5);
        }

        $roles = $rolesQuery->get();

        $campusDepartments = CampusDepartment::where('status', 1)
            ->with('campus', 'department')->select('id', 'campus_id', 'department_id')->get();

        $userCampusIds = $user->campus_id ? json_decode($user->campus_id, true) : [];
        if (!is_array($userCampusIds)) $userCampusIds = [];

        $userDepartmentIds = $user->department_id ? json_decode($user->department_id, true) : [];
        if (!is_array($userDepartmentIds)) $userDepartmentIds = [];

        return view('users.edit', compact('user','roles','campusDepartments','userCampusIds','userDepartmentIds'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'employee_id' => 'required',
            'phone_number' => [
                'required',
            ],
        ];
        if ($request->filled('password')) {
            $rules['password'] = [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*]).{8,}$/'
            ];
            $rules['password_confirmation'] = 'required|min:8';
        }
        $messages = [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'password.regex' => 'Password must include at least one uppercase letter, one number, and one special character (!@#$%^&*).',
            'phone_number.required' => 'Phone number is required',
        ];

        $data = $request->validate($rules, $messages);

        // $employeeIdExists = User::where('employee_id', $request->employee_id)->exists();

        // if (!$employeeIdExists) {
        //     return redirect()->route('manageuser')->with('error', 'Invalid Employee ID');
        // }

        $update_data = array(
            'name' => $request->name,
            'email' => $request->email,
            'su_head' => $request->su_head,
            'employee_id' => $request->employee_id,
            'status' => $request->status,
            'address' => $request->address,
            'role_id' => $request->role_id,
            'gender' => $request->gender,
            'campus_id' => json_encode($request->campus_id),
            'department_id' => json_encode($request->department_id),
            'phone_number' => $request->phone_number,
            'designation' => $request->designation,
            'functionally_reports_to' => $request->functionally_reports_to,
            'functional_head_name' => $request->functional_head_name,
            'administratively_reports_to' => $request->administratively_reports_to,
            'admin_head_name' => $request->admin_head_name,
            'strategic_unit' => $request->strategic_unit,
        );

        if ($request->filled('password')) {
            $update_data['password'] = Hash::make($request->password);
        }

        User::whereid($request->id)->update($update_data);
        $user = User::find($request->id);

        $role = DB::table('model_has_roles')->where('model_id', $user->id)->first();

       if($role){
        DB::table('model_has_roles')->where('model_id', $user->id)->update(['role_id' => $request->role_id]);
       }else{
            $model_array = array(
                'model_type' => 'App\Models\User',
                'role_id'   => $request->input('role_id'),
                'model_id'   => $user->id,

            );
            DB::table('model_has_roles')->insert($model_array);
       }

        return redirect()->route('manageuser')->with('success','Record updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $id = $_REQUEST['id'];
        User::whereid($id)->delete();
        return redirect()->route('manageuser')->with('error','Data Deleted Successfully');
    }
    /**
     *
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function changeStatus()
    // {
    //     $id = $_REQUEST['id'];
    //     $status = $_REQUEST['status'];
    //     $update_data = array(
    //         'status' => $status,
    //     );
    //     User::whereid($id)->update($update_data);
    //     return redirect()->route('manageuser');
    // }

    public function profile() {
        $user = Auth::user();
        $userprofile = User::where('employee_id', Auth::user()->employee_id)->first();
        return view('users.profile', compact('user', 'userprofile'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'profile_img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_img')) {
            $destinationPath = 'userprofile';
            $myimage = time() . '_' . $request->profile_img->getClientOriginalName();
            $request->profile_img->move(public_path($destinationPath), $myimage);

            $user->profile_img = $destinationPath . '/' . $myimage;
            $user->save();

            return redirect()->back()->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->with('error', 'Please select a valid image.');
    }

}
