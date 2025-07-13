{{-- This view now extends your main admin layout --}}
@extends('admin.layouts.app')

{{-- The title for this specific page --}}
@section('title', 'تفاصيل طلب الصيانة #' . $request->id)

@section('styles')
<style>
    /* --- General Styles for Screen View --- */
    /* These styles are specific to this page. You can move them to a main CSS file if you prefer. */
    .page-wrapper {
      max-width: 900px;
      margin: 2rem auto;
      background-color: #f9f9f9;
      padding: 1.5rem;
    }
    .invoice-container {
      border: 1px solid #e0e0e0;
      padding: 30px 40px;
      background: white;
      box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
    .header {
      text-align: center;
      padding-bottom: 20px;
      border-bottom: 2px solid #2a3f54;
      margin-bottom: 20px;
    }
    .header h1 { font-size: 26px; margin: 0; color: #2a3f54; }
    .header .sub-header { font-size: 14px; color: #555; }
    .section-title {
      background-color: #e9ecef;
      padding: 10px 15px;
      font-weight: bold;
      margin-top: 25px;
      margin-bottom: 10px;
      border-right: 4px solid #1abb9c;
    }
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    .details-table td, .details-table th {
      border: 1px solid #dee2e6;
      padding: 12px;
      vertical-align: top;
      text-align: right;
    }
    .details-table th {
        width: 25%;
        font-weight: 600;
        background-color: #f8f9fa;
    }
    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 60px;
    }
    .signature-box {
      width: 45%;
      text-align: center;
      border-top: 1px solid #333;
      padding-top: 10px;
      font-size: 14px;
    }
    .footer {
      text-align: center;
      font-size: 11px;
      color: #888;
      margin-top: 50px;
      padding-top: 15px;
      border-top: 1px solid #e0e0e0;
    }
    .action-buttons {
        text-align: center;
        padding: 20px 0;
        border-top: 1px solid #e0e0e0;
        margin-top: 20px;
    }
    .action-btn {
        display: inline-block;
        width: 220px;
        margin: 10px;
        padding: 12px;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        text-decoration: none;
    }
    .btn-print { background-color: #1abb9c; }
    .btn-forward { background-color: #0d6efd; }

    /* --- Status Badge Styles --- */
    .status-badge { display: inline-block; padding: 4px 12px; border-radius: 15px; font-weight: bold; color: white; }
    .status-completed { background-color: #28a745; }
    .status-in-progress { background-color: #ffc107; color: #333; }
    .status-pending-review { background-color: #0dcaf0; color: #333; }
    .status-assigned { background-color: #0d6efd; }
    .status-rejected { background-color: #dc3545; }
    .status-pending { background-color: #6c757d; }

    /* --- Modal, Toast, and Document Section Styles --- */
    .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background-color: #fefefe; margin: auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h4 { margin: 0; }
    .modal-close-btn { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; background: none; border: none; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; }
    .toast { visibility: hidden; min-width: 250px; background-color: #333; color: #fff; text-align: center; border-radius: 2px; padding: 16px; position: fixed; z-index: 1060; right: 30px; top: 30px; }
    .toast.show { visibility: visible; animation: fadein 0.5s, fadeout 0.5s 2.5s; }
    @keyframes fadein { from {top: 0; opacity: 0;} to {top: 30px; opacity: 1;} }
    @keyframes fadeout { from {top: 30px; opacity: 1;} to {top: 0; opacity: 0;} }
    .document-section, .final-actions { margin-top: 1.5rem; padding: 1.5rem; border: 1px solid #e0e0e0; border-radius: 8px; background: #fdfdfd; }
    .document-section a { margin-right: 15px; }

    /* --- Print Styles --- */
    @media print {
      body { margin: 0; padding: 0; background-color: #fff; }
      .page-wrapper { margin: 0; padding: 0; }
      .invoice-container { box-shadow: none; border: none; margin: 0; padding: 1cm; width: auto; height: auto; }
      .action-buttons, .document-section, .final-actions { display: none; }
      .footer { position: fixed; bottom: 1cm; left: 1cm; right: 1cm; }
    }
</style>
@endsection

@section('content')
<div class="page-wrapper">
    <div class="invoice-container">
        <div class="header">
            <h1>أمر طلب صيانة</h1>
            <div class="sub-header">
                <span>رقم الطلب: {{ $request->id }}</span> |
                <span>تاريخ الطلب: {{ $request->created_at->format('Y/m/d') }}</span>
            </div>
        </div>
      
      
        <table class="details-table">
            <tr><th>اسم العميل</th><td>{{ $request->user->name ?? 'غير محدد' }}</td></tr>
            <tr><th>رقم التواصل</th><td>{{ $request->user->mobile ?? 'غير محدد' }}</td></tr>
           <tr>
        <th>رقم اللوحة</th>
        <td>{{ $request->vehicle_number }}</td>
    </tr>
    <tr>
        <th>طراز السيارة</th>
        <td>{{ $request->vehicle_model }}</td>
    </tr>
    <tr>
        <th>نوع السيارة</th>
        <td>{{ $request->vehicle_color }}</td>
    </tr>
        </table>
    
        <div class="section-title">تفاصيل طلب الصيانة</div>
        <table class="details-table">
            <tr>
                <th>القطع المطلوبة</th>
                <td>
                    @if($request->products && $request->products->isNotEmpty())
                        <ul style="padding-right: 20px; margin: 0;">
                            @foreach($request->products as $product)
                                <li>{{ $product->item_description }} (الكمية: {{ $product->pivot->quantity }})</li>
                            @endforeach
                        </ul>
                    @else
                        لا يوجد
                    @endif
                </td>
            </tr>
            <tr>
              <th>الطلب / ملاحظات إضافية</th>
              <td>{{ $request->parts_manual ?: 'لا يوجد' }}</td>
            </tr>
        </table>
    
        <div class="section-title">التكلفة والحالة</div>
        <table class="details-table">
            <tr>
                <th>حالة الطلب الحالية</th>
                <td>
                    @php
                        $status_text = match($request->status) {
                            'completed' => 'مكتمل', 'in-progress' => 'قيد التنفيذ', 'Assigned' => 'تم التعيين',
                            'Pending Review' => 'بانتظار المراجعة', 'rejected' => 'مرفوض', 'pending' => 'قيد الانتظار',
                            default => $request->status
                        };
                        $status_class = 'status-' . str_replace(' ', '-', strtolower($request->status));
                    @endphp
                    <span class="status-badge {{ $status_class }}">{{ $status_text }}</span>
                </td>
            </tr>
           <tr>
    <th>التكلفة الإجمالية</th>
    <td>
        @php
            // Default to the saved cost if it exists and is greater than zero
            $displayCost = $request->total_cost ?? 0;
            if ($displayCost == 0 && $request->products->isNotEmpty()) {
                $estimatedCost = 0;
                foreach ($request->products as $product) {
                    $estimatedCost += $product->price_with_vat * $product->pivot->quantity;
                }
                $displayCost = $estimatedCost;
            }
        @endphp

        {{-- Display the calculated or saved cost --}}
        <strong>{{ number_format($displayCost, 2) }} ريال سعودي</strong>

        {{-- Add a small note if the cost is just an estimate --}}
        @if($request->total_cost == 0)
            <small class="text-muted d-block">(تقديرية قبل الموافقة)</small>
        @endif
      </td>
    </tr>
            <tr><th>المندوب المسؤول</th><td>{{ $request->representative->name ?? 'لم يتم التعيين' }}</td></tr>
        </table>
    
        <div class="signature-section">
            <div class="signature-box">توقيع المندوب<br><strong>{{ $request->representative->name ?? '________________' }}</strong></div>
            <div class="signature-box">توقيع وختم الجهة المستلمة<br><strong>________________</strong></div>
        </div>
    
        <div class="footer">
            تم إنشاء هذا المستند بواسطة نظام إدارة الصيانة.
        </div>
    </div>

    {{-- Document Review Section --}}
    @if($request->parts_receipt_doc_path || $request->install_complete_doc_path)
    <div class="document-section">
        <h3>مراجعة المستندات المرفوعة</h3>
        @if($request->parts_receipt_doc_path)
            <a href="{{ Storage::disk('public')->url($request->parts_receipt_doc_path) }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-file-alt"></i> عرض مستند استلام القطع</a>
        @endif
        @if($request->install_complete_doc_path)
            <a href="{{ Storage::disk('public')->url($request->install_complete_doc_path) }}" target="_blank" class="btn btn-outline-primary"><i class="fas fa-file-check"></i> عرض مستند إتمام التركيب</a>
        @endif
    </div>
    @endif

    {{-- Final Actions Section --}}
    @if($request->status == 'Pending Review')
    <div class="final-actions">
        <h4>الإجراءات النهائية</h4>
        <form method="POST" action="{{ route('admin.requests.approve_final', $request->id) }}" style="display: inline-block;">
            @csrf
            @method('PATCH')
            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> موافقة وإغلاق الطلب</button>
        </form>
    </div>
    @endif

    {{-- Action Buttons --}}
    <div class="action-buttons">
        <button class="action-btn btn-print" onclick="window.print()"><i class="fas fa-print"></i> طباعة الطلب</button>
        <button type="button" class="action-btn btn-forward" onclick="openForwardModal()"><i class="fas fa-paper-plane"></i> إرسال / توجيه</button>
    </div>
</div>

<!-- Forward Request Modal -->
<div id="forwardRequestModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>توجيه طلب الصيانة</h4>
            <button type="button" class="modal-close-btn" onclick="closeModal('forwardRequestModal')">×</button>
        </div>
        <div class="modal-body">
            <p>أدخل عنوان البريد الإلكتروني الذي تود إرسال نسخة من هذا الطلب إليه.</p>
            <div id="forward_error" class="alert alert-danger" style="display:none;"></div>
            <div class="form-group">
                <label for="recipient_email">البريد الإلكتروني للمستلم:</label>
                <input type="email" id="recipient_email" class="form-control" placeholder="example@company.com" required>
            </div>
            <div class="form-group">
                <label for="email_notes">ملاحظات إضافية (اختياري):</label>
                <textarea id="email_notes" class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('forwardRequestModal')">إلغاء</button>
            <button type="button" class="btn btn-primary" onclick="submitForwardRequest({{ $request->id }})"><i class="fas fa-paper-plane"></i> إرسال</button>
        </div>
    </div>
</div>

<div id="toastNotification" class="toast"></div>

@endsection

@push('scripts')
<script>
    function openForwardModal() {
        document.getElementById('forwardRequestModal').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
        document.getElementById('recipient_email').value = '';
        document.getElementById('email_notes').value = '';
        document.getElementById('forward_error').style.display = 'none';
    }

    function showToast(message) {
        const toast = document.getElementById('toastNotification');
        toast.textContent = message;
        toast.className = "toast show";
        setTimeout(() => { toast.className = toast.className.replace("show", ""); }, 3000);
    }

    function submitForwardRequest(requestId) {
        const email = document.getElementById('recipient_email').value;
        const notes = document.getElementById('email_notes').value;
        const errorDiv = document.getElementById('forward_error');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const submitButton = document.querySelector('#forwardRequestModal button[onclick^="submitForwardRequest"]');

        errorDiv.style.display = 'none';

        if (!email || !email.includes('@')) {
            errorDiv.textContent = 'الرجاء إدخال بريد إلكتروني صحيح.';
            errorDiv.style.display = 'block';
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري الإرسال...';

        fetch("{{ route('admin.requests.forward', $request->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ recipient_email: email, notes: notes })
        })
        .then(response => {
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fas fa-paper-plane"></i> إرسال';
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal('forwardRequestModal');
                showToast(data.message);
            } else {
                errorDiv.textContent = data.message || 'An unknown error occurred.';
                errorDiv.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            errorDiv.textContent = error.message || 'فشل الاتصال بالخادم.';
            errorDiv.style.display = 'block';
        });
    }

    window.addEventListener('click', function(event) {
        const modal = document.getElementById('forwardRequestModal');
        if (event.target == modal) {
            closeModal('forwardRequestModal');
        }
    });
</script>
@endpush