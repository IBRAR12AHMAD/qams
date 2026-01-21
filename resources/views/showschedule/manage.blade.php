@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Show Audit Checklists
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
                    Audit Checklists
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-xxl ">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('primary'))
                <div class="alert alert-primary alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check me-2"></i>{{ session('primary') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>
        <div class="card card-flush">
            <div class="card-header align-items-center py-5 gap-1 gap-md-5">
                <div class="card-title">
                    <div class="d-flex align-items-center position-relative my-1">
                        <i class="ki-duotone ki-magnifier fs-2 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                        <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-12" placeholder="Search Data" />
                    </div>
                    <div id="kt_ecommerce_report_views_export" class="d-none"></div>
                </div>
                <div class="card-toolbar flex-row-fluid gap-5">
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
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_views_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            {{-- <th>#</th> --}}
                            <th>Schedule</th>
                            <th>Auditor</th>
                            <th>Audit</th>
                            <th>Checklist</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            {{-- <th>#</th> --}}
                            <th>Schedule</th>
                            <th>Auditor</th>
                            <th>Audit</th>
                            <th>Checklist</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($allSchedulesData as $schedule)
                            <tr>
                                {{-- <td>{{ $schedule->id }}</td> --}}
                                <td>{{ $schedule->title }}</td>
                                <td>{{ $schedule->auditor_name }}</td>
                                {{-- <td>{{ $schedule->aditor_id }}</td> --}}
                                <td>{{ $checklistAuditMap[$schedule->id] ?? 'N/A' }}</td>
                                <td>
                                    @if(!empty($checklistScheduleMap[$schedule->id]))
                                        {{ implode(', ', $checklistScheduleMap[$schedule->id]) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                {{-- <td>
                                    @if($schedule->submit_type == 1)
                                        <div class="badge badge-light-success">Submit</div>
                                    @elseif($schedule->submit_type == 2)
                                        <div class="badge badge-light-primary">Persal Submit</div>
                                    @else
                                        <div class="badge badge-light-warning">Pending</div>
                                    @endif
                                </td> --}}
                                {{-- <td>
                                    @switch($schedule->status)
                                        @case('submitted')
                                            <span class="badge badge-light-success">Submitted</span>
                                            @break
                                        @case('responded_submitted')
                                            <span class="badge badge-light-success">All Data Submitted</span>
                                            @break
                                        @case('responded_pending')
                                            <span class="badge badge-light-warning">Responded Pending</span>
                                            @break
                                        @default
                                            <span class="badge badge-light-danger">Pending</span>
                                    @endswitch
                                </td> --}}
                                <td>
                                    @switch($schedule->status)
                                        @case('submitted')
                                            <span class="badge badge-light-success">Submitted</span>
                                            @break
                                        @case('partial_submitted')
                                            <span class="badge badge-light-primary">Partial Submitted</span>
                                            @break
                                        @case('responded_submitted')
                                            <span class="badge badge-light-success">All Data Submitted</span>
                                            @break
                                        @case('responded_partial')
                                            <span class="badge badge-light-info">Partial Respond Submit</span>
                                            @break
                                        @case('responded_pending')
                                            <span class="badge badge-light-warning">Responded Pending</span>
                                            @break
                                        @default
                                            <span class="badge badge-light-danger">Pending</span>
                                    @endswitch
                                </td>
                                <td>
                                    @if ($check_read_permission == 'true' || $check_all_permission == 'true')
                                        <a href="{{ route('viewschedule', $schedule->id) }}" class="btn btn-sm btn-icon btn-warning">
                                            <i class="ki-duotone ki-eye fs-1">
                                                <span class="path1"></span>
                                                <span class="path2"></span>
                                                <span class="path3"></span>
                                            </i>
                                        </a>
                                    @endif
                                    @if(Auth::user()->role_id == 2)
                                        <form action="{{ route('changesubmittype', $schedule->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-danger">
                                                <i class="ki-duotone ki-call fs-1">
                                                    <span class="path1"></span>
                                                    <span class="path2"></span>
                                                    <span class="path3"></span>
                                                    <span class="path4"></span>
                                                    <span class="path5"></span>
                                                    <span class="path6"></span>
                                                    <span class="path7"></span>
                                                    <span class="path8"></span>
                                                </i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection
