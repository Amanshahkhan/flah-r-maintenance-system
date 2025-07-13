@extends('admin.layouts.app')

@section('content')
<!-- Dashboard Client Content -->
<div id="dashboard-client">
    <header class="page-header">
        <h1>الطلبات قيد التنفيذ</h1>
    </header>
        
    <!-- Summary Cards -->
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
            <h2>قائمة الطلبات قيد التنفيذ</h2>
        </div>
        
        <div class="table-container">
            <div class="table-header">
                <div class="table-cell">العميل</div>
                <div class="table-cell">رقم المركبة</div>
                <div class="table-cell">القطع المطلوبة</div>
                <div class="table-cell">التاريخ</div>
                <div class="table-cell">المندوب المعين</div>
                <div class="table-cell">عرض</div>
                <div class="table-cell">الإجراءات</div>
            </div>

            @forelse ($requests as $request)
            {{-- Added id to the table row for easier selection with jQuery --}}
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
                <div class="table-cell">{{ $request->created_at->format('Y-m-d') }}</div>
                
                <div class="table-cell">{{ $request->representative->name ?? 'غير معين' }}</div>
                
                <div class="table-cell">
                    <a href="{{ route('admin.view_request', $request->id) }}" class="btn-view">عرض</a>
                </div>
                <div class="table-cell">
                    {{-- The button is correct --}}
                    <button class="btn-complete" onclick="markAsComplete({{ $request->id }})">إكمال</button>
                </div>
            </div>
            @empty
            <div class="table-row">
                <div class="table-cell" colspan="7" style="text-align: center;">
                    لا توجد طلبات قيد التنفيذ حالياً.
                </div>
            </div>
            @endforelse
        </div>
    </section>
</div>

<!-- ********* ADD THIS HTML ********* -->
<!-- Toast Notification -->
<div id="toastNotification" class="toast">
    <span id="toastMessage"></span>
</div>
<!-- ************************************ -->

@endsection

{{-- ********* WRAP YOUR SCRIPT IN THIS SECTION ********* --}}
@section('scripts')
    {{-- You probably don't need this if jQuery is loaded in app.blade.php, but it's safe to keep --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        // Your existing script is fine.
        function showToast(message, isError = false) {
            // Find the toast element. Make sure this HTML exists on the page.
            const toast = $('#toastNotification'); 
            $('#toastMessage').text(message);
            toast.addClass('show');
            setTimeout(function() {
                toast.removeClass('show');
            }, 3000);
        }

        function markAsComplete(requestId) {
            if (!confirm('هل أنت متأكد من أنك تريد وضع علامة على هذا الطلب كمكتمل؟')) {
                return;
            }
            $.ajax({
                url: "{{ url('admin/requests') }}/" + requestId + "/complete",
                type: 'POST',
                // CSRF token is handled by the global setup below
                success: function(response) {
                    showToast(response.message || 'تم إكمال الطلب بنجاح!');
                    // When completed on a filtered page, the row should disappear.
                    $('#request-row-' + requestId).fadeOut(500, function() { $(this).remove(); });
                    // TODO: Update the counts in the header.
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

        // Setup CSRF token for all AJAX requests
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
@endsection
