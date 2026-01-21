@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Roles
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
                    Roles
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
                <a type="button" class="btn btn-sm btn-light-primary" href="{{ route('addrole') }}">
                    <i class="ki-duotone ki-plus fs-2"></i>Add Role
                </a>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_views_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-70px">#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($roles as $val)
                            <tr>
                                <td><span>{{ $i }}</span></td>
                                <td>
                                    <div class="products">
                                        <div>
                                            <span>{{ $val->name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="products">
                                        <div>
                                            <span>{{ $val->guard_name }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($val->status == 1)
                                        <div class="badge badge-light-success">Active</div>
                                    @else
                                        <div class="badge badge-light-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <a class="btn btn-sm btn-success me-2" href="{{ route('editrole', $val->id) }}">
                                            Edit
                                        </a>
                                        <a class="btn btn-sm btn-warning" href="javascript:void(0);" onclick="openPermissionModel('{{ route('rolehaspermission') }}', '{{ $val->id }}')">
                                            Permission
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="manage_permission" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myLargeModalLabel">Manage Permission</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="window.location='{{ route('managerole') }}'" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive m-t-15">
                    <table class="table table-striped custom-table">
                        <input type="hidden" name="id" value="" id="role_id">
                        <thead>
                            <tr>
                                <th>Module Permission</th>
                                <th class="text-center">All</th>
                                <th class="text-center">Read</th>
                                <th class="text-center">Write</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody id="permissiontable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $('#manage_permission').on('hidden.bs.modal', function () {
    	location.reload();
    })
</script>
@endsection
