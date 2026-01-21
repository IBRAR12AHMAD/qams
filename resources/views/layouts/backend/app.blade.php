<!DOCTYPE html>
<html lang="en" >
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
    <title>QAMS - Riphah International University</title>
    <meta charset="utf-8"/>
    <meta name="description" content="The most advanced Bootstrap Admin Theme on Bootstrap Market trusted by over 4,000 beginners and professionals. Multi-demo, Dark Mode, RTL support. Grab your copy now and get life-time updates for free."/>
    <meta name="keywords" content="keen, bootstrap, bootstrap 5, bootstrap 4, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="index.html"/>
    <link rel="shortcut icon" href="{{asset('public/assets/images/header.jpeg')}}"/>
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700"/>
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{asset('public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.html')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('public/assets/plugins/custom/datatables/datatables.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{asset('public/assets/plugins/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('public/assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('public/assets/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <!--end::Global Stylesheets Bundle-->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-52YZ3XGZJ6"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-52YZ3XGZJ6');
    </script>
    <script>
        if (window.top != window.self) {
            window.top.location.replace(window.self.location.href);
        }
    </script>
</head>
<body  id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"  class="app-default" >
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if ( document.documentElement ) {
                if ( document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else {
                    if ( localStorage.getItem("data-bs-theme") !== null ) {
                        themeMode = localStorage.getItem("data-bs-theme");
                    } else {
                        themeMode = defaultThemeMode;
                    }
                }

                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }

                document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
        <div class="app-page flex-column flex-column-fluid " id="kt_app_page">
            <div class="app-wrapper flex-column flex-row-fluid " id="kt_app_wrapper">
                <x-layout.header/>

                <x-layout.sidebar/>

                <div class="content-page">
                    @yield('content')

                    <x-layout.footer/>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/index.html";
    </script>
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{asset('public/assets/plugins/global/plugins.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/scripts.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/demo.js')}}"></script>
    <!--begin::Vendors Javascript(used for this page only)-->
    <script src="{{asset('public/assets/plugins/custom/datatables/datatables.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/subscriptions/add/advanced.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/subscriptions/add/customer-select.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/subscriptions/add/products.js')}}"></script>
    <script src="{{asset('public/assets/plugins/custom/formrepeater/formrepeater.bundle.js')}}"></script>
    <!--begin::Custom Javascript(used for this page only)-->
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/view.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/update-details.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/add-schedule.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/add-task.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/update-email.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/update-password.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/update-role.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/add-auth-app.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/user-management/users/view/add-one-time-password.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/ecommerce/reports/views/views.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/projects/project/project.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/projects/settings/settings.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/ecommerce/catalog/save-product.js')}}"></script>
    <script src="{{asset('public/assets/js/widgets.bundle.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/apps/chat/chat.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/utilities/modals/upgrade-plan.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/utilities/modals/create-campaign.js')}}"></script>
     <script src="{{asset('public/assets/js/custom/utilities/modals/new-card.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/utilities/modals/users-search.js')}}"></script>
    <script src="{{asset('public/assets/js/custom/utilities/modals/new-target.js')}}"></script>
    <!--end::Custom Javascript-->
    @yield('script')
</body>
</html>
