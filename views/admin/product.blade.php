@extends('admin.layouts.app')

@section('content')

    {{-- STYLES ARE OK, NO CHANGES NEEDED --}}
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
    {{-- ... Page Header and Summary Cards are fine ... --}}

    <main>
        <div id="dashboard-client">
            <header class="page-header">
                <h1>إدارة المنتجات</h1>
                <div class="header-actions">
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#importContractsModal">
                        <i class="fas fa-file-import"></i> استيراد
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus"></i> إضافة منتج
                    </button>
                </div>
            </header>
            {{-- ... Summary Cards ... --}}


                 <div class="table-card" style="overflow-x:auto; margin-top: 30px;">
                
             

             <section class="content-section">
                <div class="summary-cards-grid">
                     <div class="summary-card">
                        <div class="summary-card-header">
                            <div class="summary-card-icon icon-blue"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div class="summary-card-content">
                                
                                <h3>إجمالي قيمة المنتجات</h3>
                                <div class="value" id="totalBudget">{{ number_format($totalValue, 2) }} ريال</div>
                                <div class="sub-value" id="activeContractsCount">{{ $productsCount }} منتجات</div>
                            </div>
                        </div>
                    </div>
                </div>

                   {{-- ✅ START: ADD BULK DELETE FORM --}}
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light border-bottom">
                    <h5 class="mb-0">قائمة المنتجات</h5>
                    <form id="bulk-delete-form" method="POST" action="{{ route('admin.products.bulk_destroy') }}">
                        @csrf
                        @method('DELETE')
                        {{-- This input will be filled by JavaScript --}}
                        <input type="hidden" name="selected_products" id="selected-products-input">
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-trash-alt"></i> حذف المحدد
                        </button>
                    </form>
                </div>
                {{-- ✅ END: ADD BULK DELETE FORM --}}
                
                <div class="table-card" style="overflow-x:auto; margin-top: 30px;">
                    <h5 style="text-align: right; margin-bottom: 15px;">قائمة المنتجات</h5>
                    <table id="priceDetailsTable" class="table table-bordered">
                        <thead style="background-color: #f2f2f2;">
                            <tr>
                                {{-- ✅ ADD SELECT ALL CHECKBOX --}}
                               <th style="width: 30px;"><input type="checkbox" id="select-all-checkbox"></th>
                                <th>البند</th>
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
                                    {{-- ✅ ADD ROW CHECKBOX --}}
                                <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>
                                    <td>{{ $product->item }}</td>
                                    <td>{{ $product->item_description }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>{{ number_format($product->unit_price, 2) }}</td>
                                    <td>{{ number_format($product->discount, 2) }}</td>
                                    <td>{{ number_format($product->total_price, 2) }}</td>
                                    <td>
                                        <div class="actions-cell">
                                            {{-- ✅ EDIT BUTTON ADDED --}}
                                            <button type="button" class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editProductModal"
                                                    data-bs-update-url="{{ route('admin.products.update', $product->id) }}"
                                                    data-bs-product="{{ $product->toJson() }}">
                                                تعديل
                                            </button>

                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد منتجات لعرضها حالياً.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<!-- Add Product Modal (Changed ID to be more specific) -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
           <form id="contractForm" method="POST" action="{{ route('admin.products.store') }}">
             @csrf
             <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">إضافة منتج جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body">
                <div class="form-group"><label class="form-label">البند</label><input type="text" name="item" class="form-control"></div>
                <div class="form-group"><label class="form-label">وصف البند</label><input type="text" name="item_description" class="form-control"></div>
                <div class="form-group"><label class="form-label">المواصفات</label><input type="text" name="specifications" class="form-control"></div>
                <div class="form-group"><label class="form-label">وحدة القياس</label><input type="text" name="unit" class="form-control" required></div>
                <div class="form-group"><label class="form-label">الكمية</label><input type="number" name="quantity" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر الافرادي</label><input type="number" step="0.01" name="unit_price" class="form-control" required></div>
                <div class="form-group"><label class="form-label">الخصم</label><input type="number" step="0.01" name="discount" class="form-control" value="0" required></div>
                <div class="form-group"><label class="form-label">السعر بعد الخصم</label><input type="number" step="0.01" name="price_after_discount" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر شامل الضريبة</label><input type="number" step="0.01" name="price_with_vat" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر الإجمالي</label><input type="number" step="0.01" name="total_price" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ</button>
            </div>
            </form>
        </div>
    </div>
</div>
{{-- ✅ PASTE THIS NEW MODAL BLOCK HERE --}}
<!-- Import Products Modal -->
<div class="modal fade" id="importContractsModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
           <form id="importProductsForm" method="POST" action="{{ route('admin.products.import') }}" enctype="multipart/form-data">
             @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">استيراد المنتجات من ملف Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="products_file" class="form-label">اختر ملف Excel (.xlsx, .xls)</label>
                        <input type="file" id="products_file" name="products_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">استيراد</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- ✅ END OF NEW MODAL BLOCK --}}


{{-- ✅ ADD THIS ENTIRE MODAL FOR EDITING --}}
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
           {{-- The action will be set dynamically by JavaScript --}}
           <form id="editProductForm" method="POST" action="">
             @csrf
             @method('PATCH') {{-- Important for telling Laravel this is an update --}}
             <div class="modal-header">
                <h5 class="modal-title" id="editModalTitle">تعديل المنتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
             <div class="modal-body">
                {{-- Add id attributes to each input for easier JS targeting --}}
                <div class="form-group"><label class="form-label">البند</label><input type="text" id="edit_item" name="item" class="form-control"></div>
                <div class="form-group"><label class="form-label">وصف البند</label><input type="text" id="edit_item_description" name="item_description" class="form-control"></div>
                <div class="form-group"><label class="form-label">المواصفات</label><input type="text" id="edit_specifications" name="specifications" class="form-control"></div>
                <div class="form-group"><label class="form-label">وحدة القياس</label><input type="text" id="edit_unit" name="unit" class="form-control" required></div>
                <div class="form-group"><label class="form-label">الكمية</label><input type="number" id="edit_quantity" name="quantity" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر الافرادي</label><input type="number" id="edit_unit_price" step="0.01" name="unit_price" class="form-control" required></div>
                <div class="form-group"><label class="form-label">الخصم</label><input type="number" id="edit_discount" step="0.01" name="discount" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر بعد الخصم</label><input type="number" id="edit_price_after_discount" step="0.01" name="price_after_discount" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر شامل الضريبة</label><input type="number" id="edit_price_with_vat" step="0.01" name="price_with_vat" class="form-control" required></div>
                <div class="form-group"><label class="form-label">السعر الإجمالي</label><input type="number" id="edit_total_price" step="0.01" name="total_price" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
            </div>
            </form>
        </div>
    </div>
</div>


<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- REMOVED IRRELEVANT JAVASCRIPT --}}

{{-- ✅ ADD THIS NEW JAVASCRIPT SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const editProductModal = document.getElementById('editProductModal');
    
 // ✅ START: NEW JAVASCRIPT FOR BULK DELETE
        
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const productCheckboxes = document.querySelectorAll('.product-checkbox');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const selectedProductsInput = document.getElementById('selected-products-input');

        if (selectAllCheckbox) {
            // "Select All" functionality
            selectAllCheckbox.addEventListener('change', function() {
                productCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }

        if (bulkDeleteForm) {
            // Form submission handler
            bulkDeleteForm.addEventListener('submit', function(event) {
                // Prevent the form from submitting immediately
                event.preventDefault();

                const selectedIds = [];
                productCheckboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        selectedIds.push(checkbox.value);
                    }
                });

                if (selectedIds.length === 0) {
                    alert('الرجاء تحديد منتج واحد على الأقل للحذف.');
                    return;
                }

                if (confirm(`هل أنت متأكد من حذف ${selectedIds.length} منتجات؟ لا يمكن التراجع عن هذا الإجراء.`)) {
                    // Put the selected IDs into the hidden input
                    selectedProductsInput.value = JSON.stringify(selectedIds);
                    // Now, submit the form
                    this.submit();
                }
            });
        }
        // ✅ END: NEW JAVASCRIPT FOR BULK DELETE

    // This event listener handles populating the "Edit" modal when it's about to be shown
    editProductModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        const button = event.relatedTarget;

        // Extract info from data-bs-* attributes
        const updateUrl = button.getAttribute('data-bs-update-url');
        const product = JSON.parse(button.getAttribute('data-bs-product'));

        // Get the form and the inputs inside the modal
        const form = document.getElementById('editProductForm');
        
        // Update the form's action attribute to the correct URL for this product
        form.action = updateUrl;
        
        // Populate the form fields with the product data
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
});
</script>

@endsection