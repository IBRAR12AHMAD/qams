<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Itemheader;
use App\Models\Checkitem;
use App\Models\Checklist;
use App\Models\User;
use App\Models\ShowSchedule;
use App\Models\Schedule;
use App\Models\ShowScheduleLog;
use App\Traits\TraitFunctions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Support\Facades\Validator;

class ShowScheduleController extends Controller
{
    use TraitFunctions;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $role_data = $this->checkRole($user->role_id);
        $check_all_permission  = $this->checkPermissions('showschedule-all', $role_data);
        $check_read_permission = $this->checkPermissions('showschedule-read', $role_data);
        if (($check_read_permission || $check_all_permission)) {
            $write_permission  = $this->checkPermissions('showschedule-write', $role_data);
            $edit_permission   = $this->checkPermissions('showschedule-edit', $role_data);
            $delete_permission = $this->checkPermissions('showschedule-delete', $role_data);

            $allSchedulesData       = [];
            $checklistAuditMap      = [];
            $checklistScheduleMap   = [];

            if ($user->role_id == 4) {
                $scheduleIds = ShowSchedule::where('asing_to', $user->employee_id)->pluck('schedule_id')->unique();
                $schedules = Schedule::whereIn('id', $scheduleIds)->get();
            } elseif (in_array($user->role_id, [1, 2, 5])) {
                $scheduleIds = ShowSchedule::where('submit_type', 1)->pluck('schedule_id')->unique();
                $schedules = Schedule::whereIn('id', $scheduleIds)->get();
            } elseif ($user->role_id == 3) {
                $schedules = Schedule::where('aditor_id', $user->employee_id)->where('status', 1)->get();
            } else {
                $schedules = collect();
            }
            $allShowSchedules = ShowSchedule::whereIn('schedule_id',$schedules->pluck('id'))->get();
            foreach ($schedules as $schedule) {
                $checklistIds = json_decode($schedule->checklist_id, true);
                if (!is_array($checklistIds)) {
                    $checklistIds = [$schedule->checklist_id];
                }
                $checklistAuditMap[$schedule->id] = DB::table('audits')->where('id', $schedule->audit_id)->value('title');
                $checklistScheduleMap[$schedule->id] = Checklist::whereIn('id', $checklistIds)->pluck('title')->toArray();
                $relatedSchedules = $allShowSchedules->where('schedule_id', $schedule->id);
                // $finalStatus = 'pending';
                // if ($relatedSchedules->contains('submit_type', 1)) {
                //     $finalStatus = 'submitted';
                // }
                // if (in_array($user->role_id, [2, 4, 1])) {

                //     if ($relatedSchedules->contains('responded_submit_type', 1)) {
                //         $finalStatus = 'responded_submitted';
                //     } elseif (
                //         $relatedSchedules
                //             ->whereIn('responded_submit_type', [2, 0])
                //             ->isNotEmpty()
                //     ) {
                //         $finalStatus = 'responded_pending';
                //     }
                // }
                $finalStatus = 'pending';
                /*
                |--------------------------------------------------------------------------
                | Submit Type Status
                |--------------------------------------------------------------------------
                */
                if ($relatedSchedules->contains('submit_type', 1)) {
                    $finalStatus = 'submitted';
                } elseif ($relatedSchedules->contains('submit_type', 2)) {
                    $finalStatus = 'partial_submitted';
                }
                /*
                |--------------------------------------------------------------------------
                | Responded Submit Type Status (Role 1,2,4 only)
                |--------------------------------------------------------------------------
                */
                if (in_array($user->role_id, [1, 2, 4, 5])) {
                    if ($relatedSchedules->contains('responded_submit_type', 1)) {
                        $finalStatus = 'responded_submitted';
                    } elseif ($relatedSchedules->contains('responded_submit_type', 2)) {
                        $finalStatus = 'responded_partial';
                    } elseif (
                        $relatedSchedules->whereIn('responded_submit_type', [0, null])->isNotEmpty()
                    ) {
                        $finalStatus = 'responded_pending';
                    }
                }
                $auditorName = DB::table('users')->where('employee_id', $schedule->aditor_id)->value('name');

                $allSchedulesData[$schedule->id] = (object)[
                    'id'            => $schedule->id,
                    'title'         => $schedule->title,
                    'audit_id'      => $schedule->audit_id,
                    'aditor_id'     => $schedule->aditor_id,
                    'auditor_name'  => $auditorName ?? 'N/A',
                    'checklist_ids' => $checklistIds,
                    'status'        => $finalStatus,
                ];
            }
            return view('showschedule.manage', compact(
                'allSchedulesData','checklistAuditMap','checklistScheduleMap','write_permission','edit_permission','delete_permission','check_all_permission','check_read_permission'
            ));
        } else {
            $error = "403";
            $heading = "Oops! Forbidden";
            $message = "You don't have permission to access this module";
            return view('errors.error', compact('message','error','heading'));
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request, $id)
    // {
    //     $user = Auth::user();
    //     $aditorEmployeeId = $user->employee_id;
    //     //     /*
    //     //     |--------------------------------------------------------------------------
    //     //     | ROLE 4 → Responded Remarks
    //     //     |--------------------------------------------------------------------------
    //     //     */
    //     if ($user->role_id == 4) {

    //         $rowOrderNumbers = $request->row_ordernumber ?? [];
    //         $respondedRemarksArray = [];

    //         // 🔹 Validation only if responded_submit_type == 1
    //         // if ($request->responded_submit_type == 1) {
    //         //     $request->validate(
    //         //         [
    //         //             'responded_remarks'   => 'required|array',
    //         //             'responded_remarks.*' => 'required|string',
    //         //         ],
    //         //         [
    //         //             'responded_remarks.required'   => 'Remarks are required',
    //         //             'responded_remarks.*.required' => 'This field is required',
    //         //         ]
    //         //     );
    //         // }

    //         // Prepare responded remarks array
    //         foreach ($rowOrderNumbers as $rowOrd) {
    //             $remarkText = $request->responded_remarks[$rowOrd] ?? null;
    //             $remarkText = trim($remarkText ?? '');
    //             $respondedRemarksArray[] = $remarkText === '' ? null : $remarkText;
    //         }

    //         foreach ($rowOrderNumbers as $rowOrd) {
    //             $showId = $request->schedule_id[$rowOrd] ?? null;

    //             if ($showId) {
    //                 $showSchedule = ShowSchedule::find($showId);
    //                 if ($showSchedule) {

    //                     // 🔹 Always update ShowSchedule
    //                     $showSchedule->responded_remarks     = json_encode($respondedRemarksArray);
    //                     $showSchedule->responded_submit_type = $request->responded_submit_type;
    //                     $showSchedule->save();

    //                     // 🔹 Create log ONLY if responded_submit_type == 1
    //                     if ($request->responded_submit_type == 1) {

    //                         $schedule = Schedule::find($showSchedule->schedule_id);

    //                         ShowScheduleLog::create([
    //                             'schedule_id'           => $showSchedule->schedule_id,
    //                             'schedule_created'      => $schedule ? $schedule->created_by : null,
    //                             'showschedule_id'       => $showSchedule->id,
    //                             'row_ordernumber'       => $showSchedule->row_ordernumber,
    //                             'checklist_id'          => $showSchedule->checklist_id,
    //                             'parameter'             => $showSchedule->parameter,
    //                             'responded_submit_type' => $request->responded_submit_type,
    //                             'responded_remarks'     => json_encode($respondedRemarksArray),
    //                             'created_by'            => $aditorEmployeeId,
    //                         ]);
    //                     }
    //                 }
    //             }
    //         }

    //         return redirect()->route('manageshowschedule')->with(
    //             $request->responded_submit_type == 1 ? 'success' : 'primary',
    //             $request->responded_submit_type == 1
    //                 ? 'Responded remarks updated & log created successfully'
    //                 : 'Personal remarks updated successfully!'
    //         );
    //     }

    //     //     /*
    //     //     |--------------------------------------------------------------------------
    //     //     | ROLE 3 → auditor Remarks
    //     //     |--------------------------------------------------------------------------
    //     //     */
    //     $schedule = Schedule::where('id', $id)
    //         ->where('status', 1)
    //         ->first();

    //     if (!$schedule) {
    //         return redirect()->back()->with('error', 'Schedule not found.');
    //     }
    //     $rowOrderNumbers = $request->row_ordernumber ?? [];
    //     if ($user->role_id == 3) {
    //         // if ($request->submit_type == 1) {
    //         //     $rules = [];
    //         //     $messages = [];

    //         //     foreach ($request->select_items ?? [] as $rowIndex => $columns) {
    //         //         foreach ($columns ?? [] as $colIndex => $type) {
    //         //             if ($type == 1) {
    //         //                 $rules["parameter.$rowIndex.$colIndex"] = 'required';
    //         //                 $messages["parameter.$rowIndex.$colIndex.required"] = 'This field is required';
    //         //             }
    //         //         }
    //         //     }

    //         //     $validator = Validator::make($request->all(), $rules, $messages);
    //         //     if ($validator->fails()) {
    //         //         return back()->withErrors($validator)->withInput();
    //         //     }
    //         // }

    //         $allRowNumbers = $rowOrderNumbers;
    //         $checklistIds  = is_array($schedule->checklist_id)
    //             ? $schedule->checklist_id
    //             : json_decode($schedule->checklist_id ?? '[]', true);
    //         $allParameters = [];

    //         foreach ($rowOrderNumbers as $rowIndex => $rowOrd) {
    //             $rowParameters = $request->parameter[$rowIndex] ?? [];
    //             $rowTypes      = $request->select_items[$rowIndex] ?? [];

    //             foreach ($rowParameters as $colIndex => $value) {
    //                 $type = $rowTypes[$colIndex] ?? null;
    //                 if ($type == 3) {
    //                     $rowParameters[$colIndex] = "empty";
    //                 } elseif ($type == 2) {
    //                     $rowParameters[$colIndex] = $value === "" ? null : $value;
    //                 }
    //             }

    //             $allParameters[] = $rowParameters;
    //         }
    //         $showSchedule = ShowSchedule::updateOrCreate(
    //             [
    //                 'schedule_id'        => $schedule->id,
    //                 'schedule_aditor_id' => $aditorEmployeeId,
    //             ],
    //             [
    //                 'row_ordernumber' => json_encode($allRowNumbers),
    //                 'checklist_id'    => json_encode($checklistIds),
    //                 'parameter'       => json_encode($allParameters, JSON_UNESCAPED_SLASHES),
    //                 'submit_type'     => $request->submit_type,
    //                 'created_by'      => $aditorEmployeeId,
    //             ]
    //         );
    //         if ($request->submit_type == 1) {
    //             ShowScheduleLog::create([
    //                 'schedule_id'        => $schedule->id,
    //                 'schedule_created'   => $schedule->created_by,
    //                 'schedule_aditor_id' => $aditorEmployeeId,
    //                 'showschedule_id'    => $showSchedule->id,
    //                 'row_ordernumber'    => json_encode($allRowNumbers),
    //                 'checklist_id'       => json_encode($checklistIds),
    //                 'parameter'          => json_encode($allParameters, JSON_UNESCAPED_SLASHES),
    //                 'submit_type'        => $request->submit_type,
    //                 'created_by'         => $aditorEmployeeId,
    //             ]);
    //         }
    //         return redirect()->route('manageshowschedule')->with('success', $request->submit_type == 1
    //             ? 'Data saved successfully'
    //             : 'Personal data saved successfully!');
    //     }
    // }
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $aditorEmployeeId = $user->employee_id;

        //     /*
        //     |--------------------------------------------------------------------------
        //     | ROLE 4 → Responded Remarks
        //     |--------------------------------------------------------------------------
        //     */
        if ($user->role_id == 4) {

            $rowOrderNumbers = $request->row_ordernumber ?? [];
            $respondedRemarksArray = [];

            foreach ($rowOrderNumbers as $rowOrd) {
                $remarkText = $request->responded_remarks[$rowOrd] ?? null;
                $remarkText = trim($remarkText ?? '');
                $respondedRemarksArray[] = $remarkText === '' ? null : $remarkText;
            }

            foreach ($rowOrderNumbers as $rowOrd) {
                $showId = $request->schedule_id[$rowOrd] ?? null;

                if ($showId) {
                    $showSchedule = ShowSchedule::find($showId);
                    if ($showSchedule) {

                        $showSchedule->responded_remarks     = json_encode($respondedRemarksArray);
                        $showSchedule->responded_submit_type = $request->responded_submit_type;
                        $showSchedule->save();

                        if ($request->responded_submit_type == 1) {
                            $schedule = Schedule::find($showSchedule->schedule_id);
                            ShowScheduleLog::create([
                                'schedule_id'           => $showSchedule->schedule_id,
                                'schedule_created'      => $schedule ? $schedule->created_by : null,
                                'showschedule_id'       => $showSchedule->id,
                                'row_ordernumber'       => $showSchedule->row_ordernumber,
                                'checklist_id'          => $showSchedule->checklist_id,
                                'parameter'             => $showSchedule->parameter,
                                'responded_submit_type' => $request->responded_submit_type,
                                'responded_remarks'     => json_encode($respondedRemarksArray),
                                'created_by'            => $aditorEmployeeId,
                                'asing_to'           => $aditorEmployeeId,
                            ]);
                        }
                    }
                }
            }
            return redirect()->route('manageshowschedule')->with(
                $request->responded_submit_type == 1 ? 'success' : 'primary',
                $request->responded_submit_type == 1
                    ? 'Responded remarks updated & log created successfully'
                    : 'Personal remarks updated successfully!'
            );
        }

        //     /*
        //     |--------------------------------------------------------------------------
        //     | ROLE 3 → auditor Remarks
        //     |--------------------------------------------------------------------------
        //     */
        $schedule = Schedule::where('id', $id)->where('status', 1)->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Schedule not found.');
        }
        $rowOrderNumbers = $request->row_ordernumber ?? [];

        if ($user->role_id == 3) {
            $allRowNumbers = $rowOrderNumbers;
            $checklistIds  = is_array($schedule->checklist_id)
                ? $schedule->checklist_id
                : json_decode($schedule->checklist_id ?? '[]', true);
            $allParameters = [];

            foreach ($rowOrderNumbers as $rowIndex => $rowOrd) {
                $rowParameters = $request->parameter[$rowIndex] ?? [];
                $rowTypes      = $request->select_items[$rowIndex] ?? [];

                foreach ($rowParameters as $colIndex => $value) {
                    $type = $rowTypes[$colIndex] ?? null;
                    if ($type == 3) {
                        $rowParameters[$colIndex] = "empty";
                    } elseif ($type == 2) {
                        $rowParameters[$colIndex] = $value === "" ? null : $value;
                    }
                }
                $allParameters[] = $rowParameters;
            }
            $showSchedule = ShowSchedule::updateOrCreate(
                [
                    'schedule_id'        => $schedule->id,
                    'schedule_aditor_id' => $aditorEmployeeId,
                ],
                [
                    'row_ordernumber' => json_encode($allRowNumbers),
                    'checklist_id'    => json_encode($checklistIds),
                    'parameter'       => json_encode($allParameters, JSON_UNESCAPED_SLASHES),
                    'submit_type'     => $request->submit_type,
                    'created_by'      => $aditorEmployeeId,
                ]
            );
            if ($request->submit_type == 1) {
                ShowScheduleLog::create([
                    'schedule_id'        => $schedule->id,
                    'schedule_created'   => $schedule->created_by,
                    'schedule_aditor_id' => $aditorEmployeeId,
                    'showschedule_id'    => $showSchedule->id,
                    'row_ordernumber'    => json_encode($allRowNumbers),
                    'checklist_id'       => json_encode($checklistIds),
                    'parameter'          => json_encode($allParameters, JSON_UNESCAPED_SLASHES),
                    'submit_type'        => $request->submit_type,
                    'created_by'         => $aditorEmployeeId,
                ]);
            }
            return redirect()->route('manageshowschedule')->with('success', $request->submit_type == 1
                ? 'Data saved successfully'
                : 'Personal data saved successfully!');
        }
    }

    /**
     * Show the form for changeSubmitType a new resource.
     */
    public function changeSubmitType($id)
    {
        $user = Auth::user();

        if ($user->role_id != 2) {
            return back()->with('error', 'Permission denied.');
        }

        $schedule = ShowSchedule::where('schedule_id', $id)->first();

        if (!$schedule) {
            return back()->with('error', 'Schedule not found.');
        }

        if (!is_null($schedule->asing_to) && !is_null($schedule->asing_by)) {

            $schedule->responded_submit_type = 2;
            $schedule->save();

        }
        else {

            ShowSchedule::updateOrCreate(
                ['schedule_id' => $id],
                ['submit_type' => 2]
            );
        }

        return back()->with('success', 'Unlocked the checklist successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $type = $request->type ?? 1;
        $itemheaders = Itemheader::where('status', 1)->orderBy('order_number', 'asc')->get();
        $type2HeaderIds = Itemheader::whereBetween('id', [6, 8])->pluck('title', 'id')->toArray();
        $user = Auth::user();
        $respondedusers = User::where('role_id', 4)->get();
        $checklistitems     = collect();
        $showSubmitButtons  = true;
        $schedule = Schedule::findOrFail($id);
        $showschedules = ShowSchedule::where('schedule_id', $id)->get();
        // echo '<pre>';
        // print_r($showschedules->toArray());
        // exit;
        /* ===============================
        SAFE JSON DECODER
        =============================== */
        $safe = function ($value) {
            if (is_array($value)) return $value;
            if (is_null($value)) return [];
            return json_decode($value, true) ?: [];
        };
        /* =========================================================
        ROLE 1 & 2 (ADMIN / SUPER ADMIN / RESPONDED USER)
        ========================================================= */
        if (in_array($user->role_id, [1, 2, 4, 5])) {
            $checklistIds = $safe($schedule->checklist_id);
            $checklistitems = Checkitem::where(function ($query) use ($checklistIds) {
                foreach ($checklistIds as $cid) {
                    $query->orWhereJsonContains('checklist_id', (string) $cid);
                }
            })->get();
            $showSubmitButtons = false;
            $mergedParameters = [];
            foreach ($showschedules as $showSchedule) {
                foreach ($safe($showSchedule->parameter) as $r => $cols) {
                    foreach ($cols as $c => $v) {
                        $mergedParameters[$r][$c] = $v;
                    }
                }
            }
            $checklistitems->transform(function ($item, $rowIndex) use ($safe, $mergedParameters) {
                $item->checklist_ids       = $safe($item->checklist_id);
                $item->checklist_titles   = Checklist::whereIn('id', $item->checklist_ids)->pluck('title')->toArray();
                $item->header_array       = $safe($item->header_id);
                $item->select_items_array = $safe($item->select_items);
                $originalParams = $safe($item->parameter);
                $params = [];
                foreach ($item->select_items_array as $col => $type) {
                    if (isset($mergedParameters[$rowIndex][$col])) {
                        $params[$col] = $mergedParameters[$rowIndex][$col];
                    } else {
                        $params[$col] = $type == 2 ? 'no' : ($originalParams[$col] ?? '');
                    }
                }
                $item->parameter_array = $params;
                return $item;
            });
        }
        /* =========================================================
        ROLE 3 (AUDITOR)
        ========================================================= */
        elseif ($user->role_id == 3) {
            $schedule = Schedule::where('id', $id)->where('aditor_id', $user->employee_id)->where('status', 1)->first();
            $checklistitems = collect();
            $allShowSchedules = collect();

            if ($schedule) {
                $checklistIds = is_array($schedule->checklist_id) ? $schedule->checklist_id : json_decode($schedule->checklist_id ?? '[]', true);
                if (!empty($checklistIds)) {
                    $checklistitems = Checkitem::where(function ($query) use ($checklistIds) {
                        foreach ($checklistIds as $cid) {
                            $query->orWhereJsonContains('checklist_id', (string)$cid);
                        }
                    })->get();
                }

                $allShowSchedules = ShowSchedule::where('schedule_id', $schedule->id)->where('created_by', $user->employee_id)->get();

                $checklistitems->transform(function ($item, $rowIndex) use ($allShowSchedules) {
                    $item->checklist_ids       = is_array($item->checklist_id) ? $item->checklist_id : json_decode($item->checklist_id ?? '[]', true);
                    $item->checklist_titles    = Checklist::whereIn('id', $item->checklist_ids)->pluck('title')->toArray();
                    $item->status_array        = is_array($item->status) ? $item->status : json_decode($item->status ?? '[]', true);
                    $item->header_array        = is_array($item->header_id) ? $item->header_id : json_decode($item->header_id ?? '[]', true);
                    $item->select_items_array  = is_array($item->select_items) ? $item->select_items : json_decode($item->select_items ?? '[]', true);
                    $originalParams            = is_array($item->parameter) ? $item->parameter : json_decode($item->parameter ?? '[]', true);

                    $params = [];
                    foreach ($item->select_items_array as $colIndex => $type) {
                        $savedValue = null;

                        foreach ($allShowSchedules as $saved) {
                            $savedParams = is_array($saved->parameter) ? $saved->parameter : json_decode($saved->parameter ?? '[]', true);
                            if (isset($savedParams[$rowIndex][$colIndex])) {
                                $savedValue = $savedParams[$rowIndex][$colIndex];
                                break;
                            }
                        }

                        if ($type == 1) {
                            $params[$colIndex] = $savedValue ?? '';
                        } elseif ($type == 2) {
                            $params[$colIndex] = $savedValue ?? 'no';
                        } else {
                            $params[$colIndex] = $savedValue ?? ($originalParams[$colIndex] ?? '');
                        }
                    }
                    $item->parameter_array = $params;
                    return $item;
                });
                $checklistitems = $checklistitems->filter(function ($item) {
                    $statuses = $item->status_array ?? [];
                    return is_array($statuses) && in_array("1", $statuses);
                });
            }
            $showSubmitButtons = true;
            $savedSchedule = $allShowSchedules->sortByDesc('id')->first();
            if ($savedSchedule && $savedSchedule->submit_type == 1) {
                $showSubmitButtons = false;
            }
        }
        return view('showschedule.view', compact(
            'itemheaders', 'showschedules', 'schedule', 'checklistitems', 'type', 'showSubmitButtons', 'respondedusers', 'type2HeaderIds'
        ));
    }

    /**
     * AsingChecklist the specified resource in storage.
     */
    public function AsingChecklist(Request $request, $id)
    {
        $showSchedule = ShowSchedule::findOrFail($id);
        $request->validate(
            [
                'asing_to'        => 'required',
                'asingby_remarks' => 'required|string',
            ],
            [
                'asing_to.required'        => 'Responded is required',
                'asingby_remarks.required' => 'Remarks is required',
            ]
        );
        /*
        |--------------------------------------------------------------------------
        | Update Original ShowSchedule
        |--------------------------------------------------------------------------
        */
        $showSchedule->update([
            'asing_to'           => $request->asing_to,
            'asing_by'           => Auth::user()->employee_id,
            'asingby_remarks'    => $request->asingby_remarks,
            'schedule_status'    => 1,
            'compliant'          => $request->has('compliant') ? 1 : 0,
            'partial_compliant'  => $request->has('partial_compliant') ? 1 : 0,
            'non_compliant'      => $request->has('non_compliant') ? 1 : 0,
        ]);
        /*
        |--------------------------------------------------------------------------
        | Fetch Schedule to get created_by for log
        |--------------------------------------------------------------------------
        */
        $schedule = Schedule::find($showSchedule->schedule_id);
        /*
        |--------------------------------------------------------------------------
        | CREATE NEW LOG RECORD (HISTORY)
        |--------------------------------------------------------------------------
        */
        ShowScheduleLog::create([
            'schedule_id'        => $showSchedule->schedule_id,
            'schedule_created'   => $schedule ? $schedule->created_by : null,
            // 'schedule_aditor_id' => $showSchedule->schedule_aditor_id,
            'showschedule_id'    => $showSchedule->id,
            'row_ordernumber'    => $showSchedule->row_ordernumber,
            'checklist_id'       => $showSchedule->checklist_id,
            'parameter'          => $showSchedule->parameter,
            'submit_type'        => $showSchedule->submit_type,
            'asing_by'           => Auth::user()->employee_id,
            'asingby_remarks'    => $request->asingby_remarks,
            'compliant'          => $request->has('compliant') ? 1 : 0,
            'partial_compliant'  => $request->has('partial_compliant') ? 1 : 0,
            'non_compliant'      => $request->has('non_compliant') ? 1 : 0,
            'asing_to'           => $request->asing_to,
            'schedule_status'    => 1,
            'created_by'         => Auth::user()->employee_id,
        ]);
        return redirect()->route('manageshowschedule')->with('success', 'Data Updated & Logged Successfully');
    }


}
