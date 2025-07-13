{{-- File: resources/views/admin/requests_list.blade.php --}}

@extends('admin.layouts.app')

@section('content')
<!-- Dashboard Client Content -->
<div id="dashboard-client">
    <header class="page-header">
        {{-- The page title is now dynamic, passed from the controller --}}
        <h1>{{ $pageTitle ?? 'طلبات الصيانة' }}</h1>
    </header>
        
    <!-- Summary Cards Section (Now linked on all pages) -->
  <section class="summary-cards-section">
     <a href="{{ route('admin.requests_admin') }}" style="text-decoration: none; color: inherit;">
    <div class="summary-card" data-status-filter="all">
        <div class="card-content">
            <h3>جميع الطلبات</h3>
            {{-- It's better to use the specific count variable from the controller --}}
            <p class="count" id="countAll">{{ $allCount ?? $requests->count() }}</p>
        </div>
        <div class="card-icon"><i class="fas fa-list-alt"></i></div>
    </div>
</a>
        
        <a href="{{ route('admin.request_progress') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card" data-status-filter="in-progress">
                <div class="card-content">
                    <h3>قيد التنفيذ</h3>
                    <p class="count">{{ $inProgressCount }}</p>
                </div>
                <div class="card-icon icon-in-progress"><i class="fas fa-cogs"></i></div>
            </div>
        </a>

        <a href="{{ route('admin.request_completed') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card" data-status-filter="completed">
                <div class="card-content">
                    <h3>مكتملة</h3>
                    <p class="count">{{ $completedCount }}</p>
                </div>
                <div class="card-icon icon-completed"><i class="fas fa-check-circle"></i></div>
            </div>
        </a>

        <a href="{{ route('admin.request_rejects') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card" data-status-filter="rejected">
                <div class="card-content">
                    <h3>مرفوضة</h3>
                    <p class="count">{{ $rejectedCount }}</p>
                </div>
                <div class="card-icon icon-rejected"><i class="fas fa-times-circle"></i></div>
            </div>
        </a>
    </section>

    <!-- Table Section -->
    <section class="table-section card">
        <div class="card-header">
            {{-- The table title is also dynamic --}}
            <h2>{{ $pageTitle ?? 'قائمة الطلبات' }}</h2>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <div class="table-cell">العميل</div>
                <div class="table-cell">رقم المركبة</div>
                <div class="table-cell">القطع المطلوبة</div>
                <div class="table-cell">المندوب</div>
                <div class="table-cell">الحالة / الإجراءات</div>
            </div>

            @forelse ($requests as $request)
            <div class="table-row" id="request-row-{{ $request->id }}">
                <div class="table-cell">{{ $request->user->name ?? 'غير معروف' }}</div>
                <div class="table-cell">{{ $request->vehicle_number }}</div>
                <div class="table-cell">
                    @if(is_array($request->parts_select))
                        {{ implode('، ', $request->parts_select) }}
                    @else
                        لا يوجد
                    @endif
                </div>
                <div class="table-cell">{{ $request->representative->name ?? 'لم يعين' }}</div>
                
                {{-- ***** THIS IS THE KEY DYNAMIC PART ***** --}}
                <div class="table-cell actions-cell">
                    @if($request->status == 'pending')
                        <button class="btn btn-success btn-accept" onclick="openAssignModal({{ $request->id }})">قبول</button>
                        <button class="btn btn-danger btn-reject" onclick="openRejectModal({{ $request->id }})">رفض</button>
                    @elseif($request->status == 'in-progress')
                        <span class="status-label status-in-progress">قيد التنفيذ</span>
                        <button class="btn-complete" onclick="markAsComplete({{ $request->id }})">إكمال</button>
                    @elseif($request->status == 'completed')
                        <span class="status-label status-completed">مكتمل</span>
                    @elseif($request->status == 'rejected')
                        <span class="status-label status-rejected">مرفوض</span>
                         @if($request->rejection_reason)
                            <small>(سبب: {{ Str::limit($request->rejection_reason, 20) }})</small>
                        @endif
                    @endif
                </div>
                {{-- ******************************************* --}}

            </div>
            @empty
            <div class="table-row">
                <div class="table-cell" colspan="5" style="text-align: center;">
                    لا توجد طلبات تطابق هذه الحالة.
                </div>
            </div>
            @endforelse
        </div>
    </section>
</div>

<!-- All Modals and Toast HTML here -->
<div id="toastNotification" class="toast"><span id="toastMessage"></span></div>
<!-- (The HTML for your assign and reject modals would go here if needed, but the JS handles them) -->
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Your JS functions (openAssignModal, markAsComplete, etc.) remain the same.
        // The `updateRequestRowUI` function will now be very useful.
        // On pages that are filtered, when an action is taken, the row should disappear.
        
        function showToast(message, isError = false) { /* ... same as before ... */ }
        function openAssignModal(requestId) { /* ... same as before ... */ }
        function openRejectModal(requestId) { /* ... same as before ... */ }
        function submitAssignRepresentative() { /* ... same as before ... */ }
        function submitRejectRequest() { /* ... same as before ... */ }

        function markAsComplete(requestId) {
            if (!confirm('هل أنت متأكد من أنك تريد وضع علامة على هذا الطلب كمكتمل؟')) return;
            $.ajax({
                url: `{{ url('admin/requests') }}/${requestId}/complete`,
                type: 'POST',
                success: function(response) {
                    showToast(response.message || 'تم إكمال الطلب بنجاح!');
                    // Fade out and remove the row from the list
                    $('#request-row-' + requestId).fadeOut(500, function() { $(this).remove(); });
                    // Here you would ideally update the counts in the header via JS
                },
                error: function(xhr) { /* ... same error handling ... */ }
            });
        }
        
        function updateRequestRowUI(requestId, requestData) {
            // This function from your original file is perfect for this.
            // When an item's status changes, it should be removed from a filtered view.
             $('#request-row-' + requestId).fadeOut(500, function() { $(this).remove(); });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
            });
        });
    </script>
@endsection