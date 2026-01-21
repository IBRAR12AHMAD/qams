@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Edit Role
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
                    Role                                           
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
                    <h3 class="fw-bold m-0">Edit Role</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" action="{{ route('updaterole') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $roles->id }}">
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Role Name</label>
                                        <input id="name" type="text" class="form-control form-control-sm mb-3 mb-lg-0" name="name" value="{{ $roles->name }}"
                                        onblur="creat_link(),menus('{{ url('/') }}')" placeholder="Enter Role Name" oninvalid="this.setCustomValidity('Please Enter Role Name')" oninput="this.setCustomValidity('')" required/>
                                        <span class="text-danger">{{$errors->first('name')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Slug</label>
                                        <input type="text" name="slug" class="form-control form-control-sm mb-3 mb-lg-0" id="permalink" value="{{ $roles->guard_name }}" readonly/>
                                        <span class="text-danger">{{$errors->first('slug')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Status</label>
                                        <select name="status" data-control="select2" class="form-select form-select-sm fw-semibold" value="{{ old('role_id') }}">
                                            <option disabled>- Select Status -</option>
                                            <option value="1" {{ $roles->status == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <Option value="0" {{ $roles->status == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </Option> 
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
                                        <button type="submit" class="btn btn-primary m-1"><i class="ki-duotone ki-check"></i> Submit</button>
                                        <button type="button" class="btn btn-danger m-1" onclick="window.location='{{ route('managerole') }}'"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i> Cancel</button>
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
    /////////////////////// Add role generateSlug///////////////////
    function generateSlug() {
        var name = $('#name').val().trim().toLowerCase();
        var slug = name.replace(/\s+/g, '-');
        $('#permalink').val(slug);
    }
    $('#name').on('input', generateSlug);
</script>
@endsection
