@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">Edit Schedule</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1 text-end">
                <li class="breadcrumb-item text-muted">
                    <a href="#" class="text-muted text-hover-primary">Dashboard</a>
                </li>
                <li class="breadcrumb-item"><i class="bi bi-chevron-right text-gray-500 fs-8"></i></li>
                <li class="breadcrumb-item text-muted">Schedule</li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0"><h3 class="fw-bold m-0">Edit Schedule</h3></div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form action="{{ route('updateschedule') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $schedules->id }}">
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Title</label>
                                <input id="title" type="text" class="form-control form-control-sm" name="title" value="{{ old('title', $schedules->title) }}"
                                    placeholder="Enter Schedule Title" oninvalid="this.setCustomValidity('Please Enter Schedule title')" oninput="this.setCustomValidity('')" required/>
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Start Date</label>
                                <div class="position-relative d-flex align-items-center">
                                    <i class="ki-duotone ki-calendar-8 position-absolute ms-4 mb-1 fs-2"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                    <input name="start_date" class="form-control form-control-sm ps-12"
                                        value="{{ old('start_date', $schedules->start_date) }}" placeholder="Pick a start date" id="kt_datepicker_1"/>
                                </div>
                                <span class="text-danger">{{ $errors->first('start_date') }}</span>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Campus</label>
                                <select name="campus_id" id="campus_id" data-control="select2" class="form-select form-select-sm fw-semibold">
                                    <option selected disabled>- Select Campus -</option>
                                    @foreach ($campusDepartments as $campusDepartment)
                                        <option value="{{ $campusDepartment->campus_id }}"
                                            {{ old('campus_id', $schedules->campus_id) == $campusDepartment->campus_id ? 'selected' : '' }}>
                                            {{ $campusDepartment->campus->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('campus_id') }}</span>
                            </div>
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Department</label>
                                <select name="department_id[]" id="department_id" data-control="select2" multiple class="form-control form-control-sm mb-3 mb-lg-0"
                                        data-selected='@json(old("department_id", $selectedDepartments ?? []))'>
                                    <!-- Options populated dynamically -->
                                </select>
                                <span class="text-danger">{{ $errors->first('department_id') }}</span>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label fw-semibold fs-6">Program Name</label>
                                <select name="program_id[]" id="program_id" data-control="select2" multiple class="form-control form-control-sm mb-3 mb-lg-0"
                                        data-selected='@json(old("program_id", $selectedPrograms ?? []))'>
                                    <!-- Options populated dynamically -->
                                </select>
                                <span class="text-danger">{{ $errors->first('program_id') }}</span>
                            </div>
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Audit</label>
                                <select name="audit_id" data-control="select2" class="form-select form-select-sm">
                                    <option selected disabled>- Select Audit -</option>
                                    @foreach ($audits as $audit)
                                        <option value="{{ $audit->id }}" {{ old('audit_id', $schedules->audit_id) == $audit->id ? 'selected' : '' }}>
                                            {{ $audit->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('audit_id') }}</span>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Check List</label>
                                <select name="checklist_id[]" data-control="select2" class="form-control form-control-sm mb-3 mb-lg-0" multiple>
                                    @foreach ($checklists as $checklist)
                                        <option value="{{ $checklist->id }}"
                                            {{ in_array($checklist->id, old('checklist_id', $schedules->checklist_id ? json_decode($schedules->checklist_id, true) : [])) ? 'selected' : '' }}>
                                            {{ $checklist->title }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('checklist_id') }}</span>
                            </div>
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Aditor</label>
                                <select name="aditor_id" data-control="select2" class="form-select form-select-sm fw-semibold">
                                    <option selected disabled>- Select Aditor -</option>
                                    @foreach ($aditorusers as $aditoruser)
                                        <option value="{{ $aditoruser->id }}" {{ old('aditor_id', $schedules->aditor_id) == $aditoruser->employee_id ? 'selected' : '' }}>
                                            {{ $aditoruser->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">{{ $errors->first('aditor_id') }}</span>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-6 fv-row">
                                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                <select name="status" data-control="select2" class="form-select form-select-sm fw-semibold">
                                    <option selected disabled>- Select Status -</option>
                                    <option value="1" {{ old('status', $schedules->status) == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('status', $schedules->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                </select>
                                <span class="text-danger">{{ $errors->first('status') }}</span>
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

    function populatePrograms(departmentIDs = [], selectedPrograms = []) {
        let programSelect = $('#program_id');
        programSelect.empty(); // Clear previous options

        if (departmentIDs.length === 0) {
            programSelect.append('<option disabled>Select Department First</option>');
            programSelect.trigger('change');
            return;
        }

        $.ajax({
            url: "{{ route('getcampusdetails', ':id') }}".replace(':id', $('#campus_id').val()),
            type: "GET",
            success: function(data) {
                // Filter programs based on selected departments
                let programs = data.programs.filter(p => departmentIDs.includes(p.department_id.toString()));

                // Remove duplicates by program ID
                let uniquePrograms = [];
                let seen = new Set();
                programs.forEach(p => {
                    if (!seen.has(p.id)) {
                        // seen.add(p.id);
                        uniquePrograms.push(p);
                    }
                });

                if (uniquePrograms.length > 0) {
                    uniquePrograms.forEach(p => {
                        let selected = selectedPrograms.includes(p.id.toString()) ? 'selected' : '';
                        programSelect.append(`<option value="${p.id}" ${selected}>${p.name}</option>`);
                    });
                } else {
                    programSelect.append('<option disabled>No Programs Found</option>');
                }

                programSelect.trigger('change');
            }
        });
    }

    function populateDepartments(data, selectedDepartments = [], selectedPrograms = []) {
        let deptSelect = $('#department_id');
        deptSelect.empty();

        if (data.departments.length > 0) {
            data.departments.forEach(d => {
                let selected = selectedDepartments.includes(d.id.toString()) ? 'selected' : '';
                deptSelect.append(`<option value="${d.id}" ${selected}>${d.name}</option>`);
            });
        } else {
            deptSelect.append('<option disabled>No Departments Found</option>');
        }

        // deptSelect.trigger('change');

        // Populate programs only for selected departments
        populatePrograms(selectedDepartments, selectedPrograms);
    }

    // ------------------- Initial Load -------------------
    let initialCampus = $('#campus_id').val();
    let initialDepartments = JSON.parse($('#department_id').attr('data-selected') || '[]').map(String);
    let initialPrograms = JSON.parse($('#program_id').attr('data-selected') || '[]').map(String);

    if (initialCampus) {
        $.ajax({
            url: "{{ route('getcampusdetails', ':id') }}".replace(':id', initialCampus),
            type: "GET",
            success: function(data) {
                populateDepartments(data, initialDepartments, initialPrograms);
            }
        });
    }

    // ------------------- On Campus Change -------------------
    $('#campus_id').on('change', function() {
        let campusID = $(this).val();
        $('#department_id').empty().append('<option disabled>Loading Departments...</option>');
        $('#program_id').empty().append('<option disabled>Select Department First</option>');

        $.ajax({
            url: "{{ route('getcampusdetails', ':id') }}".replace(':id', campusID),
            type: "GET",
            success: function(data) {
                populateDepartments(data, [], []);
            }
        });
    });

    // ------------------- On Department Change -------------------
    $('#department_id').on('change', function() {
        let deptIDs = $(this).val() || [];
        populatePrograms(deptIDs, []); // reset programs for newly selected departments
    });

});





</script>
@endsection


{{-- @section('script') --}}
{{-- <script>
    $(document).ready(function () {
        let allPrograms = [];

        function populateDepartments(data, selectedDepartments = []) {
            let deptSelect = $('#department_id');
            let programSelect = $('#program_id');

            allPrograms = data.programs || [];

            deptSelect.empty();
            programSelect.empty();
            deptSelect.append('<option disabled>Loading Departments...</option>');
            programSelect.append('<option disabled>Select Department First</option>');

            if (data.departments.length > 0) {
                deptSelect.empty();
                data.departments.forEach(d => {
                    let selected = selectedDepartments.includes(d.id) ? 'selected' : '';
                    deptSelect.append(`<option value="${d.id}" ${selected}>${d.name}</option>`);
                });
            } else {
                deptSelect.empty().append('<option disabled>No Departments Found</option>');
            }

            let selectedDeptIDs = deptSelect.val() || [];
            updatePrograms(selectedDeptIDs);
        }

        function updatePrograms(selectedDeptIDs = []) {
            let programSelect = $('#program_id');
            let savedPrograms = JSON.parse(programSelect.attr('data-selected') || '[]');

            programSelect.empty();

            if (allPrograms.length > 0) {
                let filteredPrograms = allPrograms.filter(p => selectedDeptIDs.includes(p.department_id));

                if (filteredPrograms.length > 0) {
                    filteredPrograms.forEach(p => {
                        programSelect.append(`<option value="${p.id}">${p.name}</option>`);
                    });

                    let validSelectedPrograms = filteredPrograms
                        .map(p => p.id)
                        .filter(id => savedPrograms.includes(id));

                    programSelect.val(validSelectedPrograms).trigger('change');
                } else {
                    programSelect.append('<option disabled>No Programs Found</option>');
                    programSelect.val([]).trigger('change');
                }
            } else {
                programSelect.append('<option disabled>No Programs Found</option>');
                programSelect.val([]).trigger('change');
            }
        }

        let initialCampus = $('#campus_id').val();
        if (initialCampus) {
            $.ajax({
                url: "{{ route('getcampusdetails', ':id') }}".replace(':id', initialCampus),
                type: "GET",
                success: function (data) {
                    let selectedDepartments = JSON.parse($('#department_id').attr('data-selected') || '[]');
                    populateDepartments(data, selectedDepartments);
                }
            });
        }

        $('#campus_id').on('change', function () {
            let campusID = $(this).val();
            $('#department_id').empty().append('<option disabled>Loading Departments...</option>');
            $('#program_id').empty().append('<option disabled>Select Department First</option>');

            $.ajax({
                url: "{{ route('getcampusdetails', ':id') }}".replace(':id', campusID),
                type: "GET",
                success: function (data) {
                    populateDepartments(data);
                }
            });
        });

    $('#department_id').on('change', function () {
        let selectedDeptIDs = $(this).val() || [];

        let programSelect = $('#program_id');
        programSelect.empty().append('<option disabled>Loading Programs...</option>');

        if (selectedDeptIDs.length > 0) {
            $.ajax({
                url: "{{ route('getprogramsBydepartments') }}",
                type: "POST",
                data: {
                    department_ids: selectedDeptIDs,
                    _token: "{{ csrf_token() }}"
                },
                success: function (programs) {
                    programSelect.empty();
                    if (programs.length > 0) {
                        programs.forEach(p => {
                            programSelect.append(`<option value="${p.id}">${p.name}</option>`);
                        });
                        // Preselect previously saved programs
                        let savedPrograms = JSON.parse(programSelect.attr('data-selected') || '[]');
                        programSelect.val(savedPrograms).trigger('change');
                    } else {
                        programSelect.append('<option disabled>No Programs Found</option>');
                    }
                }
            });
        } else {
            programSelect.empty().append('<option disabled>Select Department First</option>');
        }
    });

    });
</script> --}}


{{-- @endsection --}}
