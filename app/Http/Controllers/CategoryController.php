<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Traits\TraitFunctions;
use PDF;

class CategoryController extends Controller
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
    //     $check_all_permission = $this->checkPermissions('category-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('category-read', $role_data);
    //     if($check_read_permission == true || $check_all_permission == true){
    //       $write_permission = $this->checkPermissions('category-write', $role_data);
    //       $edit_permission = $this->checkPermissions('category-edit', $role_data);
    //       $delete_permission = $this->checkPermissions('category-delete', $role_data);
    //       $category = Category::all();
    //       return view('category.manage',compact('category', 'write_permission','edit_permission','delete_permission','check_all_permission'));
    //     }else{
    //         $error = "403";
    //         $heading = "Oops! Forbidden";
    //         $message = "You don't have permission to access this module";
    //         return view('errors.error',compact('message','error','heading'));
    //     }
    // }
    public function index(Request $request)
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('category-all', $role_data);
        $check_read_permission = $this->checkPermissions('category-read', $role_data);

        if ($check_read_permission == true || $check_all_permission == true) {
            $write_permission  = $this->checkPermissions('category-write', $role_data);
            $edit_permission   = $this->checkPermissions('category-edit', $role_data);
            $delete_permission = $this->checkPermissions('category-delete', $role_data);

            if ($request->ajax()) {
                $category = Category::select('id','name','icon','status');
                return DataTables::of($category)
                    ->editColumn('status', function($row){
                        return $row->status == 1
                            ? '<div class="badge badge-light-success">Active</div>'
                            : '<div class="badge badge-light-danger">Inactive</div>';
                    })
                    ->addColumn('action', function($row) use ($edit_permission, $check_all_permission) {
                        $btn = '';
                        if ($edit_permission == 'true' || $check_all_permission == 'true') {
                            $btn .= '<a href="'.route('editcategory',$row->id).'" class="btn btn-success btn-sm mx-1">Edit</a>';
                        }
                        return $btn;
                    })
                    ->rawColumns(['status','action'])
                    ->make(true);
            }
            return view('category.manage', compact('write_permission','edit_permission','delete_permission','check_all_permission'));
        } else {
            $error   = "403";
            $heading = "Oops! Forbidden";
            $message = "You don't have permission to access this module";
            return view('errors.error', compact('message','error','heading'));
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('category.add');
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
            'status' => 'required',
            'icon' => 'required',
            'order_via' => 'required|integer',
        ],
        [
            'name.required' => 'Category Name is required',
            'order_via.required' => 'Order Via is required',
            'order_via.integer' => 'Order Via must be an integer',
        ]);

        $existingOrder = Category::where('order_via', $request->order_via)->first();
        if ($existingOrder) {
            return redirect()->back()->withErrors([
                'order_via' => 'The provided Order Via value already exists. Please choose a different value.'
            ])->withInput();
        }

        $maxOrderVia = Category::max('order_via');

        if ($request->order_via > ($maxOrderVia + 1)) {
            return redirect()->back()->withErrors([
                'order_via' => 'The provided Order Via value creates a gap. Please choose a value less than or equal to '.($maxOrderVia + 1).'.'
            ])->withInput();
        }

        Category::create([
            'name' => $request->name,
            'status' => $request->status,
            'icon' => $request->icon,
            'order_via' => $request->order_via,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('managecategory')->with('success', 'Data Added Successfully');
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
        $category = Category::find($id);
        return view('category.edit',compact('category'));
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
            'icon' => 'required',
            'order_via' => 'required|integer',
        ],
        [
            'name.required' => 'Category Name is required',
            'order_via.required' => 'Order Via is required',
            'order_via.integer' => 'Order Via must be an integer',
        ]);

        $update_data = [
            'name' => $request->name,
            'status' => $request->status,
            'icon' => $request->icon,
            'order_via' => $request->order_via,
        ];
        $category = Category::find($request->id);
        if (!$category) {
            return redirect()->route('managecategory')->with('error', 'Category not found');
        }

        if ($category->order_via == $request->order_via) {
            $category->update($update_data);
            return redirect()->route('managecategory')->with('success', 'Data Updated Successfully');
        }

        $orderVai = Category::max('order_via');
        if ($request->order_via > $orderVai + 1) {
            return redirect()->back()->withInput()->withErrors(['order_via' => 'Order via must be sequential and cannot skip numbers.']);
        }

        $existingCategory = Category::where('order_via', $request->order_via)->where('id', '!=', $request->id)->first();

        if (!$existingCategory) {
            return redirect()->back()->withInput()->withErrors(['order_via' => 'Order via does not exist in the sequence, so it cannot be swapped.']);
        }
        $existingCategory->order_via = $category->order_via;
        $existingCategory->save();

        Category::whereid($request->id)->update($update_data);
        return redirect()->route('managecategory')->with('success', 'Data Updated Successfully');
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
        Category::whereid($id)->delete();
        return redirect()->route('managecategory')->with('error','Data Deleted Successfully');
    }

    public function changeStatus()
    {
        $id = $_REQUEST['id'];
        $status = $_REQUEST['status'];
        $update_data = array(
            'status' => $status,
        );
        Category::whereid($id)->update($update_data);
        return redirect()->route('managecategory');
    }
    // Change Request PDF
    // public function generatePDF()
    // {
    //     // return view('certificate4');
    //     $pdf = PDF::loadView('certificate4');
    //     $pdf->setPaper([0, 0, 420, 565], 'landscape');
    //     return $pdf->download('certificate4.pdf');
    // }
}
