@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Create Schedule
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1 text-end">
                <li class="breadcrumb-item text-muted">
                    <a href="index.html" class="text-muted text-hover-primary">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-chevron-right text-gray-500 fs-8"></i>
                </li>
                <li class="breadcrumb-item text-muted">
                    Schedule
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Create Schedule</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form action="{{route('storeschedule')}}" method="POST">
                    @csrf
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Title</label>
                                        <input id="title" type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="title" value="{{ old('title') }}"
                                        placeholder="Enter Schedule Title" oninvalid="this.setCustomValidity('Please Enter Schedule title')" oninput="this.setCustomValidity('')" required/>
                                        <span class="text-danger">{{$errors->first('title')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Start Date</label>
                                           <div class="position-relative d-flex align-items-center">
                                                <i class="ki-duotone ki-calendar-8 position-absolute ms-4 mb-1 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                                <input name="start_date" class="form-control form-control-sm ps-12 @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}" placeholder="Pick a start date" id="kt_datepicker_1" required/>
                                            </div>
                                        <span class="text-danger">{{$errors->first('start_date')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Campus</label>
                                        <select name="campus_id" data-control="select2" class="form-select form-select-sm fw-semibold @error('campus_id') is-invalid @enderror">
                                            <option selected disabled>- Select Campus -</option>
                                            @foreach ($campusDepartments as $campusDepartment)
                                                <option value="{{ $campusDepartment->campus_id }}"
                                                    {{ old('campus_id', $schedules->campus_id ?? '') == $campusDepartment->campus_id ? 'selected' : '' }}>
                                                    {{ $campusDepartment->campus->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{$errors->first('campus_id')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Department</label>
                                        <select name="department_id[]" id="department_id" data-control="select2" multiple class="form-control form-control-sm mb-3 mb-lg-0 @error('department_id') is-invalid @enderror"
                                            data-selected="{{ old('department_id') ? json_encode(old('department_id')) : '[]' }}">
                                            <!-- Options populated dynamically -->
                                        </select>
                                        <span class="text-danger">{{$errors->first('department_id')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label fw-semibold fs-6">Program Name</label>
                                        <select name="program_id[]" id="program_id" data-control="select2" multiple class="form-control form-control-sm mb-3 mb-lg-0"
                                            data-selected="{{ old('program_id') ? json_encode(old('program_id')) : '[]' }}">
                                        </select>
                                        <span class="text-danger">{{ $errors->first('program_id') }}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Audit</label>
                                        <select name="audit_id" id="audit_id" data-control="select2"
                                                class="form-select form-select-sm fw-semibold @error('audit_id') is-invalid @enderror">
                                            <option selected disabled>- Select Audit -</option>
                                            @foreach ($audits as $audit)
                                                <option value="{{ $audit->id }}">{{ $audit->title }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('audit_id') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Check List</label>
                                          <select name="checklist_id[]" data-control="select2" class="form-control form-control-sm mb-3 mb-lg-0 @error('checklist_id') is-invalid @enderror" multiple value="{{ old('checklist_id') }}">
                                            @foreach ($checklists as $checklist)
                                                <option value="{{ $checklist->id }}"
                                                    {{ (collect(old('checklist_id', $schedules->checklist_id ?? []))->contains($checklist->id)) ? 'selected' : '' }}>
                                                    {{ $checklist->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('checklist_id') }}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Aditor</label>
                                        <select name="aditor_id" data-control="select2" class="form-select form-select-sm fw-semibold @error('aditor_id') is-invalid @enderror" value="{{ old('aditor_id') }}">
                                            <option selected disabled>- Select Aditor -</option>
                                            @foreach ($aditorusers as $aditoruser)
                                                <option value="{{ $aditoruser->id }}"
                                                    {{ old('aditor_id', $schedules->aditor_id ?? '') == $aditoruser->id ? 'selected' : '' }}>
                                                    {{ $aditoruser->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{$errors->first('aditor_id')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                        <select name="status" data-control="select2" class="form-select form-select-sm fw-semibold @error('status') is-invalid @enderror" value="{{ old('status') }}">
                                            <option disabled selected>- Select Status -</option>
                                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <span class="text-danger">{{$errors->first('status')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-8 fv-row"></div>
                                    <div class="col-lg-4 d-flex justify-content-end fv-row">
                                        <button type="submit" class="btn btn-primary m-1"><i class="ki-duotone ki-check"></i>Submit</button>
                                        <button type="button" class="btn btn-danger m-1" onclick="window.location='{{ route('manageschedule') }}'"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i>Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {

        let allPrograms = [];

        function populateDepartments(data) {
            let deptSelect = $('#department_id');
            let programSelect = $('#program_id');

            allPrograms = data.programs;

            deptSelect.empty();
            programSelect.empty();

            deptSelect.append('<option disabled>Loading Departments...</option>');
            programSelect.append('<option disabled>Select Department First</option>');

            setTimeout(() => {
                deptSelect.empty();
                if (data.departments.length > 0) {
                    data.departments.forEach(d => {
                        deptSelect.append(`<option value="${d.id}">${d.name}</option>`);
                    });
                } else {
                    deptSelect.append('<option disabled>No Department Found</option>');
                }
            }, 300);
        }

        function populatePrograms(selectedDepartments) {
            let programSelect = $('#program_id');
            programSelect.empty();
            programSelect.append('<option disabled>Loading Programs...</option>');

            setTimeout(() => {
                let filteredPrograms = allPrograms.filter(p =>
                    selectedDepartments.includes(p.department_id.toString())
                );

                programSelect.empty();
                if (filteredPrograms.length > 0) {
                    filteredPrograms.forEach(p => {
                        programSelect.append(`<option value="${p.id}">${p.name}</option>`);
                    });
                } else {
                    programSelect.append('<option disabled>No Programs Found</option>');
                }
            }, 300);
        }

        $('select[name="campus_id"]').on('change', function () {
            let campusID = $(this).val();

            $('#department_id').empty().append('<option disabled>Loading Departments...</option>');
            $('#program_id').empty().append('<option disabled>Select Department First</option>');

            $.ajax({
                url: "{{ route('getcampusdetails', ':id') }}".replace(':id', campusID),
                type: "GET",
                success: function (data) {
                    populateDepartments(data);
                },
                error: function () {
                    $('#department_id').empty().append('<option disabled>Error Loading Departments</option>');
                    $('#program_id').empty().append('<option disabled>Error Loading Programs</option>');
                }
            });
        });

        $('#department_id').on('change', function () {
            let selectedDepartments = $(this).val();
            populatePrograms(selectedDepartments);
        });

    });
</script>
@endsection

