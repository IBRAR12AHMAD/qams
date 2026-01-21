<?php

namespace App\Http\Controllers;

use App\Models\Checklist;
use App\Models\Audit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\TraitFunctions;
use Yajra\DataTables\Facades\DataTables;


class ChecklistController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('managechecklist-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('managechecklist-read', $role_data);
    //     if($check_read_permission == true || $check_all_permission == true){
    //         $write_permission = $this->checkPermissions('managechecklist-write', $role_data);
    //         $edit_permission = $this->checkPermissions('managechecklist-edit', $role_data);
    //         $delete_permission = $this->checkPermissions('managechecklist-delete', $role_data);
    //         $checklists = Checklist::with('audit')->get();
    //         return view('checklist.manage',compact('checklists', 'write_permission','edit_permission','delete_permission','check_all_permission'));
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
        $check_all_permission = $this->checkPermissions('managechecklist-all', $role_data);
        $check_read_permission = $this->checkPermissions('managechecklist-read', $role_data);

        if ($check_read_permission || $check_all_permission) {
            $write_permission = $this->checkPermissions('managechecklist-write', $role_data);
            $edit_permission = $this->checkPermissions('managechecklist-edit', $role_data);
            $delete_permission = $this->checkPermissions('managechecklist-delete', $role_data);

            if ($request->ajax()) {
                $checklists = Checklist::select(['id', 'title', 'status']);

                return DataTables::of($checklists)
                    ->addColumn('status', function ($row) {
                        return $row->status == 1
                            ? '<div class="badge badge-light-success">Active</div>'
                            : '<div class="badge badge-light-danger">Inactive</div>';
                    })
                    ->addColumn('action', function ($row) use ($edit_permission, $check_all_permission) {
                        $btn = '';
                        if ($edit_permission || $check_all_permission) {
                            $btn .= '<a href="'.route('editchecklist', $row->id).'" class="btn btn-sm btn-success me-1">Edit</a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['status', 'action'])
                    ->make(true);
            }
            return view('checklist.manage', compact('write_permission','edit_permission','delete_permission','check_all_permission'));
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
        // $audits = Audit::where('status', 1)->get();
        return view("checklist.add");
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            // 'audit_id'=> 'required',
        ]);
        Checklist::create([
            'title'=> $request->title,
            'status' => $request->status,
            // 'audit_id'=> $request->audit_id,
            'created_by'   => Auth::user()->employee_id,
        ]);
        return redirect()->route('managechecklist')->with('success', 'Data Added Successfully');
    }
    /**
     * Display the specified resource.
     */
    public function show(Checklist $checklists)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checklist $checklists, $id)
    {
        $checklists = Checklist::findOrFail($id);
        // $audits = Audit::where('status',1)->get();
        return view('checklist.edit', compact('checklists'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checklist $checklist)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            // 'audit_id'=> 'required',
        ]);
        $update_data = [
            'title' => $request->title,
            // 'audit_id' => $request->audit_id,
            'status' => $request->status,
        ];
        Checklist::whereid($request->id)->update($update_data);
        return redirect()->route('managechecklist')->with('success', 'Data Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist)
    {
        //
    }
}
