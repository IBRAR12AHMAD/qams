<?php

namespace App\View\Components\Layout;

use Illuminate\View\Component;
use App\Models\User;
use App\Models\Module;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Sidebar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = User::find(Auth::user()->id);
        $module_object = new Module();
        // echo "<pre>";
        // print_r($user->roles);
        // die();

        $userRole = $user->roles->toArray();
        $categories = Category::all();
        $roles = Role::pluck('name','name')->all();
        $categories = Category::orderBy('order_via')->get();
        $module_object = Module::orderBy('order_via')->get();
        $collection = array();
        if($userRole){
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$userRole[0]['id'])
            ->get();
            // ->where("role_has_permissions.role_id",$userRole[0]['id'])
            foreach($rolePermissions as $role_per){

                $per_name = explode('-',$role_per->name);
                if(!in_array($per_name[0],$collection)){
                    $collection[] = $per_name[0];
                }
            }
        }
        return view('components.layout.sidebar',compact('collection','categories','module_object'));
        // return view('components.layout.sidebar');
    }
}
