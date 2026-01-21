<?php

namespace App\Http\Controllers;

use App\Models\organizationlogo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\TraitFunctions;

class OrganizationlogoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    use TraitFunctions;

    public function index()
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('managelogo-all', $role_data);
        $check_read_permission = $this->checkPermissions('managelogo-read', $role_data);
        if($check_read_permission == true || $check_all_permission == true){
            $write_permission = $this->checkPermissions('managelogo-write', $role_data);
            $edit_permission = $this->checkPermissions('managelogo-edit', $role_data);
            $delete_permission = $this->checkPermissions('managelogo-delete', $role_data);
            return view('logo.manage', compact('check_all_permission','check_read_permission','write_permission','edit_permission','delete_permission')); 
        }else{
        $error = "403";
        $heading = "Oops! Forbidden";
        $message = "You don't have permission to access this module";
        return view('errors.error',compact('message','error','heading'));
      }
    }

    // public function index()
    // {
    //     return view('logo.manage');
    // }
    

    public function updateOrgLogo(Request $request)
    {
        $request->validate([
        'org_logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],
        [
            'org_logo.required' => 'Organization Profile is required.',
        ]);

        if ($request->hasFile('org_logo')) {
            $destinationPath = public_path('organizationlogo');

            $fileName = time() . '_' . $request->org_logo->getClientOriginalName();

            $existingLogo = OrganizationLogo::find(1);
            if ($existingLogo && file_exists($destinationPath . '/' . $existingLogo->org_logo)) {
                unlink($destinationPath . '/' . $existingLogo->org_logo);
            }

            $request->org_logo->move($destinationPath, $fileName);

            // Update or create the record
            OrganizationLogo::updateOrCreate(
                ['id' => 1],
                ['org_logo' => $fileName]
            );

            return redirect()->back()->with('success', 'Profile updated successfully.');
        }

        return redirect()->back()->with('error', 'Please select a valid image.');
    } 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        //
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\organizationlogo  $organizationlogo
     * @return \Illuminate\Http\Response
     */
    public function show(organizationlogo $organizationlogo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\organizationlogo  $organizationlogo
     * @return \Illuminate\Http\Response
     */
    public function edit(organizationlogo $organizationlogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\organizationlogo  $organizationlogo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, organizationlogo $organizationlogo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\organizationlogo  $organizationlogo
     * @return \Illuminate\Http\Response
     */
    public function destroy(organizationlogo $organizationlogo)
    {
        //
    }
}
