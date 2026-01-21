@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                User Profile
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
                    Profile                                           
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid"> 
    <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">
                        <strong>{{ session('success') }}</strong>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">User Profile</h3>
                </div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" action="{{ route('updateprofile') }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="card-body border-top">
                        <div class="text-center">
                            <div class="symbol symbol-65px symbol-circle">
                                @if($userprofile && $userprofile->profile_img)
                                    <img src="{{ asset('public/' . $userprofile->profile_img) }}" data-toggle="modal" alt="image">
                                @else
                                    <img src="{{ asset('public/userprofile/21.jpg') }}" alt="image">
                                @endif
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12 fv-row">
                                        <label class="col-lg-4 col-form-label required fw-semibold fs-6">Profile Img</label>
                                        <input type="file" class="form-control form-control-sm mb-3 mb-lg-0" name="profile_img" required/>
                                        <span class="text-danger">{{$errors->first('profile_img')}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-6">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-8 fv-row"></div>
                                    <div class="col-lg-4 d-flex justify-content-end fv-row">
                                        <button type="submit" class="btn btn-primary m-1"><i class="ki-duotone ki-check"></i>Upload</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card-body">
                    <hr class="" />
                    <p class="mt-4 mb-1"><strong><i class='mdi mdi-account-circle'></i> User Name:</strong></p>
                    <p>{{ $user->name }}</p>

                    <p class="mt-3 mb-1"><strong><i class='mdi mdi-at'></i> Email:</strong></p>
                    <p>{{ $user->email }}</p>

                    <p class="mt-3 mb-1"><strong><i class='mdi mdi-phone-outline'></i> Phone Number:</strong></p>
                    <p>{{ $user->phone_number }}</p>

                    <p class="mt-3 mb-1"><strong><i class='mdi mdi-map-marker-account-outline'></i> Location:</strong></p>
                    <p>{{ $user->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
