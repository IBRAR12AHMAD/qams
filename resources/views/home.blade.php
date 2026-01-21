@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Dashboard...
            </h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1 text-end">
                <li class="breadcrumb-item text-muted">
                    <a href="#" class="text-muted text-hover-primary">
                        Audit System
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <i class="bi bi-chevron-right text-gray-500 fs-8"></i>
                </li>
                <li class="breadcrumb-item text-muted">
                    Dashboard
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid" >
    <div id="kt_app_content_container" class="app-container container-fluid">
        <div class="row g-5 g-xl-10 mb-5 mb-xl-10">
            {{-- <h1 class="page-heading text-gray-900 fw-bold fs-3">
                Dashboard...
            </h1> --}}
            {{-- <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-end h-md-50 mb-5 mb-xl-10"
                    style="background-color: #3E97FF;background-image:url('assets/media/svg/shapes/widget-bg-1.png')">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">69</span>
                            <span class="text-white opacity-75 pt-1 fw-semibold fs-6">Active Projects in April</span>
                        </div>
                    </div>
                    <div class="card-body d-flex align-items-end pt-0">
                        <div class="d-flex align-items-center flex-column mt-3 w-100">
                            <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-75 w-100 mt-auto mb-2">
                                <span>43 Pending</span>
                                <span>72%</span>
                            </div>
                            <div class="h-8px mx-3 w-100 bg-white bg-opacity-50 rounded">
                                <div class="bg-white rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-flush h-md-50 mb-xl-10">
                    <div class="card-header pt-5">
                        <h3 class="card-title text-gray-800 fw-bold">External Links</h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                <i class="ki-duotone ki-dots-square fs-1 text-gray-500 me-n1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        New Ticket
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        New Customer
                                    </a>
                                </div>
                                <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
                                    <a href="#" class="menu-link px-3">
                                        <span class="menu-title">New Group</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                        <!--begin::Menu item-->
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Admin Group
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Staff Group
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Member Group
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        New Contact
                                    </a>
                                </div>
                                <div class="separator mt-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <div class="menu-content px-3 py-3">
                                        <a class="btn btn-primary  btn-sm px-4" href="#">
                                            Generate Reports
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack">
                            <a href="#" class="text-primary fw-semibold fs-6 me-2">Avg. Client Rating</a>
                            <button type="button" class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                <i class="ki-duotone ki-exit-right-corner fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <a href="#" class="text-primary fw-semibold fs-6 me-2">Instagram Followers</a>
                            <button type="button" class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                <i class="ki-duotone ki-exit-right-corner fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <a href="#" class="text-primary fw-semibold fs-6 me-2">Google Ads CPC</a>
                            <button type="button" class="btn btn-icon btn-sm h-auto btn-color-gray-500 btn-active-color-primary justify-content-end">
                                <i class="ki-duotone ki-exit-right-corner fs-2"><span class="path1"></span><span class="path2"></span></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-md-5 mb-xl-10">
                <div class="card card-flush h-md-50 mb-5 mb-xl-10">
                    <div class="card-header pt-5">
                        <div class="card-title d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <span class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">$</span>
                                <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                                <span class="badge badge-light-success fs-base">
                                    <i class="ki-duotone ki-arrow-up fs-5 text-success ms-n1"><span class="path1"></span><span class="path2"></span></i>
                                    2.2%
                                </span>
                            </div>
                            <span class="text-gray-500 pt-1 fw-semibold fs-6">Projects Earnings in April</span>
                        </div>
                    </div>
                    <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                        <div class="d-flex flex-center me-5 pt-2">
                            <div id="kt_card_widget_17_chart"
                                style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11">
                            </div>
                        </div>
                        <div class="d-flex flex-column content-justify-center flex-row-fluid">
                            <div class="d-flex fw-semibold align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Leaf CRM</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">$7,660</div>
                            </div>
                            <div class="d-flex fw-semibold align-items-center my-3">
                                <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Mivy App</div>
                                <div class="fw-bolder text-gray-700 text-xxl-end">$2,820</div>
                            </div>
                            <div class="d-flex fw-semibold align-items-center">
                                <div class="bullet w-8px h-3px rounded-2 me-3" style="background-color: #E4E6EF"></div>
                                <div class="text-gray-500 flex-grow-1 me-4">Others</div>
                                <div class=" fw-bolder text-gray-700 text-xxl-end">$45,257</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-flush h-lg-50">
                    <div class="card-header pt-5">
                        <h3 class="card-title text-gray-800">Highlights</h3>
                        <div class="card-toolbar d-none">
                            <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left" class="btn btn-sm btn-light d-flex align-items-center px-4">
                                <div class="text-gray-600 fw-bold">
                                    Loading date range...
                                </div>
                                <i class="ki-duotone ki-calendar-8 fs-1 ms-2 me-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-5">
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Client Rating</div>
                            <div class="d-flex align-items-senter">
                                <i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
                                    <span class="text-gray-900 fw-bolder fs-6">7.8</span>
                                    <span class="text-gray-500 fw-bold fs-6">/10</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Quotes</div>
                            <div class="d-flex align-items-senter">
                                <i class="ki-duotone ki-arrow-down-right fs-2 text-danger me-2"><span class="path1"></span><span class="path2"></span></i>
                                <span class="text-gray-900 fw-bolder fs-6">730</span>
                            </div>
                        </div>
                        <div class="separator separator-dashed my-3"></div>
                        <div class="d-flex flex-stack">
                            <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Agent Earnings</div>
                            <div class="d-flex align-items-senter">
                                <i class="ki-duotone ki-arrow-up-right fs-2 text-success me-2"><span class="path1"></span><span class="path2"></span></i>
                                <span class="text-gray-900 fw-bolder fs-6">$2,309</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            {{-- <div class="col-xxl-6">
                <div class="card card-flush h-md-100">
                    <div class="card-header pt-5">
                        <h3 class="card-title align-items-start flex-column">
                            <span class="card-label fw-bold text-gray-800">Authors Achievements</span>
                            <span class="text-gray-500 mt-1 fw-semibold fs-6">Avg. 69.34% Conv. Rate</span>
                        </h3>
                        <div class="card-toolbar">
                            <button class="btn btn-icon btn-color-gray-500 btn-active-color-primary justify-content-end" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-overflow="true">
                                <i class="ki-duotone ki-dots-square fs-1 text-gray-500 me-n1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                            </button>
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold w-200px" data-kt-menu="true">
                                <div class="menu-item px-3">
                                    <div class="menu-content fs-6 text-gray-900 fw-bold px-3 py-4">Quick Actions</div>
                                </div>
                                <div class="separator mb-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        New Ticket
                                    </a>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        New Customer
                                    </a>
                                </div>
                                <div class="menu-item px-3" data-kt-menu-trigger="hover" data-kt-menu-placement="right-start">
                                    <a href="#" class="menu-link px-3">
                                        <span class="menu-title">New Group</span>
                                        <span class="menu-arrow"></span>
                                    </a>
                                    <div class="menu-sub menu-sub-dropdown w-175px py-4">
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Admin Group
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Staff Group
                                            </a>
                                        </div>
                                        <div class="menu-item px-3">
                                            <a href="#" class="menu-link px-3">
                                                Member Group
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="menu-item px-3">
                                    <a href="#" class="menu-link px-3">
                                        New Contact
                                    </a>
                                </div>
                                <div class="separator mt-3 opacity-75"></div>
                                <div class="menu-item px-3">
                                    <div class="menu-content px-3 py-3">
                                        <a class="btn btn-primary  btn-sm px-4" href="#">
                                            Generate Reports
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-6">
                        <ul class="nav nav-pills nav-pills-custom mb-3">
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2 active"
                                    id="kt_stats_widget_16_tab_link_1" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_1">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-duotone ki-car fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">
                                        SaaS
                                    </span>
                                    <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                </a>
                            </li>
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2"
                                    id="kt_stats_widget_16_tab_link_2"data-bs-toggle="pill" href="#kt_stats_widget_16_tab_2">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-duotone ki-bitcoin fs-1"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">
                                        Crypto
                                    </span>
                                    <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                </a>
                            </li>
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2"
                                    id="kt_stats_widget_16_tab_link_3" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_3">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-duotone ki-like fs-1"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">
                                        Social
                                    </span>
                                    <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                </a>
                            </li>
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2"
                                    id="kt_stats_widget_16_tab_link_4" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_4">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-duotone ki-tablet fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">
                                        Mobile
                                    </span>
                                    <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                </a>
                            </li>
                            <li class="nav-item mb-3 me-3 me-lg-6">
                                <a class="nav-link btn btn-outline btn-flex btn-color-muted btn-active-color-primary flex-column overflow-hidden w-80px h-85px pt-5 pb-2"
                                    id="kt_stats_widget_16_tab_link_5" data-bs-toggle="pill" href="#kt_stats_widget_16_tab_5">
                                    <div class="nav-icon mb-3">
                                        <i class="ki-duotone ki-send fs-1"><span class="path1"></span><span class="path2"></span></i>
                                    </div>
                                    <span class="nav-text text-gray-800 fw-bold fs-6 lh-1">
                                        Others
                                    </span>
                                    <span class="bullet-custom position-absolute bottom-0 w-100 h-4px bg-primary"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
</div>
{{-- <div id="kt_app_content" class="app-content flex-column-fluid" >
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-1 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-2 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Report" />
                    </div>
                    <div id="kt_ecommerce_report_views_export" class="d-none"></div>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-center gap-5">
                    <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>Export
                    </button>
                    <div id="kt_ecommerce_report_views_export_menu" class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4" data-kt-menu="true">
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-ecommerce-export="copy">
                                Copy to clipboard
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-ecommerce-export="excel">
                                Export as Excel
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-ecommerce-export="csv">
                                Export as CSV
                            </a>
                        </div>
                        <div class="menu-item px-3">
                            <a href="#" class="menu-link px-3" data-kt-ecommerce-export="pdf">
                                Export as PDF
                            </a>
                        </div>
                    </div>
                </div>
                <a type="button" class="btn btn-primary" href="{{ route('adduser') }}">
                    <i class="ki-duotone ki-exit-up fs-2"><span class="path1"></span><span class="path2"></span></i>Add Button
                </a>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_views_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-100px">id</th>
                            <th class="min-w-100px">Price</th>
                            <th class="min-w-70px">Status</th>
                            <th class="min-w-100px">Percent</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        <tr>
                            <td class="pe-0">
                                <span class="fw-bold">04768004</span>
                            </td>
                            <td class="pe-0">
                                <span>$258.00</span>
                            </td>
                            <td>
                                <div class="badge badge-light-danger">Inactive</div>
                            </td>
                            <td class="pe-0">
                                26.62%
                            </td>
                        </tr>
                        <tr>
                            <td class="pe-0">
                                <span class="fw-bold">02587007</span>
                            </td>
                            <td class="pe-0">
                                <span>$143.00</span>
                            </td>
                            <td>
                                <div class="badge badge-light-success">Active</div>
                            </td>
                            <td class="pe-0">
                                17.936%
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> --}}
@endsection

