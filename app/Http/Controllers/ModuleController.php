<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Module;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Traits\TraitFunctions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    use TraitFunctions;
    
    public function index()
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('module-all', $role_data);
        $check_read_permission = $this->checkPermissions('module-read', $role_data);
        if($check_read_permission == true || $check_all_permission == true){
            $write_permission = $this->checkPermissions('module-write', $role_data);
            $edit_permission = $this->checkPermissions('module-edit', $role_data);
            $delete_permission = $this->checkPermissions('module-delete', $role_data);
            $modules = Module::all();
            return view('module.manage',compact('modules','write_permission','edit_permission','delete_permission','check_all_permission'));
        }else{
          $error = "403";
          $heading = "Oops! Forbidden";
          $message = "You don't have permission to access this module";
          return view('errors.error',compact('message','error','heading'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::select('id','name')->wherestatus(1)->get();
        return view('module.add', compact('category'));   
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
            'module_name' => 'required',
            'status' => 'required',
            'icon' => 'required',
            'order_via' => 'required|integer',
            'category_id' => 'required',
        ], 
        [
            'module_name.required' => 'Module Name is required',
            'order_via.required' => 'Order Via is required',
            'order_via.integer' => 'Order Via must be an integer',
            'category_id.required' => 'Category Selected is required',
        ]);
    
        $existingOrder = Module::where('order_via', $request->order_via)
            ->where('category_id', $request->category_id)->first();

        if ($existingOrder) {
            return redirect()->back()->withErrors([
                'order_via' => 'The provided Order Via value already exists in this category. Please choose a different value.'
            ])->withInput();
        }
    
        $maxOrderVia = Module::where('category_id', $request->category_id)->max('order_via');
        
        if ($request->order_via > ($maxOrderVia + 1)) {
            return redirect()->back()->withErrors([
                'order_via' => 'The provided Order Via value creates a gap in this category. Please choose a value less than or equal to '.($maxOrderVia + 1).'.'
            ])->withInput();
        }
    
        Module::create([
            'module_name' => $request->module_name,
            'slug' => $request->slug,
            'menu_template' => $request->menu_template,
            'icon' => $request->icon,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'order_via' => $request->order_via,
            'created_by' => Auth::user()->id,
        ]);
    
        return redirect()->route('managemodule')->with('success', 'Data Added Successfully');
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
        $module = Module::find($id);
        $category = Category::select('id','name')->wherestatus(1)->get();
        return view('module.edit',compact('module','category'));
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
            'module_name' => 'required',
            'slug' => 'required',
            'icon' =>'required',
            'order_via' => 'required|integer',
        ], 
        [
            'module_name.required' => 'Module Name is required',
            'order_via.required' => 'Order Via is required',
            'order_via.integer' => 'Order Via must be an integer',
        ]);
    
        $module = Module::find($request->id);
        if (!$module) {
            return redirect()->route('managemodule')->with('error', 'Module not found');
        }
    
        $update_data = [
            'module_name' => $request->module_name,
            'slug' => $request->slug,
            'menu_template' => $request->menu_template,
            'category_id' => $request->category_id,
            'icon' => $request->icon,
            'status' => $request->status,
            'order_via' => $request->order_via,
        ];
    
        // If the order_via hasn't changed, just update the module
        if ($module->order_via == $request->order_via) {
            $module->update($update_data);
            return redirect()->route('managemodule')->with('success', 'Data Updated Successfully');
        }
    
        // Check for gaps and conflicts within the specific category
        $maxOrderVia = Module::where('category_id', $request->category_id)->max('order_via');
        
        if ($request->order_via > ($maxOrderVia + 1)) {
            return redirect()->back()->withInput()->withErrors(['order_via' => 'Order Via must be sequential within this category and cannot skip numbers.']);
        }
    
        $existingModule = Module::where('order_via', $request->order_via)
            ->where('category_id', $request->category_id)
            ->where('id', '!=', $request->id)
            ->first();
    
        if ($existingModule) {
            // Swap the order_via values
            $existingModule->order_via = $module->order_via;
            $existingModule->save();
        } else {
            return redirect()->back()->withInput()->withErrors(['order_via' => 'Order Via does not exist in the sequence, so it cannot be swapped.']);
        }
    
        Module::whereid($request->id)->update($update_data);
    
        return redirect()->route('managemodule')->with('success', 'Data Updated Successfully');
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
        Module::whereid($id)->delete();
        return redirect()->route('managemodule')->with('error','Data Deleted Successfully');
    }

    public function changeStatus()
    {
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $update_data = array(
            'status' => $status,
        );
        Module::whereid($id)->update($update_data);
        return redirect()->route('managemodule');
    }

    public function changeUrl(Request $request) {
        // Validate inputs
        $validated = $request->validate([
            'old_url' => 'required|string',
            'new_url' => 'required|string',
        ]);
    
        $oldurl = $validated['old_url'];
        $newurl = $validated['new_url'];
        
        // Update all modules in one query
        $updated = Module::query()
        ->where('menu_template', 'like', '%' . $oldurl . '%')
        ->update([
            'menu_template' => DB::raw("REPLACE(menu_template, '{$oldurl}', '{$newurl}')")
        ]);

       if ($updated) {
           return response()->json(['message' => 'URL changed successfully']);
       } else {
           return response()->json(['message' => 'No records updated'], 404);
       }
       {
       return response()->json(['message' => 'An error occurred'], 500);
       }
    } 

}