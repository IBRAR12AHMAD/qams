<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use App\Traits\TraitFunctions;

class RoleController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $permission_object = new Permission();
    //     $roles = Role::all();
    //     return view('roles.manage',compact('roles','permission_object'));
    // }

    public function index()
    {
        $permission_object = new Permission();
        $user = Auth::user();

        if ($user->role_id == 5) {
            $roles = Role::all();
        } else {
            $roles = Role::where('id', '!=', 5)->get();
        }

        return view('roles.manage', compact('roles', 'permission_object'));
    }

    public function roleHasPermission(){

        $id = $_REQUEST['id'];

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
        ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
        ->all();

        $per = array();
        foreach($rolePermissions as $val){
            $permission = Permission::whereid($val)->select('name')->first();

            $per[] = $permission->name;
        }
        $modules = Module::all();
        if($modules){
            $i =1;
            foreach($modules as $m){
            ?>
                <tr>
                    <input type="hidden" name="module_id" value="{{ $m->id }}" id="module_id_{{$m->id}}">
                    <td> <?php  echo $m->module_name ?></td>
                    <td class="text-center">
                        <input  id="checkbox<?= $i; ?>" <?php echo in_array($m->slug.'-all', $per) ? 'checked' : '1' ; ?> onclick="changePermission('<?php echo route('addRolePermission');?>','<?php echo  $m->id ;?>','<?php echo  $m->slug;?>-all','<?php echo  $i++ ;?>')" type="checkbox">
                    </td>
                    <td class="text-center">
                        <input id="checkbox<?= $i; ?>" <?php echo in_array($m->slug.'-read', $per) ? 'checked' : '2' ; ?> onclick="changePermission('<?php echo route('addRolePermission');?>','<?php echo  $m->id ;?>','<?php echo  $m->slug;?>-read','<?php echo  $i++ ;?>')" type="checkbox">
                    </td>
                    <td class="text-center">
                        <input  id="checkbox<?= $i; ?>" <?php echo in_array($m->slug.'-write', $per) ? 'checked' : '3' ; ?> onclick="changePermission('<?php echo route('addRolePermission');?>','<?php echo  $m->id ;?>','<?php echo  $m->slug;?>-write','<?php echo  $i++ ;?>')" type="checkbox">
                    </td>
                    <td class="text-center">
                        <input id="checkbox<?= $i; ?>" <?php echo in_array($m->slug.'-edit', $per) ? 'checked' : '4' ; ?> onclick="changePermission('<?php echo route('addRolePermission');?>','<?php echo  $m->id ;?>','<?php echo  $m->slug;?>-edit','<?php echo  $i++ ;?>')" type="checkbox">
                    </td>
                    <td class="text-center">
                        <input id="checkbox<?= $i; ?>" <?php echo in_array($m->slug.'-delete', $per) ? 'checked' : '5' ; ?> onclick="changePermission('<?php echo route('addRolePermission');?>','<?php echo  $m->id ;?>','<?php echo  $m->slug;?>-delete','<?php echo  $i++ ;?>')" type="checkbox">
                    </td>
                    <td class="text-center">
                        <input id="checkbox<?= $i; ?>" <?php echo in_array($m->slug.'-status', $per) ? 'checked' : '6' ; ?> onclick="changePermission('<?php echo route('addRolePermission');?>','<?php echo  $m->id ;?>','<?php echo  $m->slug;?>-status','<?php echo  $i++ ;?>')" type="checkbox">
                    </td>
                </tr>
                <?php
            }
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' =>'required',
            'status' =>'required',
        ],
        [
            'name.required' => 'Role Name is required',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => $request->slug,
            'status' => $request->status,
            'created_by' => Auth::user()->id,
        ]);
        return redirect()->route('managerole')->with('success','Data Added Successfully');
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
    public function edit($id)
    {
        $roles = Role::find($id);
        return view('roles.edit',compact('roles'));
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
        $request->validate([
            'name' => 'required',
            'slug' =>'required',
        ],
        [
            'name.required' => 'Role Name is required',
        ]);

        $update_data = array(
            'name' => $request->name,
            'guard_name' => $request->slug,
            'status' => $request->status,
        );
        Role::whereid($request->id)->update($update_data);
        return redirect()->route('managerole')->with('success','Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = $_REQUEST['id'];
        Role::whereid($id)->delete();
        return redirect()->route('managerole')->with('error','Data Deleted Successfully');
    }

    public function changeStatus()
    {
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $update_data = array(
            'status' => $status,
        );
        Role::whereid($id)->update($update_data);
        return redirect()->route('managerole');
    }

    public function addRolePermission(){
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $role_id = $_REQUEST['id'];
        $module_id = $_REQUEST['module_id'];
        $permission_name = $_REQUEST['permission_name'];
        $checked = $_REQUEST['checked'];
        $role = Role::findOrFail($role_id);
        if($checked =='1'){
            $permission_result = Permission::create([
                'name' =>  $permission_name,
                'guard_name' => $role->guard_name,
                'status' => '1',
                'created_by' => Auth::user()->id,
            ]);
            $role->givePermissionTo($permission_result->id);
        }else if($checked =='0'){
            $permission_object = new Permission();
            $permission = Permission::wherename($permission_name)->get();
            $role->revokePermissionTo($permission);
            $permission_object->whereid($permission[0]->id)->delete();
        }
    }
}
