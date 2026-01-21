@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Edit User
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
                    Register
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
                    <h3 class="fw-bold m-0">Edit User</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" class="form" action="{{ route('updateuser') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Full Name</label>
                                        <input id="name" type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="name" placeholder="Enter Full Name"
                                        oninvalid="this.setCustomValidity('Please Enter Full Name')" oninput="this.setCustomValidity('')" value="{{ $user->name }}" required/>
                                        <span class="text-danger">{{$errors->first('name')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Employee ID</label>
                                        <input id="employee_id" type="text" name="employee_id" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="Enter employee id" value="{{$user->employee_id}}" />
                                        <span class="text-danger">{{$errors->first('employee_id')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Designation</label>
                                        <input id="designation" type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="designation" placeholder="Enter designation" value="{{$user->designation}}" />
                                        <span class="text-danger">{{$errors->first('designation')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Email</label>
                                        <input id="email" type="text" name="email" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="Enter Email" value="{{$user->email}}" />
                                        <span class="text-danger">{{$errors->first('email')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password</label>
                                        <input id="password" type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="password" value="{{ old('password') }}" placeholder="Enter password" />
                                        <span class="text-danger">{{$errors->first('password')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Confirm Password</label>
                                        <input id="password" type="text" name="password_confirmation" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="Enter password again" value="{{ old('password_confirmation') }}" />
                                        <span class="text-danger">{{$errors->first('password_confirmation')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Password</label>
                                        <div class="input-group">
                                            <input id="password" type="password" class="form-control form-control-sm mb-lg-0"
                                                name="password" value="{{ old('password') }}" placeholder="Enter password" />
                                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                                <i class="ki-duotone ki-eye">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <span class="text-danger">{{$errors->first('password')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Confirm Password</label>
                                        <div class="input-group">
                                            <input id="confirmPassword" type="password" name="password_confirmation"
                                                class="form-control form-control-sm mb-lg-0" placeholder="Enter password again" value="{{ old('password_confirmation') }}" />
                                            <span class="input-group-text" id="toggleConfirmPassword" style="cursor: pointer;">
                                                <i class="ki-duotone ki-eye">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                </i>
                                            </span>
                                        </div>
                                        <span class="text-danger">{{$errors->first('password_confirmation')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">SU/FU Head Name</label>
                                        <input type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="su_head" value="{{ $user->su_head }}" placeholder="Enter your current SU/FU Head" />
                                        <span class="text-danger">{{$errors->first('su_head')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Roles</label>
                                        <select name="role_id" data-control="select2" class="form-select form-select-sm fw-semibold" value="{{ old('role_id') }}">
                                            <option selected disabled>- Select Role -</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}"
                                                    {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{$errors->first('role_id')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Campus</label>
                                        <select name="campus_id[]" data-control="select2" class="form-control form-control-sm" multiple>
                                            @foreach ($campusDepartments->unique('campus_id') as $campusDepartment)
                                                <option value="{{ $campusDepartment->campus_id }}"
                                                    {{ in_array($campusDepartment->campus_id, $userCampusIds) ? 'selected' : '' }}>
                                                    {{ $campusDepartment->campus->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-form-label fw-semibold fs-6">Department</label>
                                        <select id="department_id" name="department_id[]" data-control="select2" class="form-control form-control-sm" multiple>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Phone Number</label>
                                        <input id="phone_number" type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="phone_number" value="{{ $user->phone_number }}" placeholder="03000000000" />
                                        <p class="text-danger" id="phone_error"></p>
                                        <span class="text-danger">{{$errors->first('phone_number')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Strategic Unit</label>
                                        <input type="text" name="strategic_unit" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="Enter strategic unit" value="{{ $user->strategic_unit }}" />
                                        <span class="text-danger">{{$errors->first('strategic_unit')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                        <select name="status" data-control="select2" class="form-select form-select-sm fw-semibold" value="{{ old('status') }}">
                                            <option selected disabled>- Select Status -</option>
                                            <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <span class="text-danger">{{$errors->first('status')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Gender</label>
                                        <div class="form-control form-control-sm mb-3 mb-lg-0">
                                            <div class="form-check form-check-inline col-sm-6">
                                                <input class="form-check-input" type="radio" id="male"
                                                    name="gender" value="male" @if ($user->gender == 'male') checked @endif>
                                                <label class="form-check-label ms-2" for="male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline col-sm-5">
                                                <input class="form-check-input" type="radio" id="female"
                                                    name="gender" value="female" @if ($user->gender == 'female') checked @endif>
                                                <label class="form-check-label ms-2" for="female">Female</label>
                                            </div>
                                        </div>
                                        <span class="text-danger">{{$errors->first('gender')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6 ">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Address</label>
                                        <textarea type="text" name="address" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="Enter Address">{{ $user->address }}</textarea>
                                        <span class="text-danger">{{$errors->first('address')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-8 fv-row"></div>
                                    <div class="col-lg-4 d-flex justify-content-end fv-row">
                                        <button type="submit" class="btn btn-primary m-1"><i class="ki-duotone ki-check"></i> Submit</button>
                                        <button type="button" class="btn btn-danger m-1" onclick="window.location='{{ route('manageuser') }}'"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i> Cancel</button>
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
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {

        var selectedCampusIds = $('select[name="campus_id[]"]').val() || [];
        var selectedDepartmentIds = @json($userDepartmentIds ?? []);

        loadDepartments(selectedCampusIds, selectedDepartmentIds);

        $('select[name="campus_id[]"]').on('change', function () {
            var campusIds = $(this).val() || [];
            loadDepartments(campusIds, []);
        });

        function loadDepartments(campusIds, selectedDepartments = []) {
            $('#department_id').empty();
            if (!campusIds.length) return;

            // Keep track of added department IDs to avoid duplicates
            var addedDeptIds = [];

            campusIds.forEach(function(campusId) {
                var url = "{{ route('getdepartments', ':id') }}".replace(':id', campusId);
                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        response.forEach(function(dept) {
                            if (!addedDeptIds.includes(dept.id)) {
                                var isSelected = selectedDepartments.includes(dept.id) ? 'selected' : '';
                                $("#department_id").append(
                                    '<option value="'+ dept.id +'" '+isSelected+'>'+ dept.name +'</option>'
                                );
                                addedDeptIds.push(dept.id);
                            }
                        });
                        $("#department_id").trigger('change'); // refresh select2
                    }
                });
            });
        }
    });
</script> --}}
{{-- <script>
    document.addEventListener('DOMContentLoaded', function () {

        var selectedCampusIds = $('select[name="campus_id[]"]').val() || [];
        var selectedDepartmentIds = @json($userDepartmentIds ?? []); // flat array like ["1"]

        var getDepartmentsUrl = "{{ route('getdepartments', ['campus_id' => 'CAMPUS_ID']) }}";

        function loadDepartments(campusIds, preSelectedDepartments = []) {
            $('#department_id').empty();
            if (!campusIds.length) return;

            var addedDeptIds = [];

            campusIds.forEach(function(campusId) {
                var url = getDepartmentsUrl.replace('CAMPUS_ID', campusId);

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        response.forEach(function(dept) {
                            if (!addedDeptIds.includes(dept.id)) {
                                var isSelected = preSelectedDepartments.includes(dept.id.toString()) ? 'selected' : '';
                                $('#department_id').append(
                                    '<option value="'+ dept.id +'" '+isSelected+'>'+ dept.name +'</option>'
                                );
                                addedDeptIds.push(dept.id);
                            }
                        });
                        $('#department_id').trigger('change'); // refresh select2
                    }
                });
            });
        }

        // Initial load
        loadDepartments(selectedCampusIds, selectedDepartmentIds);

        // On campus change
        $('select[name="campus_id[]"]').on('change', function () {
            var campusIds = $(this).val() || [];
            loadDepartments(campusIds, []); // no pre-selection for newly selected campuses
        });

    });
</script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var selectedCampusIds = $('select[name="campus_id[]"]').val() || [];
        var selectedDepartmentIds = @json($userDepartmentIds ?? []);

        var getDepartmentsUrl = "{{ route('getdepartments', ['campus_id' => 'CAMPUS_ID']) }}";

        function loadDepartments(campusIds, preSelectedDepartments = []) {
            $('#department_id').empty();
            if (!campusIds.length) return;

            var addedDeptIds = [];

            campusIds.forEach(function(campusId) {
                var url = getDepartmentsUrl.replace('CAMPUS_ID', campusId);

                $.ajax({
                    url: url,
                    type: "GET",
                    success: function(response) {
                        response.forEach(function(dept) {
                            if (!addedDeptIds.includes(dept.id)) {
                                var isSelected = preSelectedDepartments.includes(dept.id.toString()) ? 'selected' : '';
                                $('#department_id').append(
                                    '<option value="'+ dept.id +'" '+isSelected+'>'+ dept.name +'</option>'
                                );
                                addedDeptIds.push(dept.id);
                            }
                        });
                        $('#department_id').trigger('change');
                    }
                });
            });
        }
        loadDepartments(selectedCampusIds, selectedDepartmentIds);

        $('select[name="campus_id[]"]').on('change', function () {
            var campusIds = $(this).val() || [];
            loadDepartments(campusIds, []);
        });

        $('#kt_account_profile_details_form').on('submit', function(e) {
            if (this.checkValidity()) {
                var submitButton = $(this).find('button[type="submit"]');
                submitButton.prop('disabled', true);
                submitButton.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
            } else {
                e.preventDefault();
                this.reportValidity();
            }
        });

    });
</script>
@endsection
