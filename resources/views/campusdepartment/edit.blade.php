@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Edit Campus & Department
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
                <li class="breadcrumb-item text-muted">
                    Campus & Department
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Campus & Department</h3>
                </div>
            </div>
            <div class="card-body border-top">
                <form id="kt_account_profile_details_form" action="{{ route('updatecampusdepartment') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $campusDepartments->id }}">
                    <div class="row mb-6">
                        <div class="col-lg-6 fv-row">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Campus</label>
                            <select name="campus_id" class="form-select form-select-sm fw-semibold" data-control="select2" required>
                                <option value="" disabled>- Select Campus -</option>
                                @foreach ($campuses as $campus)
                                    <option value="{{ $campus->id }}"
                                        {{ $campus->id == $campusDepartments->campus_id ? 'selected' : '' }}>
                                        {{ $campus->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('campus_id') }}</span>
                        </div>
                        <div class="col-lg-6 fv-row">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Department</label>
                            <select name="department_id[]" class="form-control form-control-sm" data-control="select2" multiple>
                                @foreach ($deparments as $deparment)
                                    <option value="{{ $deparment->id }}"
                                        {{ in_array($deparment->id, $selectedDepartments ?? []) ? 'selected' : '' }}>
                                        {{ $deparment->department_name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('department_id') }}</span>
                        </div>
                    </div>
                    <div class="row mb-6">
                        {{-- <div class="col-lg-6 fv-row">
                            <label class="col-lg-4 col-form-label fw-semibold fs-6">Program</label>
                            <select name="program_id[]" class="form-control form-control-sm" data-control="select2" multiple>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}"
                                        {{ in_array($program->id, $selectedPrograms ?? []) ? 'selected' : '' }}>
                                        {{ $program->program_name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('program_id') }}</span>
                        </div> --}}
                        <div class="col-lg-6 fv-row">
                            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                            <select name="status" class="form-select form-select-sm fw-semibold" data-control="select2" required>
                                <option value="" disabled>- Select Status -</option>
                                <option value="1" {{ $campusDepartments->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $campusDepartments->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <span class="text-danger">{{ $errors->first('status') }}</span>
                        </div>
                    </div>
                    <div class="row mb-6">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary m-1"><i class="ki-duotone ki-check"></i>Submit</button>
                            <button type="button" class="btn btn-danger m-1" onclick="window.location='{{ route('managecampusdepartment') }}'"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i>Cancel</button>
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
