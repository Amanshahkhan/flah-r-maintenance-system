@extends('admin.layouts.app')

@section('content')

<style>
    .table-card {
        margin-top: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        background-color: #fff;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        text-align: right;
        font-family: 'Tajawal', sans-serif;
    }
    th, td {
        padding: 12px 15px;
        border: 1px solid #ddd;
        vertical-align: middle; /* Aligns content vertically */
    }
    th {
        background-color: #f9f9f9;
        font-weight: bold;
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: .25rem;
        height: 1.5rem;
    }
    .progress-bar {
        background-color: #0d6efd;
        color: white;
        text-align: center;
        line-height: 1.5rem;
        font-weight: bold;
    }

    .contract-box {
        font-family: 'Tajawal', sans-serif;
        border: 2px solid #2a3f54;
        border-radius: 10px;
        padding: 25px;
        margin: 20px auto;
        background-color: #fdfdfd;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    .contract-box h2 {
        text-align: center;
        color: #2a3f54;
        margin-top: 0;
        margin-bottom: 30px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 15px;
    }
    .contract-box .field {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #eee;
    }
    .contract-box .field:last-of-type {
        border-bottom: none;
    }
    .contract-box .field-title {
        font-weight: bold;
        color: #555;
    }
    .contract-box .field-value {
        font-weight: 500;
        color: #333;
    }
    .signature-section {
        display: flex;
        justify-content: space-around;
        margin-top: 50px;
        padding-top: 20px;
    }
    .signature-box {
        width: 40%;
        border-top: 1px solid #555;
        text-align: center;
        padding-top: 10px;
        color: #777;
        font-style: italic;
    }
    .btn-print {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 30px;
        background-color: #1abb9c;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.2s;
    }
    .btn-print:hover {
        background-color: #169a81;
    }
</style>

<div id="dashboard-client">
    <header class="page-header">
        <h1>العقود</h1>
        <div class="header-actions">
            <button class="btn btn-secondary" id="importContractsBtn"><i class="fas fa-file-import"></i> استيراد</button>
            <button class="btn btn-primary" id="addContractBtn"><i class="fas fa-plus"></i> إضافة عقد</button>
        </div>
    </header>

    <!-- Dashboard Stats Cards -->
    <section class="content-section">
        <div class="summary-cards-grid">
            <div class="summary-card">
                <div class="summary-card-header">
                    <div class="summary-card-icon icon-blue"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="summary-card-content">
                        @php
                            $totalValue = $contracts->sum('total_value');
                            $activeContractsCount = $contracts->count();
                        @endphp
                        <h3>إجمالي قيمة العقود</h3>
                        <div class="value" id="totalBudget">{{ number_format($totalValue, 2) }} ريال</div>
                        <div class="sub-value" id="activeContractsCount">{{ $activeContractsCount }} عقود نشطة</div>
                    </div>
                </div>
            </div>
            {{-- Other summary cards can go here --}}
        </div>

<!-- ✅ START: ADD THIS SEARCH FORM -->
<section class="mb-4">
    <form method="GET" action="{{ route('admin.contracts_admin') }}">
        <div class="input-group">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="ابحث برقم العقد أو اسم العقد..." 
                   value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">بحث</button>
        </div>
    </form>
</section>


        <!-- Contracts Table -->
        <div class="table-card" style="overflow-x:auto;">
            <table id="contractsTable" style="width:100%; direction: rtl; border-collapse: collapse;">
                <thead style="background-color: #f2f2f2;">
                    <tr>
                        <th>المعرف</th>
                        <th>رقم العقد</th>
                        <th>اسم العقد</th>
                        <th>تاريخ العقد</th>
                        <th>تاريخ البدء</th>
                        <th>قيمة العقد</th>
                        <th>المتبقي</th>
                        <th>عرض</th>
                        <th>حذف</th>
                        <th>✅إدارة المنتجات</th> <!-- Add a new header -->

                    </tr>
                </thead>
             <tbody>
    @forelse ($contracts as $contract)
        <tr>
            {{-- All your existing data cells are fine --}}
            <td>{{ $contract->id }}</td>
            <td>{{ $contract->contract_number }}</td>
            <td>{{ $contract->contract_name }}</td>
            <td>{{ \Carbon\Carbon::parse($contract->contract_date)->format('Y-m-d') }}</td>
            <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}</td>
            <td>{{ number_format($contract->total_value, 2) }}</td>
            <td>{{ number_format($contract->remaining_value, 2) }}</td>
        
            {{-- The "View" button cell --}}
            <td>
              {{-- ✅ THIS IS THE CORRECTED BUTTON --}}
<button type="button" class="btn btn-info btn-sm view-btn"
    data-contract_number="{{ $contract->contract_number }}"
    data-contract_name="{{ $contract->contract_name }}"
    data-contract_date="{{ \Carbon\Carbon::parse($contract->contract_date)->format('Y-m-d') }}"
    data-start_date="{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}"
    data-total_value="{{ number_format($contract->total_value, 2) }}"
    data-remaining_value="{{ number_format($contract->remaining_value, 2) }}"
    title="عرض العقد">
    <i class="fas fa-eye"></i>
</button>
            </td>

            {{-- The "Delete" button cell --}}
            <td>
                <form action="{{ route('admin.contracts.destroy', $contract->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العقد؟');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                </form>
            </td>

            {{-- ✅ THIS IS THE CORRECTED PART --}}
            {{-- The "Manage Products" button is now the last cell INSIDE the row --}}
          <td>
         <a href="{{ route('admin.contracts.manage_products', $contract->id) }}" class="btn btn-sm btn-success">
          إدارة المنتجات
         </a>
        </td> 
        </tr>
    @empty
        <tr>
            <td colspan="10" class="text-center">لا توجد عقود لعرضها حالياً.</td>
        </tr>
    @endforelse
</tbody>
        </div>
    </section>

</div>

        </div>
    </main>
</div>

<div class="modal" id="contractModal">
    <div class="modal-dialog">
        <div class="modal-content">
           <form id="contractForm" method="POST" action="{{ route('admin.contracts.store') }}">
             @csrf
             <div class="modal-header">
                    <h4 id="modalTitle">إضافة عقد جديد</h4>
                    <button type="button" class="modal-close-btn" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>رقم العقد</label>
                        <input type="text" name="contract_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>اسم العقد</label>
                        <input type="text" name="contract_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>تاريخ العقد</label>
                        <input type="date" name="contract_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>تاريخ بداية العقد</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>قيمة العقد</label>
                        <input type="number" step="0.01" name="total_value" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>المتبقي من العقد</label>
                        <input type="number" step="0.01" name="remaining_value" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal" id="importContractsModal">
    <div class="modal-dialog">
        <div class="modal-content">
           <form id="importContractsForm" method="POST" action="{{ route('admin.contracts.import') }}" enctype="multipart/form-data">
             @csrf
                <div class="modal-header">
                    <h4>استيراد العقود من ملف Excel</h4>
                    <button type="button" class="modal-close-btn" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>اختر ملف Excel (.xlsx, .xls)</label>
                        
                        <input type="file" name="contracts_file" class="form-control" accept=".xlsx,.xls" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">استيراد</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <div class="modal" id="viewContractModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
              
            </div>
        </div>
    </div>
    
    <div id="toastNotification" class="toast-notification">
        <span class="icon"><i class="fas fa-check-circle"></i></span>
        <span id="toastMessage"></span> <!-- JS sets this message -->
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('addContractBtn');
        const importBtn = document.getElementById('importContractsBtn');
        const addModal = document.getElementById('contractModal');
        const importModal = document.getElementById('importContractsModal');

        // Open "Add Contract" modal
        addBtn.addEventListener('click', function () {
            addModal.style.display = 'block';
            addModal.classList.add('show');
        });

        // Open "Import Contracts" modal
        importBtn.addEventListener('click', function () {
            importModal.style.display = 'block';
            importModal.classList.add('show');
        });

        // Close modals
        document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.modal').forEach(modal => {
                    modal.style.display = 'none';
                    modal.classList.remove('show');
                });
            });
        });
    });


      document.addEventListener("DOMContentLoaded", function () {
        const viewButtons = document.querySelectorAll('.view-btn');

        viewButtons.forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('modalContractName').textContent = this.dataset.contract_name;
                document.getElementById('modalContractDate').textContent = this.dataset.contract_date;
                document.getElementById('modalStartDate').textContent = this.dataset.start_date;
                document.getElementById('modalTotalAmount').textContent = this.dataset.total_amount;
                document.getElementById('modalRemainingAmount').textContent = this.dataset.remaining_amount;
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
    // --- Code for Add/Import Modals (No changes needed here) ---
    const addBtn = document.getElementById('addContractBtn');
    const importBtn = document.getElementById('importContractsBtn');
    const addModal = document.getElementById('contractModal');
    const importModal = document.getElementById('importContractsModal');
    
    if(addBtn) {
        addBtn.addEventListener('click', () => { 
            document.getElementById('contractModal').style.display = 'block'; 
        });
    }
    if(importBtn) {
        importBtn.addEventListener('click', () => { 
            document.getElementById('importContractsModal').style.display = 'block'; 
        });
    }
    document.querySelectorAll('[data-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            btn.closest('.modal').style.display = 'none';
        });
    });

    // --- ✅ CORRECTED SCRIPT FOR VIEWING THE CONTRACT ---
    const viewButtons = document.querySelectorAll('.view-btn');
    const viewModal = document.getElementById('viewContractModal');
    const viewModalContent = viewModal.querySelector('.modal-content');

    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            // 1. Get all the data from the button's data attributes
            const contractData = this.dataset;

            // 2. Build the HTML for the contract box using the data
            const contractHtml = `
                <div class="modal-header">
                    <h4>تفاصيل العقد</h4>
                    <button type="button" class="modal-close-btn" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <div class="contract-box">
                      <h2>عقد صيانة</h2>
                      <div class="field">
                        <div class="field-title">رقم العقد:</div>
                        <div class="field-value">${contractData.contract_number}</div>
                      </div>
                      <div class="field">
                        <div class="field-title">اسم العقد:</div>
                        <div class="field-value">${contractData.contract_name}</div>
                      </div>
                      <div class="field">
                        <div class="field-title">تاريخ العقد:</div>
                        <div class="field-value">${contractData.contract_date}</div>
                      </div>
                      <div class="field">
                        <div class="field-title">تاريخ بداية العقد:</div>
                        <div class="field-value">${contractData.start_date}</div>
                      </div>
                      <div class="field">
                        <div class="field-title">قيمة العقد:</div>
                        <div class="field-value">${contractData.total_value} ريال</div>
                      </div>
                      <div class="field">
                        <div class="field-title">المتبقي من العقد:</div>
                        <div class="field-value">${contractData.remaining_value} ريال</div>
                      </div>
                      <div class="signature-section">
                        <div class="signature-box">توقيع العميل</div>
                        <div class="signature-box">توقيع الشركة</div>
                      </div>
                    </div>
                </div>
                <div class="modal-footer">
                     <button class="btn-print" onclick="window.print()">طباعة العقد</button>
                     <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                </div>
            `;

            // 3. Inject the generated HTML into the modal
            viewModalContent.innerHTML = contractHtml;

            // 4. Add a click listener to the new "close" button inside the modal
            viewModalContent.querySelector('[data-dismiss="modal"]').addEventListener('click', () => {
                viewModal.style.display = 'none';
            });
            
            // 5. Display the modal
            viewModal.style.display = 'block';
        });
    });
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

@endsection