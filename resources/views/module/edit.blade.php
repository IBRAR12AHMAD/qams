@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Edit Module
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
                    Module                                           
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
                    <h3 class="fw-bold m-0">Edit Module</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" action="{{ route('updatemodule') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $module->id }}">
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Module Name</label>
                                        <input id="module_name" type="text" onblur="menus('{{url('/')}}')" class="form-control form-control-sm mb-3 mb-lg-0" name="module_name" value="{{ $module->module_name }}" 
                                        placeholder="Enter Module Name" oninvalid="this.setCustomValidity('Please Enter Module Name')" oninput="this.setCustomValidity('')" required/>
                                        <span class="text-danger">{{$errors->first('module_name')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Icon</label>
                                        <input type="text" name="icon" class="form-control form-control-sm mb-3 mb-lg-0" placeholder="Enter Icon" value="{{ $module->icon }}" />
                                        <span class="text-danger">{{$errors->first('icon')}}</span>
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
                                            <option value="1" {{ $module->status === 1 ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ $module->status === 0 ? 'selected' : '' }}>Inactive</option> 
                                        </select>
                                        <span class="text-danger">{{$errors->first('status')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Slug</label>
                                        <input type="text" id="permalink" name="slug" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $module->slug }}" />
                                        <span class="text-danger">{{$errors->first('slug')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Category Name</label>
                                        <select name="category_id" data-control="select2" class="form-select form-select-sm fw-semibold" value="{{ old('category_id') }}">
                                           <option selected disabled>- Select Category -</option>
                                            @foreach ($category as $cat)
                                                <option value="{{ $cat->id }}" {{ ($module->category_id == $cat->id) ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger">{{$errors->first('category_id')}}</span>
                                    </div>
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Menu Template</label>
                                        <textarea type="text" id="menudescription" name="menu_template" class="form-control form-control-sm mb-3 mb-lg-0">{{ $module->menu_template }}</textarea>
                                        <span class="text-danger">{{$errors->first('menu_template')}}</span>
                                    </div>
                                </div>
                            </div>
                         </div>
                         <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Order Via</label>
                                        <input type="text" id="order_via" name="order_via" class="form-control form-control-sm mb-3 mb-lg-0" value="{{ $module->order_via }}" />
                                        <span class="text-danger">{{$errors->first('order_via')}}</span>
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
                                        <button type="button" class="btn btn-danger m-1" onclick="window.location='{{ route('managemodule') }}'"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i>Cancel</button>
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
