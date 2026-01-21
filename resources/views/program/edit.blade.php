@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Edit Program
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1 text-end">
                <li class="breadcrumb-item text-muted">
                    <a href="#" class="text-muted text-hover-primary">
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-chevron-right text-gray-500 fs-8"></i>
                </li>
                <li class="breadcrumb-item text-muted">Programs</li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Program</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" action="{{ route('updateprogram') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $programs->id }}">
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Program Name</label>
                                        <input id="program_name" type="text" class="form-control form-control-sm mb-3 mb-lg-0"
                                            name="program_name" value="{{ old('program_name', $programs->program_name) }}" placeholder="Enter Program Name"
                                            oninvalid="this.setCustomValidity('Please Enter Program Name')" oninput="this.setCustomValidity('')" required />
                                        <span class="text-danger">{{ $errors->first('program_name') }}</span>
                                    </div>
                                    {{-- <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                        <select name="status" data-control="select2" class="form-select form-select-sm fw-semibold">
                                            <option disabled>- Select Status -</option>
                                            <option value="1" {{ $programs->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $programs->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
                                    </div> --}}
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Department Name</label>
                                        <select name="department_id" data-control="select2"
                                                class="form-select form-select-sm fw-semibold">
                                            <option selected disabled>- Select Department -</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ $department->id == $programs->department_id ? 'selected' : '' }}>
                                                    {{ $department->department_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{ $errors->first('department_id') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                        <select name="status" data-control="select2" class="form-select form-select-sm fw-semibold">
                                            <option disabled>- Select Status -</option>
                                            <option value="1" {{ $programs->status == 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $programs->status == 0 ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('status') }}</span>
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
                                        <button type="button" class="btn btn-danger m-1" onclick="window.location='{{ route('manageprogram') }}'"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i>Cancel</button>
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
@endsection
