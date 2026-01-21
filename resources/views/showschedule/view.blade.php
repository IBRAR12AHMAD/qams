@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">Show Audit Checklist</h1>
        </div>
    </div>
</div>
<div id="kt_account_settings_profile_details" class="collapse show">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card mb-5 mb-xl-12">
            <div class="card-header justify-content-center" style="background-color: #f2f2f2;">
                <h3 class="card-title fw-bold m-0 fs-2">Riphah Academic Audit Checklist</h3>
            </div>
            <div class="card-body pt-2">
                <form id="showScheduleForm" action="{{ route('storeshowschedule', $schedule->id) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr style="background-color: #e3f2fd;">
                                    <td colspan="{{ count($itemheaders) + (Auth::user()->role_id == 4 ? 1 : (in_array(Auth::user()->role_id, [1, 2, 5]) && !empty($showschedules) && ($showschedules[0]->responded_submit_type ?? 0) == 1 ? 1 : 0)) }}">
                                        <div class="row" style="margin-left: 5rem !important;">
                                            <div class="col-md-4 col-12">
                                                <h4>Department Name: {{ $departmentName ?? '' }}</h4>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <h4>Audit Date: {{ $auditDate ?? '' }}</h4>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    @foreach($itemheaders as $header)
                                        @php
                                            $headerId = $header->id;
                                            $headerName = strtolower(str_replace(' ', '_', $header->title));
                                        @endphp
                                        @if(Auth::user()->role_id != 4)
                                            <th class="text-wrap">{{ $header->title }}</th>
                                        @else
                                        @if(!in_array($headerId, array_keys($type2HeaderIds)))
                                                <th class="text-wrap">{{ $header->title }}</th>
                                            @else
                                                @php
                                                    $showColumn = false;
                                                    foreach($showschedules as $show) {
                                                        if(($show->{ $headerName } ?? 0) == 1) {
                                                            $showColumn = true;
                                                            break;
                                                        }
                                                    }
                                                @endphp
                                                @if($showColumn)
                                                    <th class="text-wrap">{{ $header->title }}</th>
                                                @endif
                                            @endif
                                        @endif
                                    @endforeach
                                    @if(Auth::user()->role_id == 4 || (in_array(Auth::user()->role_id, [1, 2, 5]) && ($showschedules[0]->responded_submit_type ?? 0) == 1))
                                        <th class="text-wrap">Responded Remarks</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checklistitems as $rowIndex => $item)
                                    @php $item->parameter_array = $item->parameter_array ?? []; @endphp
                                    <tr>
                                        <td>
                                            <input type="hidden" name="row_ordernumber[]" value="{{ $item->row_ordernumber }}">
                                            {{ $item->row_ordernumber }}
                                        </td>
                                        <td>
                                            <input type="hidden" name="checklist_id[]" value="{{ json_encode($item->checklist_ids) }}">
                                            {{ implode(', ', $item->checklist_titles) }}
                                        </td>
                                        @foreach($item->header_array as $colIndex => $headerId)
                                            @php
                                                $type = $item->select_items_array[$colIndex] ?? null;
                                                $headerTitle = App\Models\Itemheader::find($headerId)->title ?? '';
                                                $headerName = strtolower(str_replace(' ', '_', $headerTitle));
                                                $showColumn = true;
                                                if(Auth::user()->role_id == 4 && in_array($headerId, [6,7,8])) {
                                                    $showColumn = false;
                                                    foreach($showschedules as $show) {
                                                        if(($show->{ $headerName } ?? 0) == 1) {
                                                            $showColumn = true;
                                                            break;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            @if($showColumn)
                                                <td>
                                                    @php
                                                        $paramValue = $item->parameter_array[$colIndex] ?? '';
                                                    @endphp
                                                    @if($type === null)
                                                        <input type="hidden" name="parameter[{{ $rowIndex }}][{{ $colIndex }}]" value="{{ $paramValue }}">
                                                        {{ $paramValue }}

                                                    @elseif($type == 2)
                                                        @if(in_array(Auth::user()->role_id, [1,2,5]))
                                                            <div class="d-flex justify-content-center" style="margin-top: 30px;">
                                                                <span class="badge {{ $paramValue == 'yes' ? 'badge-success' : 'badge-danger' }}">
                                                                    {{ ucfirst($paramValue ?? 'no') }}
                                                                </span>
                                                                <input type="hidden" name="parameter[{{ $rowIndex }}][{{ $colIndex }}]" value="{{ $paramValue ?? 'no' }}">
                                                            </div>
                                                        @elseif(Auth::user()->role_id == 4)
                                                            <div class="d-flex justify-content-center">
                                                                <span class="badge badge-success">Yes</span>
                                                            </div>
                                                        @else
                                                        @php
                                                            $oldValue = old("parameter.$rowIndex.$colIndex", $paramValue);
                                                        @endphp
                                                        <div class="d-flex justify-content-center" style="margin-top: 30px;">
                                                            <input type="hidden" name="parameter[{{ $rowIndex }}][{{ $colIndex }}]" value="no">
                                                            <input type="radio" name="parameter[{{ $rowIndex }}][{{ $colIndex }}]" value="yes"
                                                                class="row-radio" data-row="{{ $rowIndex }}" {{ $oldValue === 'yes' ? 'checked' : '' }}>
                                                        </div>
                                                    @endif
                                                    @elseif($type == 3)
                                                        <input type="hidden" name="parameter[{{ $rowIndex }}][{{ $colIndex }}]" value="">
                                                    @endif
                                                    {{-- @if($type == 1)
                                                        <input type="hidden" name="select_items[{{ $rowIndex }}][{{ $colIndex }}]" value="{{ $type }}">
                                                        <textarea
                                                            class="form-control @error('parameter.'.$rowIndex.'.'.$colIndex) is-invalid @enderror"
                                                            name="parameter[{{ $rowIndex }}][{{ $colIndex }}]"
                                                            rows="2">{{ old('parameter.'.$rowIndex.'.'.$colIndex, $item->parameter_array[$colIndex] ?? '') }}</textarea>
                                                        @error('parameter.'.$rowIndex.'.'.$colIndex)
                                                            <div class="invalid-feedback d-block">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    @endif --}}
                                                    @if($type == 1)
                                                        <input type="hidden" name="select_items[{{ $rowIndex }}][{{ $colIndex }}]" value="{{ $type }}">
                                                        @php
                                                            $roleId = Auth::user()->role_id;
                                                            $isSubmitted = ($showschedules[0]->submit_type ?? 0) == 1;
                                                            $isReadonly = in_array($roleId, [1,2,4,5]) || ($roleId == 3 && $isSubmitted);
                                                        @endphp
                                                        <textarea class="form-control @error('parameter.'.$rowIndex.'.'.$colIndex) is-invalid @enderror"
                                                            name="parameter[{{ $rowIndex }}][{{ $colIndex }}]" rows="2"
                                                            {{ $isReadonly ? 'readonly' : '' }}>{{ old('parameter.'.$rowIndex.'.'.$colIndex, $item->parameter_array[$colIndex] ?? '') }}</textarea>
                                                        @error('parameter.'.$rowIndex.'.'.$colIndex)
                                                            <div class="invalid-feedback d-block">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    @endif
                                                </td>
                                            @endif
                                        @endforeach
                                        {{-- @if(Auth::user()->role_id == 4)
                                            <td>
                                                <input type="hidden" name="schedule_id[{{ $item->row_ordernumber }}]" value="{{ $showschedules[$rowIndex]->id ?? '' }}">
                                                @php
                                                    $firstRowIndex = 0;
                                                    $remarksArray = json_decode($showschedules[$firstRowIndex]->responded_remarks ?? '[]', true);
                                                    $value = $remarksArray[$rowIndex] ?? '';
                                                    $oldValue = old('responded_remarks.' . $item->row_ordernumber, $value);
                                                @endphp
                                                <textarea class="form-control @error('responded_remarks.' . $item->row_ordernumber) is-invalid @enderror" name="responded_remarks[{{ $item->row_ordernumber }}]" rows="2">{{ $oldValue }}</textarea>
                                                @error('responded_remarks.' . $item->row_ordernumber)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        @elseif(in_array(Auth::user()->role_id, [1,2]) && ($showschedules[0]->responded_submit_type ?? 0) == 1)
                                            <td>
                                                @php
                                                    $firstRowIndex = 0;
                                                    $remarksArray = json_decode($showschedules[$firstRowIndex]->responded_remarks ?? '[]', true);
                                                    $value = $remarksArray[$rowIndex] ?? '';
                                                    $oldValue = old('responded_remarks.' . $item->row_ordernumber, $value);
                                                @endphp
                                                <textarea class="form-control" name="responded_remarks[{{ $item->row_ordernumber }}]" rows="2" readonly>{{ $oldValue }}</textarea>
                                            </td>
                                        @endif --}}
                                        @if(Auth::user()->role_id == 4)
                                            <td>
                                                <input type="hidden" name="schedule_id[{{ $item->row_ordernumber }}]" value="{{ $showschedules[$rowIndex]->id ?? '' }}">
                                                @php
                                                    $firstRowIndex = 0;
                                                    $remarksArray = json_decode($showschedules[$firstRowIndex]->responded_remarks ?? '[]', true);
                                                    $value = $remarksArray[$rowIndex] ?? '';
                                                    $oldValue = old('responded_remarks.' . $item->row_ordernumber, $value);

                                                    $isReadonly = ($showschedules[0]->responded_submit_type ?? 0) == 1;
                                                @endphp
                                                <textarea class="form-control @error('responded_remarks.' . $item->row_ordernumber) is-invalid @enderror"
                                                    name="responded_remarks[{{ $item->row_ordernumber }}]" rows="2"
                                                    {{ $isReadonly ? 'readonly' : '' }}>{{ $oldValue }}</textarea>
                                                @error('responded_remarks.' . $item->row_ordernumber)
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        @elseif(in_array(Auth::user()->role_id, [1,2,5]) && ($showschedules[0]->responded_submit_type ?? 0) == 1)
                                            <td>
                                                @php
                                                    $firstRowIndex = 0;
                                                    $remarksArray = json_decode($showschedules[$firstRowIndex]->responded_remarks ?? '[]', true);
                                                    $value = $remarksArray[$rowIndex] ?? '';
                                                    $oldValue = old('responded_remarks.' . $item->row_ordernumber, $value);
                                                @endphp

                                                <textarea class="form-control" rows="2" readonly>{{ $oldValue }}</textarea>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(!in_array(Auth::user()->role_id, [1, 5]) && ($showSubmitButtons ?? true))
                        <div class="row mt-3">
                            <div class="col-lg-12 d-flex justify-content-end">
                                <button type="button" id="kt_docs_sweetalert_html" class="btn btn-sm btn-primary m-1">
                                    <i class="ki-duotone ki-check"></i> Submit
                                </button>
                                <button type="submit" value="2" name="submit_type" class="btn btn-sm btn-success m-1">
                                    <i class="ki-duotone ki-arrows-circle">
                                        <span class="path1"></span><span class="path2"></span>
                                    </i>Partial Submit
                                </button>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->role_id == 4)
                        <div class="row mt-3">
                            <div class="col-lg-12 d-flex justify-content-end">
                                @php
                                    $rst = $showschedules[0]->responded_submit_type ?? 0;
                                @endphp
                                @if(in_array($rst, [0, 2]))
                                    <button type="button" class="btn btn-sm btn-primary m-1 submit-btn"data-type="1">
                                        <i class="ki-duotone ki-check"></i> Submit
                                    </button>
                                    <button type="submit" name="responded_submit_type" value="2" class="btn btn-sm btn-success m-1">
                                         <i class="ki-duotone ki-arrows-circle"><span class="path1"></span><span class="path2"></span></i>Partial Submit
                                    </button>
                                @elseif($rst == 1)
                                    <div class="alert alert-info d-flex align-items-center">
                                        <i class="ki-duotone ki-information fs-2 me-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                        </i>
                                        Response has been submitted.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </form>
                @foreach($showschedules as $show)
                    <form action="{{ route('asingchecklist', ['id' => $show->id]) }}" method="POST">
                        @csrf
                        {{-- @if(Auth::user()->role_id == 2)
                            <div class="row mb-6">
                                <div class="col-lg-6 fv-row">
                                    <label class="col-form-label required fw-semibold fs-6">Responded User</label>
                                    <select name="asing_to" data-control="select2" class="form-select form-select-sm fw-semibold">
                                        <option disabled selected>- Select User -</option>
                                        @foreach ($respondedusers as $respondeduser)
                                            <option value="{{ $respondeduser->employee_id }}"
                                                {{ old('asing_to', $show->asing_to ?? '') == $respondeduser->employee_id ? 'selected' : '' }}>
                                                {{ $respondeduser->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('asing_to') }}</span>
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <label class="col-form-label fw-semibold fs-6">Select</label>
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach($type2HeaderIds as $headerId => $headerTitle)
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="{{ strtolower(str_replace(' ', '_', $headerTitle)) }}" value="1"
                                                    {{ old(strtolower(str_replace(' ', '_', $headerTitle)), $show->{strtolower(str_replace(' ', '_', $headerTitle))} ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $headerTitle }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif --}}
                        @if(Auth::user()->role_id == 2)
                            <div class="row mb-6 mt-4">
                                <div class="col-lg-6 fv-row">
                                    <label class="col-form-label required fw-semibold fs-6">Responded User</label>
                                    <select name="asing_to" data-control="select2" class="form-select form-select-sm fw-semibold @error('asing_to') is-invalid @enderror">
                                        <option disabled selected>- Select User -</option>
                                        @foreach ($respondedusers as $respondeduser)
                                            <option value="{{ $respondeduser->employee_id }}"
                                                {{ old('asing_to', $show->asing_to ?? '') == $respondeduser->employee_id ? 'selected' : '' }}>
                                                {{ $respondeduser->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('asing_to') }}</span>
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <label class="col-form-label fw-semibold fs-6">Select</label>
                                    <div class="d-flex gap-2 mt-2">
                                        @foreach($type2HeaderIds as $headerId => $headerTitle)
                                            @php
                                                $field = strtolower(str_replace(' ', '_', $headerTitle));
                                            @endphp
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input"
                                                    type="checkbox"
                                                    name="{{ $field }}"
                                                    value="1"
                                                    {{ old($field, $show->{$field} ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $headerTitle }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @elseif(in_array(Auth::user()->role_id, [1,5]) && ($show->schedule_status ?? 0) == 1)
                            <div class="row mb-6 mt-4">
                                <div class="col-lg-6 fv-row">
                                    <label class="col-form-label fw-semibold fs-6">Responded User</label>
                                    <input type="text" class="form-control form-control-sm"
                                        value="{{ optional($respondedusers->firstWhere('employee_id', $show->asing_to))->name }}" readonly>
                                </div>
                                <div class="col-lg-6 fv-row">
                                    <label class="col-form-label fw-semibold fs-6">Select</label>
                                    <div class="d-flex gap-3 mt-2">
                                        @foreach($type2HeaderIds as $headerId => $headerTitle)
                                            @php
                                                $field = strtolower(str_replace(' ', '_', $headerTitle));
                                            @endphp
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" disabled
                                                    {{ ($show->{$field} ?? 0) == 1 ? 'checked' : '' }}>
                                                <label class="form-check-label">{{ $headerTitle }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->role_id == 2)
                            <div class="row mb-6">
                                <div class="col-lg-12 fv-row">
                                    <label class="col-form-label required fw-semibold fs-6">Admin Remarks</label>
                                    <textarea class="form-control form-control-sm @error('asingby_remarks') is-invalid @enderror"
                                        name="asingby_remarks" rows="2">{{ old('asingby_remarks', $show->asingby_remarks ?? '') }}</textarea>
                                    <span class="text-danger">{{ $errors->first('asingby_remarks') }}</span>
                                </div>
                            </div>
                        @elseif(in_array(Auth::user()->role_id, [1,4,5]) && $show->schedule_status == 1)
                            <div class="row mb-6">
                                <div class="col-lg-12 fv-row">
                                    <label class="col-form-label fw-semibold fs-6">Admin Remarks</label>
                                    <textarea class="form-control form-control-sm" name="asingby_remarks" rows="2"
                                        readonly>{{ old('asingby_remarks', $show->asingby_remarks ?? '') }}</textarea>
                                    <span class="text-danger">{{ $errors->first('asingby_remarks') }}</span>
                                </div>
                            </div>
                        @endif
                        @if(Auth::user()->role_id == 2)
                            <div class="row mt-3">
                                <div class="col-lg-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-sm btn-primary m-1">
                                        <i class="ki-duotone ki-check"></i>Submit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger m-1" onclick="window.location='{{ route('manageshowschedule') }}'">
                                        <i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i>Cancel
                                    </button>
                                </div>
                            </div>
                        @endif
                    </form>
                @endforeach
                {{-- @php
                    $user = Auth::user();
                    $role = $user->role_id;
                    $showForRole1 = ($role == 1) && DB::table('show_schedule')->where('submit_type', 1)->exists();
                    $showForRole4 = ($role == 4) && DB::table('show_schedule')->where('responded_submit_type', 1)->exists();
                    $showForRole3 = false;
                    if($role == 3) {
                        $employeeIds = is_array($user->employee_id) ? $user->employee_id : [$user->employee_id];
                        $schedules = DB::table('schedules')
                            ->whereIn('aditor_id', $employeeIds)->where('status', 1)->pluck('id');
                        $showForRole3 = DB::table('show_schedule')
                            ->whereIn('schedule_id', $schedules->toArray())->where('submit_type', 1)->exists();
                    }
                @endphp
                @if($showForRole1 || $showForRole4 || $showForRole3)
                    <div class="row mt-3">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary m-1" onclick="window.location='{{ route('manageshowschedule') }}'">
                                <i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i> Back
                            </button>
                        </div>
                    </div>
                @endif --}}
                @php
                    $user = Auth::user();
                    $role = $user->role_id;
                    $showButton = false;
                    if (in_array($role, [1, 5])) {
                        $showButton = DB::table('show_schedule')
                            ->where('schedule_id', $schedule->id)
                            ->where('submit_type', 1)
                            ->exists();
                    }
                    if($role == 4) {
                        $showButton = DB::table('show_schedule')
                            ->where('schedule_id', $schedule->id)
                            ->where('responded_submit_type', 1)
                            ->exists();
                    }
                    if($role == 3) {
                        $employeeIds = is_array($user->employee_id) ? $user->employee_id : [$user->employee_id];
                        $schedules = DB::table('schedules')
                            ->whereIn('aditor_id', $employeeIds)
                            ->where('status', 1)
                            ->pluck('id');

                        if($schedules->contains($schedule->id)) {
                            $showButton = DB::table('show_schedule')
                                ->where('schedule_id', $schedule->id)
                                ->where('submit_type', 1)
                                ->exists();
                        }
                    }
                @endphp
                @if($showButton)
                    <div class="row mt-3">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-secondary m-1" onclick="window.location='{{ route('manageshowschedule') }}'">
                                <i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i> Back
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.getElementById('kt_docs_sweetalert_html');
        const form = submitBtn.closest('form');

        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            Swal.fire({
                html: `
                    <h4>Confirm Submission</h4>
                    <p>Are you sure you want to <strong>submit</strong> this audit checklist?</p>
                `,
                icon: "info",
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: 'No',
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-primary me-2",
                    cancelButton: 'btn btn-danger'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'submit_type';
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);

                    Swal.fire({
                        html: `<h4>Success!</h4><p>Data saved successfully.</p>`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: "btn btn-success"
                        }
                    }).then(() => {
                        form.submit();
                    });
                }
            });
        });
    });
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const submitBtn = document.getElementById('kt_docs_sweetalert_html');
        const form = submitBtn.closest('form');

        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            let allFilled = true;
            let firstEmptyField = null;

            form.querySelectorAll('.invalid-feedback.js-feedback').forEach(msg => msg.remove());

            form.querySelectorAll('textarea[name^="parameter"]').forEach(textarea => {
                if (!textarea.readOnly && !textarea.value.trim()) {
                    allFilled = false;
                    textarea.classList.add('is-invalid');

                    if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList.contains('js-feedback')) {
                        let feedback = document.createElement('div');
                        feedback.classList.add('invalid-feedback', 'd-block', 'js-feedback');
                        feedback.textContent = 'This field is required';
                        textarea.parentNode.insertBefore(feedback, textarea.nextSibling);
                    }

                    if (!firstEmptyField) firstEmptyField = textarea;
                } else {
                    textarea.classList.remove('is-invalid');
                }
            });

            if (!allFilled) {
                if (firstEmptyField) firstEmptyField.scrollIntoView({ behavior: 'smooth', block: 'center' });

                Swal.fire({
                    html: `<h4>Warning!</h4><p>Please fill all required fields before submitting.</p>`,
                    icon: 'error',
                    confirmButtonText: "OK",
                    buttonsStyling: false,
                    customClass: { confirmButton: "btn btn-danger" }
                });
                return;
            }

            Swal.fire({
                html: `<h4>Confirm Submission</h4><p>Are you sure you want to <strong>submit</strong> this audit checklist?</p>`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                buttonsStyling: false,
                customClass: { confirmButton: "btn btn-primary me-2", cancelButton: 'btn btn-danger' }
            }).then((result) => {
                if (result.isConfirmed) {
                    let hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'submit_type';
                    hiddenInput.value = '1';
                    form.appendChild(hiddenInput);

                    Swal.fire({
                        html: `<h4>Success!</h4><p>Data will be submitted successfully.</p>`,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: { confirmButton: "btn btn-success" }
                    }).then(() => {
                        form.submit();
                    });
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.submit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = btn.closest('form');
                const submitType = btn.getAttribute('data-type');

                let allFilled = true;
                let firstEmptyField = null;

                form.querySelectorAll('.invalid-feedback.js-feedback').forEach(msg => msg.remove());

                form.querySelectorAll('textarea[name^="responded_remarks"]').forEach(textarea => {
                    if (!textarea.readOnly && !textarea.value.trim()) {
                        allFilled = false;
                        textarea.classList.add('is-invalid');

                        if (!textarea.nextElementSibling || !textarea.nextElementSibling.classList.contains('js-feedback')) {
                            let feedback = document.createElement('div');
                            feedback.classList.add('invalid-feedback', 'd-block', 'js-feedback');
                            feedback.textContent = 'This field is required';
                            textarea.parentNode.insertBefore(feedback, textarea.nextSibling);
                        }

                        if (!firstEmptyField) firstEmptyField = textarea;
                    } else {
                        textarea.classList.remove('is-invalid');
                    }
                });

                if (!allFilled) {
                    if (firstEmptyField) firstEmptyField.scrollIntoView({ behavior: 'smooth', block: 'center' });

                    Swal.fire({
                        html: `<h4>Warning!</h4><p>Please fill all required fields before submitting.</p>`,
                        icon: 'error',
                        confirmButtonText: 'OK',
                        buttonsStyling: false,
                        customClass: { confirmButton: "btn btn-danger" }
                    });
                    return;
                }
                if(submitType == '1'){
                    Swal.fire({
                        html: `
                            <h4>Confirm Submission</h4>
                            <p>Are you sure you want to <strong>Submit</strong> this responded remarks?</p>
                        `,
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: "btn btn-primary me-2",
                            cancelButton: 'btn btn-danger'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'responded_submit_type';
                            hiddenInput.value = submitType;
                            form.appendChild(hiddenInput);

                            Swal.fire({
                                html: `<h4>Success!</h4><p>Data saved successfully.</p>`,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                customClass: { confirmButton: "btn btn-success" }
                            }).then(() => {
                                form.submit();
                            });
                        }
                    });
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('.row-radio');
        radios.forEach(radio => {
            radio.addEventListener('click', function() {
                const row = this.dataset.row;
                radios.forEach(r => {
                    if (r.dataset.row === row && r !== this) r.checked = false;
                });
            });
        });
    });
</script>
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.submit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = btn.closest('form');
                const submitType = btn.getAttribute('data-type');

                if(submitType == '1'){
                    Swal.fire({
                        html: `
                            <h4>Confirm Submission</h4>
                            <p>Are you sure you want to <strong>Submit</strong> this responded remarks?</p>
                        `,
                        icon: "info",
                        showCancelButton: true,
                        confirmButtonText: "Yes",
                        cancelButtonText: "No",
                        buttonsStyling: false,
                        customClass: {
                            confirmButton: "btn btn-primary me-2",
                            cancelButton: 'btn btn-danger'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'responded_submit_type';
                            hiddenInput.value = submitType;
                            form.appendChild(hiddenInput);

                            Swal.fire({
                                html: `<h4>Success!</h4><p>Data saved successfully.</p>`,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                buttonsStyling: false,
                                customClass: {
                                    confirmButton: "btn btn-success"
                                }
                            }).then(() => {
                                form.submit();
                            });
                        }
                    });
                }
            });
        });
    });
</script> --}}
@endsection
