@extends('client.layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form id="maintenanceForm" method="POST" action="{{ route('maintenance.store') }}" enctype="multipart/form-data">
    @csrf

    {{-- Vehicle Details (No Change) --}}
    <div class="form-group mb-3"><label for="vehicle_number">رقم سيارة:</label><input type="text" id="vehicle_number" name="vehicle_number" class="form-control" required></div>
    <div class="form-group mb-3"><label for="vehicle_color">نوع السيارة:</label><input type="text" id="vehicle_color" name="vehicle_color" class="form-control" required></div>
    <div class="form-group mb-3"><label for="vehicle_model">طراز المركبة :</label><input type="text" id="vehicle_model" name="vehicle_model" class="form-control" required></div>
    <div class="form-group mb-3">
        <label for="chassis_number">رقم الشاصي:</label>
        <input type="text" id="chassis_number" name="chassis_number" class="form-control" required>
    </div>
    <div class="form-group mb-3"><label for="vehicle_images" class="form-label">صورة رقم السيارة:</label><input type="file" id="vehicle_images" name="vehicle_images[]" class="form-control" accept="image/*" multiple></div>
    <div class="form-group mb-3">
        <label for="pdf_document" class="form-label">إضافة تحميل (PDF):</label>
        <input type="file" id="pdf_document" name="pdf_document" class="form-control" accept=".pdf">
    </div>
 

    <hr>

    {{-- NEW DYNAMIC PRODUCT SECTION --}}
    <h4>القطع المطلوبة</h4>

    {{-- Product Search Box --}}
    <div class="form-group mb-3">
        <label for="product_search">ابحث لإضافة قطعة / خدمة:</label>
        <select id="product_search" class="form-control">
            <option></option> {{-- Important: empty option for placeholder --}}
            @foreach ($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price_with_vat }}" data-name="{{ $product->item_description }}">
                    {{ $product->item_description }} - ({{ number_format($product->price_with_vat, 2) }} ريال)
                </option>
            @endforeach
        </select>
    </div>

    {{-- Table for Selected Products --}}
    <table class="table table-bordered" id="selected-products-table">
        <thead>
            <tr>
                <th>المنتج</th>
                <th style="width: 120px;">الكمية</th>
                <th>الإجراء</th>
            </tr>
        </thead>
        <tbody>
            {{-- JavaScript will add rows here --}}
        </tbody>
    </table>

    <div class="form-group mb-3">
        <label for="parts_manual">ملاحظات أو طلبات إضافية</label>
        <textarea id="parts_manual" name="parts_manual" class="form-control"></textarea>
    </div>

    <button type="submit" class="btn btn-primary">إرسال الطلب</button>
</form>

@endsection

@push('scripts')
{{-- Use Select2 for a better search experience --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    const productSearch = $('#product_search');
    const selectedTableBody = $('#selected-products-table tbody');

    // Initialize Select2
    productSearch.select2({
        placeholder: 'ابحث عن قطعة أو خدمة...',
        dir: "rtl"
    });

    // Event handler for when a product is selected
    productSearch.on('select2:select', function (e) {
        const selectedOption = $(e.params.data.element);
        const productId = selectedOption.val();
        const productName = selectedOption.data('name');
        
        // Prevent adding the same product twice
        if ($(`#row-${productId}`).length > 0) {
            alert('هذا المنتج تم إضافته بالفعل.');
            productSearch.val(null).trigger('change'); // Clear selection
            return;
        }

        // Create the new table row
        const newRow = `
            <tr id="row-${productId}">
                <td>
                    ${productName}
                    <input type="hidden" name="products[${productId}][id]" value="${productId}">
                </td>
                <td>
                    <input type="number" name="products[${productId}][quantity]" class="form-control" value="1" min="1">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-product-btn">حذف</button>
                </td>
            </tr>
        `;
        
        selectedTableBody.append(newRow);
        productSearch.val(null).trigger('change'); // Clear selection
    });

    // Event handler for the "remove" button
    selectedTableBody.on('click', '.remove-product-btn', function() {
        $(this).closest('tr').remove();
    });
});
</script>
@endpush

<style>
    /* Add this to your public/css/client_style.css file */

/* Style the main container of the search box */
.select2-container .select2-selection--multiple {
    box-sizing: border-box;
    cursor: pointer;
    display: block;
    width: 100%;
    min-height: 38px; /* Adjust height to match your inputs */
    padding: 6px 12px; /* Adjust padding to match your inputs */
    font-size: 1rem; /* Adjust font size */
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #fff;
    border: 1px solid #ced4da; /* Match border color */
    border-radius: 0.25rem; /* Match border radius */
}

/* Style the placeholder text */
.select2-container--default .select2-selection--multiple .select2-selection__placeholder {
    color: #6c757d; /* Lighter color for placeholder */
}

/* Style the little "x" to clear the selection */
.select2-container--default .select2-selection--multiple .select2-selection__clear {
    margin-right: 10px;
    float: left; /* Puts the clear button on the left in RTL */
}

/* Style the tags for selected items */
.select2-container--default .select2-selection--multiple .select2-selection__choice {
    background-color: #e9ecef; /* A light grey for selected items */
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 2px 6px;
    margin-top: 5px;
    margin-right: 5px;
}

/* Style the dropdown menu that appears */
.select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 4px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .15);
}
</style>