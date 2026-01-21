<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\TraitFunctions;
use Yajra\DataTables\Facades\DataTables;

class AuditController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('manageaudit-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('manageaudit-read', $role_data);
    //     if($check_read_permission == true || $check_all_permission == true){
    //         $write_permission = $this->checkPermissions('manageaudit-write', $role_data);
    //         $edit_permission = $this->checkPermissions('manageaudit-edit', $role_data);
    //         $delete_permission = $this->checkPermissions('manageaudit-delete', $role_data);
    //         $audits = Audit::all();
    //         return view('audit.manage',compact('audits', 'write_permission','edit_permission','delete_permission','check_all_permission'));
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
        $check_all_permission = $this->checkPermissions('manageaudit-all', $role_data);
        $check_read_permission = $this->checkPermissions('manageaudit-read', $role_data);

        if ($check_read_permission || $check_all_permission) {
            $write_permission = $this->checkPermissions('manageaudit-write', $role_data);
            $edit_permission = $this->checkPermissions('manageaudit-edit', $role_data);
            $delete_permission = $this->checkPermissions('manageaudit-delete', $role_data);

            if ($request->ajax()) {
                $audits = Audit::select(['id', 'title', 'start_date', 'status']);
                return DataTables::of($audits)
                    ->addColumn('status', function ($row) {
                        return $row->status == 1
                            ? '<div class="badge badge-light-success">Active</div>'
                            : '<div class="badge badge-light-danger">Inactive</div>';
                    })
                    ->addColumn('action', function ($row) use ($edit_permission, $check_all_permission) {
                        $btn = '';
                        if ($edit_permission || $check_all_permission) {
                            $btn .= '<a href="'.route('editaudit', $row->id).'" class="btn btn-sm btn-success">Edit</a>';
                        }
                        return $btn;
                    })
                ->rawColumns(['status', 'action'])
                ->make(true);
            }
            return view('audit.manage', compact('write_permission', 'edit_permission', 'delete_permission', 'check_all_permission'));
        } else {
            $error = "403";
            $heading = "Oops! Forbidden";
            $message = "You don't have permission to access this module";
            return view('errors.error', compact('message', 'error', 'heading'));
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('audit.add');
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
        ],
        [
            'title.required' => 'Title is required',
            'start_date.required' => 'Start Date required',
        ]);
        Audit::create([
            'title' => $request->title,
            'start_date' => $request->start_date,
            'status' => $request->status,
            'created_by' => Auth::user()->employee_id,
        ]);
        return redirect()->route('manageaudit')->with('success', 'Data Added Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Audit $audit, $id)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Audit $audits, $id)
    {
        $audits = Audit::findOrFail($id);
        return view('audit.edit', compact('audits'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Audit $audits)
    {
        $request->validate([
            'title' => 'required',
            'start_date' => 'required',
            'status' => 'required',
        ],
        [
            'title.required' => 'Title is required',
            'start_date.required' => 'Start Date required',
        ]);

        $update_data = [
            'title' => $request->title,
            'start_date' => $request->start_date,
            'status' => $request->status,
        ];
        Audit::whereid($request->id)->update($update_data);
        return redirect()->route('manageaudit')->with('success', 'Data Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Audit $audit)
    {
        //
    }
}
