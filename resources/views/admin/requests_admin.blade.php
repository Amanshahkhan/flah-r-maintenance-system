@extends('admin.layouts.app')

@section('content')
<style>
    /* Basic Modal CSS (add to your admin CSS file) */
    .modal {
        display: none; /* Initially hidden */
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 5px;
        position: relative;
    }
    .modal-header {
        padding: 10px 15px;
        border-bottom: 1px solid #e9ecef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .modal-header h4 { margin: 0; }
    .close-btn {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #000;
        text-shadow: 0 1px 0 #fff;
        opacity: .5;
        background: transparent;
        border: 0;
        cursor: pointer;
    }
    .close-btn:hover { opacity: .75; }
    .modal-body { padding: 15px; }
    .modal-footer {
        padding: 10px 15px;
        border-top: 1px solid #e9ecef;
        text-align: right; /* Adjusted for Arabic, usually left */
    }
    .modal-footer .btn + .btn { margin-right: 5px; /* Adjusted for RTL */ margin-left:0;}
    .form-group { margin-bottom: 1rem; }
    .form-control { display: block; width: 100%; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff; background-clip: padding-box; border: 1px solid #ced4da; border-radius: .25rem; }
    .alert { padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
    .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }

    .status-label { padding: 5px 10px; border-radius: 4px; color: white; font-weight: bold; display: inline-block; }
    .status-pending { background-color: #6c757d; /* Grey for pending */ }
    .status-in-progress { background-color: #ffc107; /* Yellow/Orange */ }
    .status-completed { background-color: #28a745; /* Green */ }
    .status-rejected { background-color: #dc3545; /* Red */ }
    .btn-complete { background-color: #17a2b8; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 3px; margin: 2px; }
    .btn-accept, .btn-reject { margin: 2px; } /* Add some margin to action buttons */

    .toast {
        visibility: hidden;
        min-width: 250px;
        background-color: #333;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 16px;
        position: fixed;
        z-index: 1060;
        right: 30px; /* For RTL */
        top: 30px;
        font-size: 17px;
    }
    .toast.show {
        visibility: visible;
        -webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
        animation: fadein 0.5s, fadeout 0.5s 2.5s;
    }
    @-webkit-keyframes fadein {
        from {top: 0; opacity: 0;}
        to {top: 30px; opacity: 1;}
    }
    @keyframes fadein {
        from {top: 0; opacity: 0;}
        to {top: 30px; opacity: 1;}
    }
    @-webkit-keyframes fadeout {
        from {top: 30px; opacity: 1;}
        to {top: 0; opacity: 0;}
    }
    @keyframes fadeout {
        from {top: 30px; opacity: 1;}
        to {top: 0; opacity: 0;}
    }
</style>

<!-- Dashboard Client Content -->
<div id="dashboard-client">
    <header class="page-header">
        <h1>طلبات الصيانة</h1>
    </header>

    <!-- Summary Cards -->
    <section class="summary-cards-section">
        <div class="summary-card" data-status-filter="all">
            <div class="card-content">
                <h3>جميع الطلبات</h3>
                <p class="count" id="countAll">{{ $requests->count() }}</p>
            </div>
            <div class="card-icon"><i class="fas fa-list-alt"></i></div>
        </div>
         <a href="{{ route('admin.request_progress') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card" data-status-filter="in-progress">
                <div class="card-content">
                    <h3>قيد التنفيذ</h3>
                    <p class="count" id="countInProgress">{{ $requests->where('status', 'in-progress')->count() }}</p>
                </div>
                <div class="card-icon icon-in-progress"><i class="fas fa-cogs"></i></div>
            </div>
        </a>
        <a href="{{ route('admin.request_completed') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card" data-status-filter="completed">
                <div class="card-content">
                    <h3>مكتملة</h3>
                    <p class="count" id="countCompleted">{{ $requests->where('status', 'completed')->count() }}</p>
                </div>
                <div class="card-icon icon-completed"><i class="fas fa-check-circle"></i></div>
            </div>
        </a>
        <a href="{{ route('admin.request_rejects') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card" data-status-filter="rejected">
                <div class="card-content">
                    <h3>مرفوضة</h3>
                    <p class="count" id="countRejected">{{ $requests->where('status', 'rejected')->count() }}</p>
                </div>
                <div class="card-icon icon-rejected"><i class="fas fa-times-circle"></i></div>
            </div>
        </a>
    </section>

    <!-- Table Section -->
    <section class="table-section card">
        <div class="card-header">
            <h2>قائمة الطلبات</h2>
        </div>
        <div class="table-container">
            <div class="table-header">
                <div class="table-cell">رقم الهاتف</div>
                <div class="table-cell">العميل</div>
                <div class="table-cell">رقم العقد</div>
                <div class="table-cell">رقم المركبة</div>
                <div class="table-cell">موديل المركبة</div>
                <div class="table-cell">القطع المطلوبة</div>
                <div class="table-cell">التاريخ</div>
                <div class="table-cell">عرض</div>
                <div class="table-cell">الإجراءات</div>
            </div>

            @forelse ($requests as $request)
            <div class="table-row" id="request-row-{{ $request->id }}" data-status="{{ $request->status }}">
                <div class="table-cell" data-label="رقم الهاتف">{{ $request->user->mobile ?? 'N/A' }}</div>
                <div class="table-cell" data-label="العميل">{{ $request->user->name ?? 'N/A' }}</div>
                <div class="table-cell" data-label="رقم العقد">{{ $request->user?->contract?->contract_number ?? 'N/A' }}</div>
                <div class="table-cell" data-label="رقم المركبة">{{ $request->vehicle_number }}</div>
                <div class="table-cell" data-label="موديل المركبة">{{ $request->vehicle_model }}</div>
                <div class="table-cell" data-label="القطع المطلوبة">
                    @if($request->products && $request->products->isNotEmpty())
                        <ul>
                            @foreach($request->products as $product)
                                <li>{{ $product->item_description }} (x{{ $product->pivot->quantity }})</li>
                            @endforeach
                        </ul>
                    @else
                        لا يوجد
                    @endif
                </div>
                <div class="table-cell" data-label="التاريخ">{{ $request->created_at->format('Y-m-d') }}</div>
                <div class="table-cell" data-label="عرض">
                    <a href="{{ route('admin.view_request', $request->id) }}" class="btn-view">عرض</a>
                </div>
                <div class="table-cell actions-cell" data-label="الإجراءات">
                    {{-- ✅ START: CORRECTED LOGIC BLOCK --}}
                    @if($request->status == 'pending')
                        <button class="btn btn-success btn-accept" onclick="openAssignModal({{ $request->id }})">قبول</button>
                        <button class="btn btn-danger btn-reject" onclick="openRejectModal({{ $request->id }})">رفض</button>

                    @elseif($request->status == 'in-progress')
                        <span class="status-label status-in-progress">قيد التنفيذ</span>
                        @if($request->representative)
                            <small>({{ $request->representative->name }})</small>
                        @endif

                    @elseif($request->status == 'Pending Review')
                        <span class="status-label" style="background-color: #0d6efd;">بانتظار المراجعة</span>
                        <a href="{{ route('admin.view_request', $request->id) }}" class="btn btn-primary btn-sm">مراجعة</a>

                    @elseif($request->status == 'completed')
                        <span class="status-label status-completed">مكتمل</span>

                    @elseif($request->status == 'rejected')
                        <span class="status-label status-rejected">مرفوض</span>

                    @else
                        <span class="status-label status-pending">{{ ucfirst($request->status) }}</span>
                    @endif
                    {{-- ✅ END: CORRECTED LOGIC BLOCK --}}
                </div>
            </div>
            @empty
            <div class="table-row">
                <div class="table-cell" colspan="9" style="text-align: center;">لا توجد طلبات حالياً.</div>
            </div>
            @endforelse
        </div>
    </section>
</div>
<!-- Toast Notification -->
<div id="toastNotification" class="toast">
    <span id="toastMessage"></span>
</div>

<!-- Representative Assignment Modal -->
<div id="assignRepresentativeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>تعيين مندوب لطلب الصيانة</h4>
            <button type="button" class="close-btn" onclick="closeModal('assignRepresentativeModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="assignRepresentativeForm">
                @csrf
                <input type="hidden" name="request_id" id="assign_request_id">
                <div class="form-group">
                    <label for="representative_id_select">اختر المندوب:</label>
                    <select name="representative_id" id="representative_id_select" class="form-control" required>
                        <option value="">جاري تحميل المندوبين...</option>
                    </select>
                </div>
                <div id="assignError" class="alert alert-danger" style="display:none;"></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('assignRepresentativeModal')">إلغاء</button>
            <button type="button" class="btn btn-primary" onclick="submitAssignRepresentative()">تعيين</button>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div id="rejectReasonModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h4>سبب رفض الطلب</h4>
            <button type="button" class="close-btn" onclick="closeModal('rejectReasonModal')">×</button>
        </div>
        <div class="modal-body">
            <form id="rejectReasonForm">
                @csrf
                <input type="hidden" name="request_id" id="reject_request_id">
                <div class="form-group">
                    <label for="rejection_reason_text">سبب الرفض:</label>
                    <textarea name="rejection_reason" id="rejection_reason_text" class="form-control" rows="3" required></textarea>
                </div>
                <div id="rejectError" class="alert alert-danger" style="display:none;"></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('rejectReasonModal')">إلغاء</button>
            <button type="button" class="btn btn-danger" onclick="submitRejectRequest()">تأكيد الرفض</button>
        </div>
    </div>
</div>
@endsection




@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    {{-- Your other script includes --}}

    <script>
        // --- Global Helper Functions ---
        function closeModal(modalId) {
            $('#' + modalId).hide();
            // Clear form fields and error messages when closing modals
            if (modalId === 'assignRepresentativeModal') {
                $('#assignRepresentativeForm')[0].reset();
                $('#assignError').hide().text('');
                $('#representative_id_select').html('<option value="">جاري تحميل المندوبين...</option>');
            } else if (modalId === 'rejectReasonModal') {
                $('#rejectReasonForm')[0].reset();
                $('#rejectError').hide().text('');
            }
        }

        function showToast(message, isError = false) {
            const toast = $('#toastNotification');
            $('#toastMessage').text(message);
            toast.removeClass('show error success'); // Assuming you might add .error or .success classes for styling
            toast.addClass('show');
            // Add error/success class if you have specific styling for them
            // toast.addClass(isError ? 'error-toast' : 'success-toast');
            setTimeout(function() {
                toast.removeClass('show');
            }, 3000);
        }

        // --- Modal Opening Functions (called by inline onclick) ---
        function openAssignModal(requestId) {
            console.log("Opening assign modal for request ID: " + requestId);
            $('#assign_request_id').val(requestId);
            $('#assignError').hide();
            $('#representative_id_select').html('<option value="">جاري تحميل المندوبين...</option>');
            $('#assignRepresentativeModal').show(); // Show modal immediately

            // Fetch representatives
           $.ajax({
    url: "{{ route('admin.representatives.listJson') }}",
    type: 'GET',
    success: function(data) {
        const select = $('#representative_id_select');
        select.empty(); // Clear existing options
        if (data && data.length > 0) {
            select.append('<option value="">اختر المندوب</option>');
            data.forEach(function(representative) {
                // Ensure 'representative.name' and 'representative.id' exist in your JSON response
                select.append(new Option(representative.name, representative.id));
            });
        } else {
            select.append('<option value="">لا يوجد مندوبين متاحين</option>');
        }
    },
    error: function(xhr) {
        console.error("Error fetching representatives:", xhr.responseText);
        $('#representative_id_select').html('<option value="">خطأ في تحميل المندوبين</option>');
        $('#assignError').text('خطأ في تحميل قائمة المندوبين. حاول مرة أخرى.').show();
    }
      });
        }

        function openRejectModal(requestId) {
            console.log("Opening reject modal for request ID: " + requestId);
            $('#reject_request_id').val(requestId);
            $('#rejection_reason_text').val('');
            $('#rejectError').hide();
            $('#rejectReasonModal').show();
        }

        // --- Form Submission Functions (called by modal buttons) ---
        function submitAssignRepresentative() {
            const form = $('#assignRepresentativeForm');
            const requestId = $('#assign_request_id').val();
            const representativeId = $('#representative_id_select').val();

            if (!representativeId) {
                $('#assignError').text('الرجاء اختيار مندوب.').show();
                return;
            }
            $('#assignError').hide();

            $.ajax({
                url: "{{ url('admin/requests') }}/" + requestId + "/assign", // Using url() helper for base path
                type: 'POST',
                data: {
                    // _token: form.find('input[name="_token"]').val(), // Handled by global AJAX setup
                    representative_id: representativeId
                    // request_id is part of the URL
                },
                success: function(response) {
                    showToast(response.message || 'تم تعيين المندوب بنجاح!');
                    closeModal('assignRepresentativeModal');
                    updateRequestRowUI(requestId, response.request);
                },
                error: function(xhr) {
                    let errorMessage = 'فشل تعيين المندوب.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const parsedError = JSON.parse(xhr.responseText);
                            if (parsedError.message) errorMessage = parsedError.message;
                        } catch (e) { /* ignore */ }
                    }
                    $('#assignError').text(errorMessage).show();
                }
            });
        }

        function submitRejectRequest() {
            const form = $('#rejectReasonForm');
            const requestId = $('#reject_request_id').val();
            const rejectionReason = $('#rejection_reason_text').val();

            if (!rejectionReason.trim()) {
                $('#rejectError').text('الرجاء إدخال سبب الرفض.').show();
                return;
            }
            $('#rejectError').hide();

            $.ajax({
                url: "{{ url('admin/requests') }}/" + requestId + "/reject",
                type: 'POST',
                data: {
                    // _token: form.find('input[name="_token"]').val(), // Handled by global AJAX setup
                    rejection_reason: rejectionReason
                },
                success: function(response) {
                    showToast(response.message || 'تم رفض الطلب بنجاح!');
                    closeModal('rejectReasonModal');
                    updateRequestRowUI(requestId, response.request);
                },
                error: function(xhr) {
                    let errorMessage = 'فشل رفض الطلب.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                         try {
                            const parsedError = JSON.parse(xhr.responseText);
                            if(parsedError.message) errorMessage = parsedError.message;
                        } catch(e) { /* ignore */ }
                    }
                    $('#rejectError').text(errorMessage).show();
                }
            });
        }

        function markAsComplete(requestId) {
            if (!confirm('هل أنت متأكد من أنك تريد وضع علامة على هذا الطلب كمكتمل؟')) {
                return;
            }
            $.ajax({
                url: "{{ url('admin/requests') }}/" + requestId + "/complete",
                type: 'POST',
                // data: { _token: $('meta[name="csrf-token"]').attr('content') }, // Handled by global AJAX setup
                success: function(response) {
                    showToast(response.message || 'تم إكمال الطلب بنجاح!');
                    updateRequestRowUI(requestId, response.request);
                },
                error: function(xhr) {
                    let errorMessage = 'فشل إكمال الطلب.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    showToast(errorMessage, true);
                }
            });
        }

        // --- UI Update Function ---
        function updateRequestRowUI(requestId, requestData) {
            const row = $('#request-row-' + requestId);
            if (!row.length) return;

            row.attr('data-status', requestData.status);
            const actionsCell = row.find('.actions-cell');
            actionsCell.empty();

            let representativeName = '';
            if (requestData.representative && requestData.representative.name) {
                representativeName = ` <small>(مندوب: ${requestData.representative.name})</small>`;
            }
             let rejectionReasonText = '';
            if (requestData.rejection_reason) {
                // Simple substring, you might want a more sophisticated truncate
                const shortReason = requestData.rejection_reason.length > 20 ? requestData.rejection_reason.substring(0, 17) + '...' : requestData.rejection_reason;
                rejectionReasonText = ` <small>(سبب: ${shortReason})</small>`;
            }


            if (requestData.status === 'in-progress') {
                actionsCell.append(`<span class="status-label status-in-progress">قيد التنفيذ</span>${representativeName}`);
                actionsCell.append(` <button class="btn-complete" onclick="markAsComplete(${requestId})">إكمال</button>`);
                actionsCell.append(` <button class="btn btn-danger btn-reject" onclick="openRejectModal(${requestId})">رفض</button>`);
            } else if (requestData.status === 'completed') {
                actionsCell.append('<span class="status-label status-completed">مكتمل</span>');
            } else if (requestData.status === 'rejected') {
                actionsCell.append(`<span class="status-label status-rejected">مرفوض</span>${rejectionReasonText}`);
            } else if (requestData.status === 'pending') {
                actionsCell.append(`<button class="btn btn-success btn-accept" onclick="openAssignModal(${requestId})">قبول</button>`);
                actionsCell.append(` <button class="btn btn-danger btn-reject" onclick="openRejectModal(${requestId})">عدم القبول</button>`);
            } else { // Fallback for any other status
                 actionsCell.append(`<span class="status-label status-pending">${requestData.status}</span>`); // Generic status display
                 actionsCell.append(` <button class="btn btn-success btn-accept" onclick="openAssignModal(${requestId})">قبول</button>`);
                 actionsCell.append(` <button class="btn btn-danger btn-reject" onclick="openRejectModal(${requestId})">عدم القبول</button>`);
            }

            // Update summary counts (Simplified: just refresh the page or make another AJAX call to get counts)
            // For a more sophisticated update, you'd adjust counts directly.
            // location.reload(); // Simplest way, but not ideal UX for single updates.
        }

        // --- Document Ready ---
        $(document).ready(function() {
            console.log("Document is ready. jQuery version: ", $.fn.jquery);

            // Setup CSRF token for all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#toggleFiltersBtn').on('click', function() {
                $('#advancedFiltersPanel').slideToggle();
                $(this).find('.fa-chevron-down, .fa-chevron-up').toggleClass('fa-chevron-down fa-chevron-up');
            });

            // Close modal if clicked outside content (optional)
            $('.modal').on('click', function(event) {
                if ($(event.target).is('.modal')) {
                    closeModal($(this).attr('id'));
                }
            });
        });
    </script>
@endsection