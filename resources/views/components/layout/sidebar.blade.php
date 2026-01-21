<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}"
    data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <a href="#">
            @php
                use App\Models\organizationlogo;
                $organizationLogo = organizationlogo::first();
            @endphp
            <img alt="Logo" src="{{ asset('public/organizationlogo/' . $organizationLogo->org_logo) }}" class="h-40px app-sidebar-logo-default"/>
        </a>
        <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-sm h-30px w-30px rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
            <div id="kt_app_sidebar_menu_scroll" class="hover-scroll-y my-5 mx-3" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
                data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-category fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                    <span class="path4"></span>
                                </i>
                            </span>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </div>
                    {{-- ✅ Ensure $collection is always defined --}}
                    @php
                        $collection = $collection ?? [];
                    @endphp
                    @foreach ($categories as $cat)
                        @php
                            $modules_of_this_category = $module_object
                                ->where('status', 1)
                                ->where('category_id', $cat->id)
                                ->sortBy('order_via');

                            $active_modules = $modules_of_this_category->filter(function($mod) use ($collection) {
                                return in_array($mod->slug, $collection);
                            });

                            $isCategoryActive = $active_modules->contains(function($mod) {
                                return url()->current() == url($mod->menu_template);
                            });
                        @endphp
                        @if ($active_modules->count())
                            <div data-kt-menu-trigger="click"
                                 class="menu-item menu-accordion {{ $isCategoryActive ? 'here show' : '' }}">
                                <span class="menu-link {{ $isCategoryActive ? 'active' : '' }}" data-bs-toggle="collapse">
                                    <span class="menu-icon">
                                        <i class="{{ $cat->icon }} fs-2">
                                            <span class="path1"></span>
                                            <span class="path2"></span>
                                            <span class="path3"></span>
                                             <span class="path4"></span>
                                            <span class="path5"></span>
                                            <span class="path6"></span>
                                        </i>
                                    </span>
                                    <span class="menu-title">{{ $cat->name }}</span>
                                    <span class="menu-arrow"></span>
                                </span>
                                <div class="menu-sub menu-sub-accordion" id="sidebarCategory{{ $cat->id }}">
                                    @foreach ($active_modules as $mod)
                                        <div class="menu-item">
                                            <a class="menu-link {{ url()->current() == url($mod->menu_template) ? 'active' : '' }}"
                                               href="{{ $mod->menu_template }}">
                                                <span class="menu-icon">
                                                    <i class="{{ $mod->icon }} fs-2">
                                                        <span class="path1"></span>
                                                        <span class="path2"></span>
                                                        <span class="path3"></span>
                                                        <span class="path4"></span>
                                                        <span class="path5"></span>
                                                        <span class="path6"></span>
                                                    </i>
                                                </span>
                                                <span class="menu-title">{{ $mod->module_name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                    <div class="menu-item" >
                        <a class="menu-link" href="{{route('logoutuser')}}">
                            <span class="menu-icon" ><i class="ki-duotone ki-exit-right text-danger fs-2"><span class="path1"></span><span class="path2"></span></i></span><span class="menu-title text-danger">Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
