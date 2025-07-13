 @extends('admin.layouts.app')

  @section('content')
 


            <!-- Dashboard Client Content -->
            <div id="dashboard-client">
                <header class="page-header">
                    <h1>طلبات الصيانة</h1>
                </header>
                    
            <!-- Summary Cards -->
            <section class="summary-cards-section">
        <a href="{{ route('admin.requests_admin') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card"><div class="card-content"><h3>جميع الطلبات</h3><p class="count">{{ $allCount ?? 0 }}</p></div><div class="card-icon"><i class="fas fa-list-alt"></i></div></div>
        </a>
        <a href="{{ route('admin.request_progress') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card"><div class="card-content"><h3>قيد التنفيذ</h3><p class="count">{{ $inProgressCount ?? 0 }}</p></div><div class="card-icon icon-in-progress"><i class="fas fa-cogs"></i></div></div>
        </a>
        <a href="{{ route('admin.request_completed') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card"><div class="card-content"><h3>مكتملة</h3><p class="count">{{ $completedCount ?? 0 }}</p></div><div class="card-icon icon-completed"><i class="fas fa-check-circle"></i></div></div>
        </a>
        <a href="{{ route('admin.request_rejects') }}" style="text-decoration: none; color: inherit;">
            <div class="summary-card"><div class="card-content"><h3>مرفوضة</h3><p class="count">{{ $rejectedCount ?? 0 }}</p></div><div class="card-icon icon-rejected"><i class="fas fa-times-circle"></i></div></div>
        </a>
    </section>

            <!-- Table Section -->
            <section class="table-section card">
                <div class="card-header">
                    <h2>قائمة الطلبات</h2>
                    <div class="table-actions">

                        <button class="btn btn-outline" id="toggleFiltersBtn">
                            <i class="fas fa-filter"></i> فلاتر إضافية <i class="fas fa-chevron-down"></i>
                        </button>
                        <!-- <button class="btn btn-primary"> 
                            <i class="fas fa-file-alt"></i> إنشاء تقرير
                        </button> -->
                    </div>
                </div>
                <div class="advanced-filters" id="advancedFiltersPanel" style="display: none;"> <!-- Initially hidden -->
                    <div class="filter-group">
                        <label for="dateRangeStart">النطاق الزمني:</label>
                        <input type="date" id="dateRangeStart" class="form-control">
                        <span>إلى</span>
                        <input type="date" id="dateRangeEnd" class="form-control">
                    </div>
                    <div class="filter-group">
                        <label for="priorityFilter">الأولوية:</label>
                        <select id="priorityFilter" class="form-control">
                            <option value="">جميع الأولويات</option>
                            <option value="high">عالية</option>
                            <option value="medium">متوسطة</option>
                            <option value="low">منخفضة</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="clientFilter">العميل:</label>
                        <select id="clientFilter" class="form-control">
                            <option value="">جميع العملاء</option>
                            <!-- Options will be populated by JS -->
                        </select>
                    </div>
                    <button class="btn btn-secondary" id="applyAdvancedFilters">تطبيق الفلاتر</button>
                    <button class="btn btn-link" id="clearAdvancedFilters">مسح الفلاتر</button>
                </div>
        <section>
      <div class="table-container">
<div class="table-header">
    <div class="table-cell">رقم الهاتف</div>
    <div class="table-cell">العميل</div>
    <div class="table-cell">رقم الإقامة</div>
    <div class="table-cell">رقم المركبة</div>
    <div class="table-cell">موديل المركبة</div>
    <div class="table-cell">القطع المطلوبة</div>
    <div class="table-cell">التاريخ</div>
    <div class="table-cell">المندوب</div> <!-- New Column -->
    <div class="table-cell">عرض</div>
    <div class="table-cell">الإجراءات</div>
</div>

@foreach ($requests as $request)
<div class="table-row">
    <div class="table-cell">{{ $request->user->phone ?? 'غير معروف' }}</div>
    <div class="table-cell">{{ $request->user->name ?? 'غير معروف' }}</div>
    <div class="table-cell">{{ $request->user->iqama ?? 'غير متوفر' }}</div>
    <div class="table-cell">{{ $request->vehicle_number }}</div>
    <div class="table-cell">{{ $request->vehicle_model }}</div>
    <div class="table-cell">
        @php
            $parts = json_decode($request->parts_select, true);
            echo $parts ? implode('، ', $parts) : 'لا يوجد';
        @endphp
    </div>
    <div class="table-cell">{{ $request->created_at->format('Y-m-d') }}</div>
    <div class="table-cell">{{ $request->representative->name ?? 'غير معين' }}</div> <!-- New Column -->
    <div class="table-cell">
        <a href="{{ route('admin.view_request', $request->id) }}" class="btn-view">عرض</a>
    </div>
   <div class="table-cell">
    <button class="btn-accept" style="background-color: red; color: white;">رفض</button>
</div>

</div>
@endforeach


</div>

</section>

        </div>
    </main>
</div>

    <!-- Request Details Modal -->
    <div id="requestDetailsModal" class="modal">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h4 id="modalTitle">تفاصيل طلب الصيانة</h4> <!-- JS changes this -->
                <button class="close-btn" id="closeModalBtn">×</button>
            </div>
            <div class="modal-body" id="modalBodyContent">
                <!-- Details will be populated by JavaScript -->
                <p>جاري تحميل التفاصيل...</p>
            </div>
            <div class="modal-footer" id="modalActionFooter">
                <!-- Dynamic action buttons -->
            </div>
        </div>
    </div>

    <div id="toastNotification" class="toast"> <!-- CSS for RTL positioning -->
        <span id="toastMessage"></span>
    </div>

        <!-- for menu using client js -->
    <script src="../assets/js/client_script.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @endsection