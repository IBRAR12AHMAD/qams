<!--begin::Header-->
<div id="kt_app_header" class="app-header">
    <div class="app-container  container-fluid d-flex align-items-stretch justify-content-between" id="kt_app_header_container">
        <div class="d-flex align-items-center d-lg-none ms-n3 me-2" title="Show sidebar menu">
            <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
                <i class="ki-duotone ki-abstract-14 fs-1"><span class="path1"></span><span class="path2"></span></i>
            </div>
        </div>
        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
            <a href="#" class="d-lg-none">
                <img alt="Logo" src="{{asset('public/assets/images/header.jpeg')}}" class="theme-light-show h-30px"/>
                {{-- <img alt="Logo" src="{{asset('public/assets/images/header.jpeg')}}" class="theme-dark-show h-30px"/> --}}
            </a>
        </div>
        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
            <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibol px-2 px-lg"
                id="kt_app_header_menu" data-kt-menu="true">
            </div>
        </div>
        <div class="app-navbar flex-shrink-0">
            <div class="app-navbar-item ms-2 ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
                <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                    <?php
                        use App\Models\User;
                        $user = auth()->user();
                        $userProfile = User::where('id', $user->id)->first();
                    ?>
                    @if ($userProfile && $userProfile->profile_img)
                        <img alt="user" src="{{ asset('public/' . $userProfile->profile_img) }}"/>
                    @else
                        <img alt="user" src="{{ asset('public/userprofile/21.jpg') }}"/>
                    @endif
                </div>
                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                    <div class="menu-item px-3">
                        <div class="menu-content d-flex align-items-center px-3">
                            <div class="symbol symbol-50px me-5">
                                @if ($userProfile && $userProfile->profile_img)
                                    <img alt="Logo" src="{{ asset('public/' . $userProfile->profile_img) }}"/>
                                @else
                                    <img alt="Logo" src="{{ asset('public/userprofile/21.jpg') }}"/>
                                @endif
                            </div>
                            <div class="d-flex flex-column">
                                <div class="fw-bold d-flex align-items-center fs-5">
                                    {{ $user->name }}
                                    {{-- <span class="badge badge-light-success fw-bold fs-8 px-2 py-1 ms-2">Pro</span> --}}
                                </div>
                                <a href="#" class="fw-semibold text-muted text-hover-primary fs-7">
                                    {{ $user->designation }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="separator my-2"></div>
                    <div class="menu-item px-5">
                        <a href="{{route('userprofile')}}" class="menu-link px-5">
                            My Profile
                        </a>
                    </div>
                    {{-- <div class="menu-item px-5">
                        <a href="apps/projects/list.html" class="menu-link px-5">
                            <span class="menu-text">My Projects</span>
                            <span class="menu-badge">
                                <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
                            </span>
                        </a>
                    </div>
                    <div class="menu-item px-5 my-1">
                        <a href="account/settings.html" class="menu-link px-5">
                            Account Settings
                        </a>
                    </div> --}}
                    <div class="menu-item px-5">
                        <a href="{{route('logoutuser')}}" class="menu-link px-5 text-danger">
                            Sign Out
                        </a>
                    </div>
                </div>
            </div>
            <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
                <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
                    <i class="ki-duotone ki-element-4 fs-1"><span class="path1"></span><span class="path2"></span></i>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Header-->
