<?php

namespace App\Http\Controllers;

use App\Models\Itemheader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\TraitFunctions;
use Yajra\DataTables\Facades\DataTables;

class ItemheaderController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('manageheaderitem-all', $role_data);
        $check_read_permission = $this->checkPermissions('manageheaderitem-read', $role_data);

        if($check_read_permission == true || $check_all_permission == true){
            $write_permission = $this->checkPermissions('manageheaderitem-write', $role_data);
            $edit_permission = $this->checkPermissions('manageheaderitem-edit', $role_data);
            $delete_permission = $this->checkPermissions('manageheaderitem-delete', $role_data);

            if ($request->ajax()) {
                $query = Itemheader::query();
                return DataTables::of($query)
                    ->addColumn('status', function ($item) {
                        return $item->status == 1
                            ? '<span class="badge badge-light-success">Active</span>'
                            : '<span class="badge badge-light-danger">Inactive</span>';
                    })
                    ->addColumn('action', function ($item) use ($edit_permission, $check_all_permission) {
                        if ($edit_permission || $check_all_permission) {
                            return '<a href="'.route('editheaderitem', $item->id).'"
                                        class="btn btn-sm btn-success">Edit</a>';
                        }
                        return '';
                    })
                ->rawColumns(['status', 'action'])
                ->make(true);
            }
            return view('headeritem.manage', compact('write_permission','edit_permission','delete_permission','check_all_permission'));
        }else{
        $error = "403";
        $heading = "Oops! Forbidden";
        $message = "You don't have permission to access this module";
        return view('errors.error',compact('message','error','heading'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("headeritem.add");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            'order_number'=> 'required',
        ],
        [
            'order_number'=> 'Enter Order Number is required',
        ]);
        Itemheader::create([
            'title'=> $request->title,
            'status' => $request->status,
            'order_number' => $request->order_number,
            'created_by'   => Auth::user()->employee_id,
        ]);
        return redirect()->route('manageheaderitem')->with('success', 'Data Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Itemheader $itemheaders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Itemheader $itemheaders, $id)
    {
        $itemheaders = Itemheader::findOrFail($id);
        return view('headeritem.edit', compact('itemheaders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Itemheader $itemheaders)
    {
        $request->validate([
            'title' => 'required',
            'status' => 'required',
            'order_number'=> 'required',
        ],
        [
            'order_number'=> 'Enter Order Number is required',
        ]);

        $update_data = [
            'title' => $request->title,
            'order_number' => $request->order_number,
            'status' => $request->status,
        ];
        Itemheader::whereid($request->id)->update($update_data);
        return redirect()->route('manageheaderitem')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Itemheader $itemheaders)
    {
        //
    }
}
