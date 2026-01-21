@extends('layouts.backend.app')
@section('content')
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
        <div class="page-title d-flex justify-content-between align-items-center w-100">
            <h1 class="page-heading text-gray-900 fw-bold fs-3 my-0">Edit Checklist Item</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1 text-end">
                <li class="breadcrumb-item text-muted"><a href="#" class="text-muted text-hover-primary">Dashboard</a></li>
                <li class="breadcrumb-item"><i class="bi bi-chevron-right text-gray-500 fs-8"></i></li>
                <li class="breadcrumb-item text-muted">Checklist Item</li>
            </ul>
        </div>
    </div>
</div>
<div id="kt_app_content" class="app-content flex-column-fluid">
    <div class="app-container container-xxl">
        <div class="card mb-5 mb-xl-12">
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0"><h3 class="fw-bold m-0">Edit Checklist Item</h3></div>
            </div>
            <div id="kt_account_settings_profile_details" class="collapse show">
                <form id="kt_account_profile_details_form" action="{{ route('updatechecklistitem') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $checkitems->id }}">
                    <div class="card-body border-top">
                        <div class="row mb-6">
                            <div class="col-lg-6 fv-row">
                                <label class="col-form-label required fw-semibold fs-6">Checklist</label>
                                <select name="checklist_id[]" class="form-control form-control-sm" multiple data-control="select2"
                                    oninvalid="this.setCustomValidity('Please Select Checklist')" oninput="this.setCustomValidity('')"required>
                                    @foreach ($checklists as $checklist)
                                        <option value="{{ $checklist->id }}"
                                            @if(collect(old('checklist_id', $selectedchecklist))->contains($checklist->id)) selected @endif>
                                            {{ $checklist->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('checklist_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col-lg-6 fv-row">
                                <label class="col-form-label required fw-semibold fs-6">Row Order Number</label>
                                <input type="number" class="form-control form-control-sm" name="row_ordernumber" value="{{ old('row_ordernumber', $checkitems->row_ordernumber) }}"
                                    oninvalid="this.setCustomValidity('Please Enter Number')" oninput="this.setCustomValidity('')" required>
                                @error('row_ordernumber') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @php
                            $parameters = old('parameter') ?? json_decode($checkitems->parameter, true) ?? [];
                            $header_ids = old('header_id') ?? json_decode($checkitems->header_id, true) ?? [];
                            // $order_numbers = old('order_number') ?? json_decode($checkitems->order_number, true) ?? [];
                            $statuses = old('status') ?? json_decode($checkitems->status, true) ?? [];
                            $selectitems = old('select_items') ?? json_decode($checkitems->select_items, true) ?? [];
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-row-dashed">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 15%;">Select Items</th>
                                        <th style="width: 30%;">Input</th>
                                        <th style="width: 20%;">Header</th>
                                        {{-- <th style="width: 15%;">Order No</th> --}}
                                        <th style="width: 20%;">Status</th>
                                        <th style="width: 5%;" class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="dynamicRows">
                                    @foreach($parameters as $i => $param)
                                        <tr class="rowItem">
                                            <td>
                                                <select name="select_items[]" class="form-select form-select-sm selectItemDropdown" data-control="select2">
                                                    <option value="" {{ empty($selectitems[$i]) ? 'selected' : '' }}>- Select Item -</option>
                                                    <option value="1" {{ ($selectitems[$i] ?? '') == 1 ? 'selected' : '' }}>Textarea</option>
                                                    <option value="2" {{ ($selectitems[$i] ?? '') == 2 ? 'selected' : '' }}>Radio</option>
                                                    <option value="3" {{ ($selectitems[$i] ?? '') == 3 ? 'selected' : '' }}>Empty</option>
                                                </select>
                                                @error("select_items.$i") <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <input type="text" name="parameter[]" class="form-control form-control-sm" value="{{ $param }}"
                                                    oninvalid="this.setCustomValidity('Please Enter Input')" oninput="this.setCustomValidity('')" required>
                                                @error("parameter.$i") <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td>
                                                <select name="header_id[]" class="form-select form-select-sm" data-control="select2">
                                                    <option value="">- Select Header -</option>
                                                    @foreach ($itemheaders as $header)
                                                        <option value="{{ $header->id }}" {{ ($header_ids[$i] ?? '') == $header->id ? 'selected' : '' }}>
                                                            {{ $header->title }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error("header_id.$i") <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            {{-- <td>
                                                <input type="number" name="order_number[]" class="form-control form-control-sm" value="{{ $order_numbers[$i] ?? '' }}">
                                                @error("order_number.$i") <span class="text-danger">{{ $message }}</span> @enderror
                                            </td> --}}
                                            <td>
                                                <select name="status[]" class="form-select form-select-sm">
                                                    <option value="">- Select Status -</option>
                                                    <option value="1" {{ ($statuses[$i] ?? '') == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ ($statuses[$i] ?? '') == 0 ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                                @error("status.$i") <span class="text-danger">{{ $message }}</span> @enderror
                                            </td>
                                            <td class="text-end">
                                                @if($i == 0)
                                                    <button type="button" class="btn btn-icon btn-light-success btn-sm addRow">
                                                        <i class="bi bi-plus-circle fs-3"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-icon btn-light-danger btn-sm removeRow">
                                                        <i class="bi bi-dash-circle fs-3"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary m-1"><i class="ki-duotone ki-check"></i>Submit</button>
                            <a href="{{ route('managechecklistitem') }}" class="btn btn-danger m-1"><i class="ki-duotone ki-cross-circle"><span class="path1"></span><span class="path2"></span></i>Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function () {

        function initSelect2() {
            $('[data-control="select2"]').select2();
        }
        initSelect2();

        let isPageLoad = true;

        function toggleParameter(row, changedByUser = false) {
            let selectItem = row.find('select[name="select_items[]"]').val();
            let paramInput = row.find('input[name="parameter[]"]');
            let currentValue = paramInput.val().trim();

            let isReadOnly = (selectItem == "1" || selectItem == "2" || selectItem == "3");

            if (isPageLoad && currentValue !== "") {
                paramInput.prop('readonly', isReadOnly);
                return;
            }

            if (changedByUser) {
                if (selectItem == "1") {
                    paramInput.val('textarea');
                } else if (selectItem == "2") {
                    paramInput.val('radio');
                } else if (selectItem == "3") {
                    paramInput.val('empty');
                } else {
                    paramInput.val('');
                }
            }

            paramInput.prop('readonly', isReadOnly);
        }

        $('#dynamicRows tr.rowItem').each(function () {
            toggleParameter($(this));
        });

        isPageLoad = false;

        $(document).on('change', 'select[name="select_items[]"]', function () {
            toggleParameter($(this).closest('tr'), true);
        });

        $(document).on("click", ".addRow", function () {
            let newRow = `
            <tr class="rowItem">
                <td>
                    <select name="select_items[]" class="form-select form-select-sm" data-control="select2">
                        <option value="" selected>- Select Item -</option>
                        <option value="1">Textarea</option>
                        <option value="2">Radio</option>
                        <option value="3">Empty</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="parameter[]" class="form-control form-control-sm"
                        oninvalid="this.setCustomValidity('Please Enter Input')" oninput="this.setCustomValidity('')" required>
                </td>
                <td>
                    <select name="header_id[]" class="form-select form-select-sm" data-control="select2">
                        <option value="" selected>- Select Header -</option>
                        @foreach ($itemheaders as $header)
                            <option value="{{ $header->id }}">{{ $header->title }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select name="status[]" class="form-select form-select-sm">
                        <option value="" selected>- Select Status -</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </td>
                <td class="text-end">
                    <button type="button" class="btn btn-icon btn-light-danger btn-sm removeRow">
                        <i class="bi bi-dash-circle fs-3"></i>
                    </button>
                </td>
            </tr>`;

            $("#dynamicRows").append(newRow);
            initSelect2();
        });

        $(document).on("click", ".removeRow", function () {
            $(this).closest("tr").remove();
        });

    });
</script>
{{-- <script>
$(document).ready(function () {
    function initSelect2() {
        $('[data-control="select2"]').select2();
    }
    initSelect2();

    function toggleParameter(row) {
        let selectItem = row.find('select[name="select_items[]"]').val();
        let paramInput = row.find('input[name="parameter[]"]');

        if (selectItem == "1") {
            paramInput.val('textarea').prop('readonly', true);
        } else if (selectItem == "2") {
            paramInput.val('radio').prop('readonly', true);
        } else {
            paramInput.prop('readonly', false);
        }
    }

    $('#dynamicRows tr.rowItem').each(function () {
        toggleParameter($(this));
    });

    $(document).on('change', 'select[name="select_items[]"]', function () {
        toggleParameter($(this).closest('tr'));
    });

    $(document).on("click", ".addRow", function () {
        let newRow = `
        <tr class="rowItem">
            <td>
                <select name="select_items[]" class="form-select form-select-sm" data-control="select2">
                    <option value="" selected>- Select Item -</option>
                    <option value="1">Textarea</option>
                    <option value="2">Radio</option>
                    <option value="3">Empty</option>
                </select>
            </td>
            <td>
                <input type="text" name="parameter[]" class="form-control form-control-sm"
                    oninvalid="this.setCustomValidity('Please Enter Input')" oninput="this.setCustomValidity('')" required>
            </td>
            <td>
                <select name="header_id[]" class="form-select form-select-sm" data-control="select2">
                    <option value="" selected>- Select Header -</option>
                    @foreach ($itemheaders as $header)
                        <option value="{{ $header->id }}">{{ $header->title }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <select name="status[]" class="form-select form-select-sm">
                    <option value="" selected>- Select Status -</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </td>
            <td class="text-end">
                <button type="button" class="btn btn-icon btn-light-danger btn-sm removeRow">
                    <i class="bi bi-dash-circle fs-3"></i>
                </button>
            </td>
        </tr>`;
        $("#dynamicRows").append(newRow);
        initSelect2();
    });

    $(document).on("click", ".removeRow", function () {
        $(this).closest("tr").remove();
    });
});
</script> --}}
@endsection
