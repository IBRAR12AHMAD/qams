@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">
               Manage Checklist Items
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
                    Checklist Items
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                <a type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#excelUploadModal">
                    <i class="ki-duotone ki-exit-down fs-1"><span class="path1"></span><span class="path2"></span></i> Import Checklist Items
                </a>
                <a type="button" class="btn btn-sm btn-light-primary" href="{{ route('addchecklistitem') }}">
                    <i class="ki-duotone ki-plus fs-2"></i>Add Checklist Item
                </a>
            </div>
            <div class="card-body pt-0">
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_report_views_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-70px">#</th>
                            <th>Checklist Name</th>
                            <th>Sr.No</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>#</th>
                            <th>Checklist Name</th>
                            <th>Sr.No</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        {{-- @foreach($checklistitems as $checkitem)
                            <tr>
                                <td>{{ $checkitem->id }}</td>
                                <td>
                                    @php
                                        $ids = $checkitem->checklist_id;

                                        if (is_null($ids)) {
                                            $ids = [];
                                        } elseif (!is_array($ids)) {
                                            if (is_string($ids)) {
                                                $ids = array_filter(array_map('trim', explode(',', $ids)));
                                            } else {
                                                $ids = [];
                                            }
                                        }
                                        $names = [];
                                        foreach ($ids as $id) {
                                            $id = trim($id);
                                            if ($id === '') continue;
                                            if (isset($checklists[$id])) {
                                                $names[] = $checklists[$id];
                                            }
                                        }
                                    @endphp

                                    {{ implode(', ', $names) }}
                                </td>
                                <td>{{ $checkitem->row_ordernumber }}</td>
                                @php
                                    $status = json_decode($checkitem->status);
                                @endphp
                                <td>
                                    @if (is_array($status) && in_array(1, $status))
                                        <div class="badge badge-light-success">Active</div>
                                    @else
                                        <div class="badge badge-light-danger">Inactive</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($edit_permission == 'true' || $check_all_permission == 'true')
                                        <a href="{{ route('editchecklistitem', $checkitem->id) }}" class="btn btn-sm btn-success">Edit</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- Excel Upload Modal -->
<div class="modal fade" id="excelUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Excel File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{route(('checklistitemimport'))}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-end">
                        <a href="{{asset('public/cheklistitem/checklistitem_sample.xlsx')}}"
                           class="btn btn-success btn-sm">
                            <i class="bi bi-download me-1"></i>
                            Download Excel Sample
                        </a>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Select Excel File</label>
                        <input type="file" name="excel_file" class="form-control @error('excel_file') is-invalid @enderror" accept=".xls,.xlsx" required>
                        @error('excel_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-sm btn-primary" id="uploadBtn">
                        <span class="btn-text">
                            <i class="bi bi-upload me-1"></i> Upload
                        </span>
                        <span class="btn-loading d-none">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            Uploading...
                        </span>
                    </button>
                </div>
            </form>
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
            ordering: false,
            order: [],
            ajax: "{{ route('managechecklistitem') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'checklist_names', name: 'checklist_names' },
                { data: 'row_ordernumber', name: 'row_ordernumber' },
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
                { extend: 'copyHtml5', title: 'Checklist Item', className: 'd-none' },
                { extend: 'excelHtml5', title: 'Checklist Item', className: 'd-none' },
                { extend: 'csvHtml5', title: 'Checklist Item', className: 'd-none' },
                { extend: 'pdfHtml5', title: 'Checklist Item', className: 'd-none' }
            ]
        });

        $('input[data-kt-ecommerce-order-filter="search"]').on('keyup', function () {
            table.search(this.value).draw();
        });

        $('#kt_ecommerce_report_views_export_menu a').on('click', function (e) {
            e.preventDefault();
            var exportType = $(this).data('kt-ecommerce-export');
            switch(exportType) {
                case 'copy': table.button(0).trigger(); break;
                case 'excel': table.button(1).trigger(); break;
                case 'csv': table.button(2).trigger(); break;
                case 'pdf': table.button(3).trigger(); break;
            }
        });
    });
</script>
<script>
    $('form[action="{{ route('checklistitemimport') }}"]').on('submit', function () {
        const btn = $('#uploadBtn');
        btn.prop('disabled', true);
        btn.find('.btn-text').addClass('d-none');
        btn.find('.btn-loading').removeClass('d-none');
    });
</script>
@endsection
