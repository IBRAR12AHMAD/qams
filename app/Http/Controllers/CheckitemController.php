<?php

namespace App\Http\Controllers;

use App\Models\Checkitem;
use App\Models\Checklist;
use App\Models\Itemheader;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Traits\TraitFunctions;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB; // Add this import


class CheckitemController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('managechecklistitem-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('managechecklistitem-read', $role_data);

    //     if($check_read_permission == true || $check_all_permission == true){
    //         $write_permission = $this->checkPermissions('managechecklistitem-write', $role_data);
    //         $edit_permission = $this->checkPermissions('managechecklistitem-edit', $role_data);
    //         $delete_permission = $this->checkPermissions('managechecklistitem-delete', $role_data);

    //         $checklistitems = Checkitem::all();
    //         $allIds = collect($checklistitems)->flatMap(function($item){
    //             $ids = $item->checklist_id;

    //             if (is_null($ids)) return [];

    //             if (!is_array($ids)) {
    //                 if (is_string($ids)) {
    //                     $ids = array_filter(array_map('trim', explode(',', $ids)));
    //                 } else {
    //                     return [];
    //                 }
    //             }
    //             return $ids;
    //         })->unique()->filter()->values()->all();

    //         $checklists = Checklist::whereIn('id', $allIds)->pluck('title', 'id')->toArray();

    //         return view('checklistitem.manage', compact('checklistitems','checklists','write_permission','edit_permission','delete_permission','check_all_permission'));
    //     }else{
    //     $error = "403";
    //     $heading = "Oops! Forbidden";
    //     $message = "You don't have permission to access this module";
    //     return view('errors.error',compact('message','error','heading'));
    //     }
    // }

    // public function index(Request $request)
    // {
    //     $role_data = $this->checkRole(Auth::user()->role_id);
    //     $check_all_permission = $this->checkPermissions('managechecklistitem-all', $role_data);
    //     $check_read_permission = $this->checkPermissions('managechecklistitem-read', $role_data);

    //     if ($check_read_permission || $check_all_permission) {
    //         $write_permission  = $this->checkPermissions('managechecklistitem-write', $role_data);
    //         $edit_permission   = $this->checkPermissions('managechecklistitem-edit', $role_data);
    //         $delete_permission = $this->checkPermissions('managechecklistitem-delete', $role_data);

    //         if ($request->ajax()) {
    //             $query = Checkitem::query();
    //             return DataTables::of($query)
    //                 ->addColumn('checklist_names', function ($item) {
    //                     $ids = $item->checklist_id;

    //                     if (is_null($ids)) {
    //                         $ids = [];
    //                     } elseif (!is_array($ids)) {
    //                         $ids = array_filter(array_map('trim', explode(',', $ids)));
    //                     }

    //                     if (empty($ids)) {
    //                         return 'N/A';
    //                     }

    //                     return Checklist::whereIn('id', $ids)
    //                         ->pluck('title')
    //                         ->implode(', ');
    //                 })
    //                 ->addColumn('status', function ($item) {
    //                     $status = json_decode($item->status, true);

    //                     return (is_array($status) && in_array(1, $status))
    //                         ? '<span class="badge badge-light-success">Active</span>'
    //                         : '<span class="badge badge-light-danger">Inactive</span>';
    //                 })
    //                 ->addColumn('action', function ($item) use ($edit_permission, $check_all_permission) {
    //                     if ($edit_permission || $check_all_permission) {
    //                         return '<a href="'.route('editchecklistitem', $item->id).'" class="btn btn-sm btn-success">Edit</a>';
    //                     }
    //                     return '';
    //                 })
    //                 ->filterColumn('checklist_names', function ($query, $keyword) {
    //                     $query->whereIn('id', function ($sub) use ($keyword) {
    //                         $sub->select('checkitems.id')
    //                             ->from('checkitems')
    //                             ->whereRaw("
    //                                 EXISTS (
    //                                     SELECT 1 FROM checklists
    //                                     WHERE FIND_IN_SET(checklists.id, checkitems.checklist_id)
    //                                     AND checklists.title LIKE ?
    //                                 )
    //                             ", ["%{$keyword}%"]);
    //                     });
    //                 })
    //             ->rawColumns(['status', 'action'])
    //             ->make(true);
    //         }
    //         return view('checklistitem.manage', compact('write_permission','edit_permission','delete_permission','check_all_permission'));
    //     } else {
    //         $error = "403";
    //         $heading = "Oops! Forbidden";
    //         $message = "You don't have permission to access this module";
    //         return view('errors.error', compact('message','error','heading'));
    //     }
    // }

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
     * Import the form for creating a new resource.
     */
    //dropdown excel
    // public function importChecklistItems(Request $request)
    // {
    //     $request->validate([
    //         'excel_file' => 'required|mimes:xlsx,xls'
    //     ]);

    //     $spreadsheet = IOFactory::load($request->file('excel_file')->getPathname());
    //     $sheetData   = $spreadsheet->getActiveSheet()->toArray();

    //     unset($sheetData[0]);
    //     $imported = false;
    //     $groupedData = [];

    //     foreach ($sheetData as $row) {

    //         $rowOrder = isset($row[0]) ? trim($row[0]) : null;

    //         if ($rowOrder === null || $rowOrder === '') {
    //             continue;
    //         }

    //         $rowOrder = (int) $rowOrder;

    //         $checklistIds = !empty($row[1])
    //             ? array_map(fn($id) => (string) trim($id), explode(',', $row[1]))
    //             : [];

    //         $headerTitle = isset($row[2]) && trim($row[2]) !== ''
    //             ? trim($row[2])
    //             : null;

    //         $headerId = null;
    //         if ($headerTitle) {
    //             $header = ItemHeader::where('title', $headerTitle)->first();
    //             $headerId = $header ? (string) $header->id : null;
    //         }

    //         $select_items_raw = array_key_exists(4, $row) ? trim($row[4]) : null;

    //         $status = isset($row[5]) && trim($row[5]) !== ''
    //             ? (string) $row[5]
    //             : '1';

    //         if ($select_items_raw === '1') {
    //             $parameter = 'textarea';
    //         } elseif ($select_items_raw === '2') {
    //             $parameter = 'radio';
    //         } elseif ($select_items_raw === '3') {
    //             $parameter = 'empty';
    //         } else {
    //             $parameter = isset($row[3]) && trim($row[3]) !== ''
    //                 ? trim($row[3])
    //                 : null;
    //         }

    //         $select_items = $select_items_raw !== '' ? $select_items_raw : null;

    //         if (!isset($groupedData[$rowOrder])) {
    //             $groupedData[$rowOrder] = [
    //                 'checklist_id' => [],
    //                 'header_id'    => [],
    //                 'parameter'    => [],
    //                 'select_items' => [],
    //                 'status'       => [],
    //             ];
    //         }
    //         $groupedData[$rowOrder]['checklist_id'] = array_unique(array_merge($groupedData[$rowOrder]['checklist_id'], $checklistIds));
    //         $groupedData[$rowOrder]['header_id'][]    = $headerId;
    //         $groupedData[$rowOrder]['parameter'][]    = $parameter;
    //         $groupedData[$rowOrder]['select_items'][] = $select_items;
    //         $groupedData[$rowOrder]['status'][]       = $status;
    //     }
    //     foreach ($groupedData as $rowOrder => $data) {
    //         Checkitem::updateOrCreate(
    //             ['row_ordernumber' => $rowOrder],
    //             [
    //                 'checklist_id' => $data['checklist_id'],
    //                 'header_id'    => json_encode($data['header_id']),
    //                 'parameter'    => json_encode($data['parameter'], JSON_UNESCAPED_SLASHES),
    //                 'select_items' => json_encode($data['select_items']),
    //                 'status'       => json_encode($data['status']),
    //                 'created_by'   => Auth::user()->employee_id,
    //             ]
    //         );
    //         $imported = true;
    //     }
    //     if ($imported) {
    //         return redirect()->route('managechecklistitem')->with('success', 'Excel data imported successfully.');
    //     }
    //     return redirect()->route('managechecklistitem')->with('error', 'No Checklistitem were imported. Please check Excel file');
    // }
    public function importChecklistItems(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls'
        ]);

        try {
            $spreadsheet = IOFactory::load($request->file('excel_file')->getPathname());
        } catch (Exception $e) {
            return redirect()->route('managechecklistitem')
                ->with('error', 'Failed to read Excel file: ' . $e->getMessage());
        }

        $sheetData = $spreadsheet->getActiveSheet()->toArray();

        if (count($sheetData) <= 1) {
            return redirect()->route('managechecklistitem')->with('error', 'Excel file is empty or missing rows.');
        }

        $expectedHeaders = ['row_ordernumber', 'checklist_id', 'header_id', 'parameter', 'select_item', 'status'];
        $fileHeaders = array_map('trim', $sheetData[0]);

        if ($fileHeaders !== $expectedHeaders) {
            return redirect()->route('managechecklistitem')->with('error', 'Invalid Excel format. Please check Excel file.');
        }

        unset($sheetData[0]);
        $imported = false;
        $groupedData = [];
        $errors = [];
        foreach ($sheetData as $index => $row) {
            $rowNumber = $index + 2;
            $rowOrder = isset($row[0]) ? trim($row[0]) : null;
            if (!$rowOrder) {
                $errors[] = "Row $rowNumber: Row Order is empty.";
                continue;
            }
            $rowOrder = (int) $rowOrder;

            $checklistIds = !empty($row[1])
                ? array_map(fn($id) => (string) trim($id), explode(',', $row[1]))
                : [];

            $headerTitle = isset($row[2]) && trim($row[2]) !== '' ? trim($row[2]) : null;
            $headerId = null;
            if ($headerTitle) {
                $header = ItemHeader::where('title', $headerTitle)->first();
                if (!$header) {
                    $errors[] = "Row $rowNumber: Header title '$headerTitle' not found.";
                } else {
                    $headerId = (string) $header->id;
                }
            }
            $select_items_raw = $row[4] ?? null;
            $status = isset($row[5]) && trim($row[5]) !== '' ? (string) $row[5] : '1';

            if ($select_items_raw === '1') {
                $parameter = 'textarea';
            } elseif ($select_items_raw === '2') {
                $parameter = 'radio';
            } elseif ($select_items_raw === '3') {
                $parameter = 'empty';
            } else {
                $parameter = isset($row[3]) && trim($row[3]) !== '' ? trim($row[3]) : null;
            }

            $select_items = $select_items_raw !== '' ? $select_items_raw : null;

            if (!isset($groupedData[$rowOrder])) {
                $groupedData[$rowOrder] = [
                    'checklist_id' => [],
                    'header_id'    => [],
                    'parameter'    => [],
                    'select_items' => [],
                    'status'       => [],
                ];
            }

            $groupedData[$rowOrder]['checklist_id'] = array_unique(array_merge($groupedData[$rowOrder]['checklist_id'], $checklistIds));
            $groupedData[$rowOrder]['header_id'][]    = $headerId;
            $groupedData[$rowOrder]['parameter'][]    = $parameter;
            $groupedData[$rowOrder]['select_items'][] = $select_items;
            $groupedData[$rowOrder]['status'][]       = $status;
        }
        foreach ($groupedData as $rowOrder => $data) {
            try {
                Checkitem::updateOrCreate(
                    ['row_ordernumber' => $rowOrder],
                    [
                        'checklist_id' => $data['checklist_id'],
                        'header_id'    => json_encode($data['header_id']),
                        'parameter'    => json_encode($data['parameter'], JSON_UNESCAPED_SLASHES),
                        'select_items' => json_encode($data['select_items']),
                        'status'       => json_encode($data['status']),
                        'created_by'   => Auth::user()->employee_id,
                    ]
                );
                $imported = true;
            } catch (Exception $e) {
                $errors[] = "Row Order $rowOrder: Failed to import. Error: " . $e->getMessage();
            }
        }
        if ($imported && empty($errors)) {
            return redirect()->route('managechecklistitem')->with('success', 'Excel data imported successfully.');
        } elseif ($imported && !empty($errors)) {
            return redirect()->route('managechecklistitem')->with('warning', 'Excel imported with some errors: ' . implode(' | ', $errors));
        } else {
            return redirect()->route('managechecklistitem')->with('error', 'No Checklistitem imported. Errors: ' . implode(' | ', $errors));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'header_id'      => 'required|array',
    //         'header_id.*'    => 'required|integer',
    //         'status'         => 'required|array',
    //         'status.*'       => 'required|in:0,1',
    //         'order_number'   => 'required|array',
    //         'order_number.*' => 'required|integer',
    //     ],
    //     [
    //         'header_id.*.required'     => 'Header is required',
    //         'status.*.required'        => 'Status is required',
    //         'order_number.*.required'  => 'Order number',
    //     ]);

    //     $parameters = $request->parameter ?? [];
    //     $selectItems = $request->select_items ?? [];

    //     foreach ($selectItems as $index => $item) {
    //         if (empty($item)) {
    //             $request->validate([
    //                 "parameter.$index" => 'required|string',
    //             ], [
    //                 "parameter.$index.required" => "Parameter is required.",
    //             ]);
    //         } else {
    //             $parameters[$index] = null;
    //         }
    //     }
    //     $request->merge(['parameter' => $parameters]);

    //     Checkitem::create([
    //         'checklist_id'    => $request->checklist_id,
    //         'row_ordernumber' => $request->row_ordernumber,
    //         'parameter'       => json_encode($parameters, JSON_UNESCAPED_SLASHES),
    //         'header_id'       => json_encode($request->header_id),
    //         'select_items'    => json_encode($selectItems),
    //         'status'          => json_encode($request->status),
    //         'order_number'    => json_encode($request->order_number),
    //         'created_by'      => Auth::id(),
    //     ]);
    //     return redirect()->route('managechecklistitem')->with('success', 'Checklist Item Saved Successfully');
    // }

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
    // public function update(Request $request)
    // {
    //     $request->validate([
    //         'header_id'      => 'required|array',
    //         'header_id.*'    => 'required|integer',
    //         'status'         => 'required|array',
    //         'status.*'       => 'required|in:0,1',
    //         'order_number'   => 'required|array',
    //         'order_number.*' => 'required|integer',
    //     ],
    //     [
    //         'header_id.*.required'     => 'Header is required',
    //         'status.*.required'        => 'Status is required',
    //         'order_number.*.required'  => 'Order number',
    //     ]);

    //     $parameters = $request->parameter;

    //     $update_data = [
    //         'checklist_id'   => $request->checklist_id,
    //         'row_ordernumber'=> $request->row_ordernumber,
    //         'parameter'      => json_encode($parameters, JSON_UNESCAPED_SLASHES),
    //         'header_id'      => json_encode($request->header_id),
    //         'select_items'   => json_encode($request->select_items),
    //         'status'         => json_encode($request->status),
    //         'order_number'   => json_encode($request->order_number),
    //     ];

    //     Checkitem::whereId($request->id)->update($update_data);
    //     return redirect()->route('managechecklistitem')->with('success', 'Data Updated Successfully');
    // }
    public function update(Request $request)
    {
        $request->validate([
            'header_id'      => 'required|array',
            'header_id.*'    => 'required|integer',
            'status'         => 'required|array',
            'status.*'       => 'required|in:0,1',
            // 'order_number'   => 'required|array',
            // 'order_number.*' => 'required|integer',
        ], [
            'header_id.*.required'    => 'Header is required',
            'status.*.required'       => 'Status is required',
            // 'order_number.*.required' => 'Order number is required',
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
            // 'order_number'    => json_encode($request->order_number),
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
