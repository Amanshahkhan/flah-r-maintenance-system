@extends('admin.layouts.app')

@section('content')

{{-- You can add the same styles from product.blade.php here if needed --}}
    <style>
        body { background-color: #f4f7f6; font-family: 'Tajawal', sans-serif; }
        .page-container { padding: 20px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e0e0e0; }
        .page-header h1 { font-size: 2rem; font-weight: 700; }
        .header-actions .btn { margin-left: 10px; }
        .table-card { margin-top: 20px; border: 1px solid #ddd; border-radius: 8px; padding: 10px; background-color: #fff; }
        table { width: 100%; border-collapse: collapse; text-align: right; }
        th, td { padding: 12px 15px; border: 1px solid #ddd; vertical-align: middle; }
        th { background-color: #f9f9f9; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f2f2f2; }
        .summary-cards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .summary-card { background-color: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .summary-card-header { display: flex; align-items: center; }
        .summary-card-icon { font-size: 24px; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; margin-left: 15px; }
        .summary-card-icon.icon-blue { background-color: #0d6efd; }
        .summary-card-content h3 { margin: 0; font-size: 1rem; color: #6c757d; }
        .summary-card-content .value { font-size: 1.75rem; font-weight: 700; color: #343a40; }
        .summary-card-content .sub-value { font-size: 0.9rem; color: #6c757d; }
        .modal-body .form-group { margin-bottom: 1rem; }
        .actions-cell { display: flex; gap: 5px; }
    </style>

<div class="page-container">
    <header class="page-header">
        {{-- The title now clearly indicates which contract is being managed --}}
        <div>
            <h1>إدارة منتجات العقد: {{ $contract->contract_name }}</h1>
            <h4 class="text-muted">رقم العقد: {{ $contract->contract_number }}</h4>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
    @endif

    {{-- Import Products Card --}}
    <div class="card mb-4">
        <div class="card-header">
            <strong><i class="fas fa-file-import"></i> إضافة منتجات لهذا العقد من Excel</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.contracts.import_products', $contract->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <p>قم برفع ملف Excel يحتوي على المنتجات الخاصة بهذا العقد.</p>
                <div class="input-group">
                    <input type="file" name="products_file" class="form-control" required accept=".xlsx,.xls">
                    <button class="btn btn-success" type="submit">رفع واستيراد</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Products List Card --}}
    <div class="table-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">المنتجات الحالية في هذا العقد</h5>
            
            {{-- This form is for bulk-deleting products from this specific contract --}}
            <form id="bulk-delete-form" method="POST" action="{{ route('admin.products.bulk_destroy') }}">
                @csrf
                @method('DELETE')
                <input type="hidden" name="selected_products" id="selected-products-input">
                <button type="submit" class="btn btn-danger btn-sm">
                    <i class="fas fa-trash-alt"></i> حذف المحدد
                </button>
            </form>
        </div>

        <div style="overflow-x:auto;">
            <table class="table table-bordered">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th style="width: 30px;"><input type="checkbox" id="select-all-checkbox"></th>
                        <th style="width: 50px;">#</th>
                    
                        <th>وصف البند</th>
                        <th>الكمية</th>
                        <th>السعر الافرادي</th>
                        <th>الخصم</th>
                        <th>السعر الإجمالي</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                             <td>{{ $loop->iteration }}</td>
                            <td>{{ $product->item_description }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ number_format($product->unit_price, 2) }}</td>
                            <td>{{ number_format($product->discount, 2) }}</td>
                            <td>{{ number_format($product->total_price, 2) }}</td>
                            <td>
                                <div class="actions-cell">
                                    {{-- Edit button will need a modal, which we will add --}}
                                    <button type="button" class="btn btn-info btn-sm edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProductModal"
                                            data-update-url="{{ route('admin.products.update', $product->id) }}"
                                            data-product="{{ $product->toJson() }}">
                                        تعديل
                                    </button>

                                    {{-- The delete form now points to the route for deleting a single product --}}
                                    <form action="{{ route('admin.contracts.destroy_product', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">لا توجد منتجات مضافة لهذا العقد حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL FOR EDITING A PRODUCT --}}
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
           <form id="editProductForm" method="POST" action="">
             @csrf
             @method('PATCH')
             <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">تعديل المنتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body">
                <input type="hidden" name="contract_id" value="{{ $contract->id }}"> {{-- Keep it linked to the contract --}}
                <div class="form-group mb-2"><label class="form-label">البند</label><input type="text" id="edit_item" name="item" class="form-control"></div>
                <div class="form-group mb-2"><label class="form-label">وصف البند</label><input type="text" id="edit_item_description" name="item_description" class="form-control"></div>
                <div class="form-group mb-2"><label class="form-label">المواصفات</label><input type="text" id="edit_specifications" name="specifications" class="form-control"></div>
                <div class="form-group mb-2"><label class="form-label">وحدة القياس</label><input type="text" id="edit_unit" name="unit" class="form-control" required></div>
                <div class="form-group mb-2"><label class="form-label">الكمية</label><input type="number" id="edit_quantity" name="quantity" class="form-control" required></div>
                <div class="form-group mb-2"><label class="form-label">السعر الافرادي</label><input type="number" id="edit_unit_price" step="0.01" name="unit_price" class="form-control" required></div>
                <div class="form-group mb-2"><label class="form-label">الخصم</label><input type="number" id="edit_discount" step="0.01" name="discount" class="form-control" required></div>
                <div class="form-group mb-2"><label class="form-label">السعر بعد الخصم</label><input type="number" id="edit_price_after_discount" step="0.01" name="price_after_discount" class="form-control" required></div>
                <div class="form-group mb-2"><label class="form-label">السعر شامل الضريبة</label><input type="number" id="edit_price_with_vat" step="0.01" name="price_with_vat" class="form-control" required></div>
                <div class="form-group mb-2"><label class="form-label">السعر الإجمالي</label><input type="number" id="edit_total_price" step="0.01" name="total_price" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- Bootstrap JS for the modal to work --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // --- Bulk Delete Logic ---
    const selectAllCheckbox = document.getElementById('select-all-checkbox');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkDeleteForm = document.getElementById('bulk-delete-form');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            productCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }

    if (bulkDeleteForm) {
        bulkDeleteForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const selectedIds = Array.from(productCheckboxes)
                                   .filter(i => i.checked)
                                   .map(i => i.value);

            if (selectedIds.length === 0) {
                alert('الرجاء تحديد منتج واحد على الأقل للحذف.');
                return;
            }

            if (confirm(`هل أنت متأكد من حذف ${selectedIds.length} من المنتجات؟`)) {
                document.getElementById('selected-products-input').value = JSON.stringify(selectedIds);
                this.submit();
            }
        });
    }

    // --- Edit Modal Logic ---
    const editProductModal = document.getElementById('editProductModal');
    if(editProductModal) {
        editProductModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const updateUrl = button.dataset.updateUrl;
            const product = JSON.parse(button.dataset.product);
            const form = document.getElementById('editProductForm');
            
            form.action = updateUrl;
            
            form.querySelector('#edit_item').value = product.item || '';
            form.querySelector('#edit_item_description').value = product.item_description || '';
            form.querySelector('#edit_specifications').value = product.specifications || '';
            form.querySelector('#edit_unit').value = product.unit || '';
            form.querySelector('#edit_quantity').value = product.quantity || 0;
            form.querySelector('#edit_unit_price').value = product.unit_price || 0;
            form.querySelector('#edit_discount').value = product.discount || 0;
            form.querySelector('#edit_price_after_discount').value = product.price_after_discount || 0;
            form.querySelector('#edit_price_with_vat').value = product.price_with_vat || 0;
            form.querySelector('#edit_total_price').value = product.total_price || 0;
        });
    }
});
</script>
@endpush