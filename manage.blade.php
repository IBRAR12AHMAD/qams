@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
                Manage Audits
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
                    Audits
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
                <a type="button" class="btn btn-sm btn-light-primary" href="{{ route('addaudit') }}">
                    <i class="ki-duotone ki-plus fs-2"></i>Add Audit
                </a>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_views_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-70px">#</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(function () {

        if ($.fn.DataTable.isDataTable('#kt_ecommerce_report_views_table')) {
            $('#kt_ecommerce_report_views_table').DataTable().destroy();
        }

        var table = $('#kt_ecommerce_report_views_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('manageaudit') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'title', name: 'title' },
                { data: 'start_date', name: 'start_date' },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[1, 'asc']],
            initComplete: function () {
                var api = this.api();
                api.columns([0,1,2]).every(function () {
                    var column = this;
                    var input = $('<input type="text" class="footer-search" />')
                        .appendTo($(column.footer()).empty())
                        .on('keyup change clear', function () {
                            if (column.search() !== this.value) {
                                column.search(this.value).draw();
                            }
                        });
                });
            },
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: 'Audits',
                    className: 'd-none',
                },
                {
                    extend: 'excelHtml5',
                    title: 'Audits',
                    className: 'd-none',
                },
                {
                    extend: 'csvHtml5',
                    title: 'Audits',
                    className: 'd-none',
                },
                {
                    extend: 'pdfHtml5',
                    title: 'Audits',
                    className: 'd-none',
                }
            ]
        });
        $('input[data-kt-ecommerce-order-filter="search"]').on('keyup', function () {
            table.search(this.value).draw();
        });
        $('#kt_ecommerce_report_views_export_menu a').on('click', function (e) {
            e.preventDefault();
            var exportType = $(this).data('kt-ecommerce-export');
            switch(exportType) {
                case 'copy':
                    table.button(0).trigger();
                    break;
                case 'excel':
                    table.button(1).trigger();
                    break;
                case 'csv':
                    table.button(2).trigger();
                    break;
                case 'pdf':
                    table.button(3).trigger();
                    break;
            }
        });
    });
</script>
@endsection


