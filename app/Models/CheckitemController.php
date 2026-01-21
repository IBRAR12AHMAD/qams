<?php

namespace App\Http\Controllers;

use App\Models\Checkitem;
use App\Models\Checklist;
use App\Models\Itemheader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\TraitFunctions;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class CheckitemController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $role_data = $this->checkRole(Auth::user()->role_id);
        $check_all_permission = $this->checkPermissions('managechecklistitem-all', $role_data);
        $check_read_permission = $this->checkPermissions('managechecklistitem-read', $role_data);

        if ($check_read_permission || $check_all_permission) {
            $write_permission  = $this->checkPermissions('managechecklistitem-write', $role_data);
            $edit_permission   = $this->checkPermissions('managechecklistitem-edit', $role_data);
            $delete_permission = $this->checkPermissions('managechecklistitem-delete', $role_data);

            if ($request->ajax()) {
                $query = Checkitem::query();
                return DataTables::of($query)
                    ->addColumn('checklist_names', function ($item) {
                        $ids = $item->checklist_id ?? [];
                        if (!is_array($ids)) {
                            $ids = json_decode($ids, true) ?? [];
                        }

                        if (empty($ids)) {
                            return 'No checklist assigned';
                        }
                        return Checklist::whereIn('id', $ids)->pluck('title')->implode(', ');
                    })
                    ->addColumn('status', function ($item) {
                        $status = $item->status;
                        if (!is_array($status)) {
                            $status = json_decode($status, true) ?? [];
                        }
                        return (is_array($status) && in_array(1, $status))
                            ? '<span class="badge badge-light-success">Active</span>'
                            : '<span class="badge badge-light-danger">Inactive</span>';
                    })
                    ->addColumn('action', function ($item) use ($edit_permission, $check_all_permission) {
                        if ($edit_permission || $check_all_permission) {
                            return '<a href="'.route('editchecklistitem', $item->id).'" class="btn btn-sm btn-success">Edit</a>';
                        }
                        return '';
                    })
                    ->filterColumn('checklist_names', function ($query, $keyword) {
                        $query->whereExists(function ($subQuery) use ($keyword) {
                            $subQuery->select(DB::raw(1))
                                ->from('checklists')
                                ->whereRaw('FIND_IN_SET(checklists.id, REPLACE(REPLACE(REPLACE(REPLACE(checkitems.checklist_id, \'["\', \'\'), \'"]\', \'\'), \'[\', \'\'), \']\', \'\'))')
                                ->where('checklists.title', 'like', "%{$keyword}%");
                        });
                    })
                ->rawColumns(['status', 'action'])
                ->make(true);
            }

            return view('checklistitem.manage', compact('write_permission','edit_permission','delete_permission','check_all_permission'));
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
        $checklists = Checklist::where('status', 1)->get();
        $itemheaders = Itemheader::where('status', 1)->get();
        return view("checklistitem.add", compact('checklists', 'itemheaders'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'header_id'       => 'required|array',
            'header_id.*'     => 'required|integer',
            'status'          => 'required|array',
            'status.*'        => 'required|in:0,1',
        ], [
            'header_id.*.required'    => 'Header is required.',
            'status.*.required'       => 'Status is required.',
        ]);

        $parameters = $request->parameter ?? [];
        $selectItems = $request->select_items ?? [];

        foreach ($selectItems as $index => $item) {
            if ($item == "1") {
                $parameters[$index] = "textarea";
            } elseif ($item == "2") {
                $parameters[$index] = "radio";
            } elseif ($item == "3") {
                $parameters[$index] = "empty";
            } else {
                $parameters[$index] = $parameters[$index] ?? null;
            }
        }

        Checkitem::create([
            'checklist_id'    => $request->checklist_id,
            'row_ordernumber' => $request->row_ordernumber,
            'parameter'       => json_encode($parameters, JSON_UNESCAPED_SLASHES),
            'header_id'       => json_encode($request->header_id),
            'select_items'    => json_encode($selectItems),
            'status'          => json_encode($request->status),
            'created_by'   => Auth::user()->employee_id,
        ]);

        return redirect()->route('managechecklistitem')->with('success', 'Data Saved Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Checkitem $checkitems)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkitem $checkitems, $id)
    {
        $checkitems = Checkitem::findOrFail($id);
        $checklists = Checklist::where('status', 1)->get();
        $itemheaders = Itemheader::where('status', 1)->get();

        $raw = $checkitems->checklist_id;

        if (is_array($raw)) {
            $selectedchecklist = $raw;
        }
        elseif (is_string($raw)) {

            $decoded = json_decode($raw, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $selectedchecklist = $decoded;
            } else {
                $selectedchecklist = array_filter(array_map('trim', explode(',', $raw)));
            }
        }
        else {
            $selectedchecklist = [];
        }
        return view('checklistitem.edit', compact('checkitems', 'selectedchecklist', 'checklists', 'itemheaders'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'header_id'      => 'required|array',
            'header_id.*'    => 'required|integer',
            'status'         => 'required|array',
            'status.*'       => 'required|in:0,1',
        ], [
            'header_id.*.required'    => 'Header is required',
            'status.*.required'       => 'Status is required',
        ]);

        $parameters = $request->parameter ?? [];
        $selectItems = $request->select_items ?? [];

        foreach ($selectItems as $index => $item) {
            if ($item == "1") {
                $parameters[$index] = "textarea";
            } elseif ($item == "2") {
                $parameters[$index] = "radio";
            } elseif ($item == "3") {
                $parameters[$index] = "empty";
            } else {
                $parameters[$index] = $parameters[$index] ?? null;
            }
        }
        $update_data = [
            'checklist_id'    => $request->checklist_id,
            'row_ordernumber' => $request->row_ordernumber,
            'parameter'       => json_encode($parameters, JSON_UNESCAPED_SLASHES),
            'header_id'       => json_encode($request->header_id),
            'select_items'    => json_encode($selectItems),
            'status'          => json_encode($request->status),
        ];
        Checkitem::whereId($request->id)->update($update_data);
        return redirect()->route('managechecklistitem')->with('success', 'Data Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkitem $checkitems)
    {
        //
    }
}
