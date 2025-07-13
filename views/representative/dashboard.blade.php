@extends('representative.layouts.representative')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
<style>
    /* You can move these styles to your main representative.css file */
    .main-header h1 { font-weight: 700; color: #333; }
    .main-header p { font-size: 1.1rem; color: #6c757d; }

    /* Summary Cards Styles (no change needed, but included for context) */
    .summary-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
    .summary-card { background-color: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); display: flex; align-items: center; gap: 20px; }
    .summary-card .card-icon { font-size: 2rem; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; }
    .summary-card .card-icon.icon-active { background-color: #007bff; }
    .summary-card .card-icon.icon-completed { background-color: #28a745; }
    .summary-card .card-content h3 { margin: 0 0 5px 0; font-size: 1rem; color: #6c757d; }
    .summary-card .card-content p { margin: 0; font-size: 2.2rem; font-weight: 700; color: #343a40; }

    /* --- ✅ NEW COMPACT LIST DESIGN --- */
    .request-list-card {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 25px rgba(0,0,0,0.07);
        overflow: hidden;
    }
    .request-list-header {
        padding: 20px 25px;
    }
    .request-list-header h2 {
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    /* Grid for the header and rows */
    .request-grid-header, .request-row {
        display: grid;
        /* Columns: ID | Client | Vehicle | Assigned Date | Status | Action */
        grid-template-columns: 80px 1.5fr 1.5fr 1fr 1fr 150px;
        gap: 15px;
        align-items: center;
        padding: 15px 25px;
        text-align: right;
    }

    .request-grid-header {
        font-weight: bold;
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        border-top: 1px solid #e9ecef;
        font-size: 0.9rem;
        color: #6c757d;
        text-transform: uppercase;
    }
    
    .request-row {
        border-bottom: 1px solid #f1f1f1;
        transition: background-color 0.2s;
    }
    .request-row:last-child {
        border-bottom: none;
    }
    .request-row:hover {
        background-color: #fbfdff;
    }
    
    .request-row .value {
        font-weight: 500;
        color: #333;
    }
    .request-row .sub-value {
        font-size: 0.85em;
        color: #777;
        display: block; /* Make it appear on a new line */
    }

    .status-badge {
        padding: 5px 15px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        color: #fff;
        display: inline-block;
    }
    .status-badge.in-progress { background-color: #ffc107; color: #333; }
    .status-badge.Assigned { background-color: #17a2b8; }

    /* Responsive Design for Mobile */
    @media (max-width: 768px) {
        .request-grid-header {
            display: none; /* Hide the header on mobile */
        }
        .request-row {
            grid-template-columns: 1fr; /* Stack everything in one column */
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            margin: 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .request-row .grid-cell {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #f0f0f0;
        }
        .request-row .grid-cell:last-child {
            border-bottom: none;
        }
        /* Add the label back using ::before pseudo-element */
        .request-row .grid-cell::before {
            content: attr(data-label);
            font-weight: bold;
            color: #555;
            margin-left: 10px;
        }
        .request-row .actions-cell {
            justify-content: center;
            padding-top: 15px;
        }
        .request-row .actions-cell::before {
            content: ""; /* No label for the action button */
        }
        .action-btn.view {
            width: 100%;
            text-align: center;
        }
    }
</style>

<!-- Header with Welcome Message -->
<header class="main-header">
    <h1>مرحباً بك، {{ $representative->name }}</h1>
    <p>هنا ملخص لمهامك الحالية. نتمنى لك يوماً موفقاً!</p>
</header>

<!-- Summary Cards Section -->
<section class="summary-cards">
    <div class="summary-card">
        <div class="card-icon icon-active"><i class="fas fa-cogs"></i></div>
        <div class="card-content">
            <h3>الطلبات النشطة</h3>
            <p>{{ $stats['active_assignments'] }}</p>
        </div>
    </div>
    <div class="summary-card">
        <div class="card-icon icon-completed"><i class="fas fa-calendar-check"></i></div>
        <div class="card-content">
            <h3>أُنجز هذا الشهر</h3>
            <p>{{ $stats['completed_this_month'] }}</p>
        </div>
    </div>
</section>

<!-- Active Assignments List Section -->
<section class="request-list-card">
    <div class="request-list-header">
        <h2>الطلبات المسندة إليك حالياً</h2>
    </div>

    @if($activeRequests->isEmpty())
        <div class="alert-success" style="margin: 25px; border-radius: 8px;">
            <i class="fas fa-check-circle"></i> لا توجد لديك أي طلبات نشطة حالياً. عمل رائع!
        </div>
    @else
        {{-- The Grid Header --}}
        <div class="request-grid-header">
            <div>رقم الطلب</div>
            <div>العميل</div>
            <div>السيارة</div>
            <div>تاريخ التعيين</div>
            <div>الحالة</div>
            <div class="text-end">الإجراء</div>
        </div>

        {{-- The Grid Rows --}}
        @foreach ($activeRequests as $request)
            <div class="request-row">
                <div class="grid-cell" data-label="رقم الطلب">
                    <span class="value">#{{ $request->id }}</span>
                </div>
                <div class="grid-cell" data-label="العميل">
                    <div class="value">{{ $request->user->name }}
                        <span class="sub-value">{{ $request->user->mobile }}</span>
                    </div>
                </div>
                <div class="grid-cell" data-label="السيارة">
                    <div class="value">{{ $request->vehicle_model ?? 'غير محدد' }}
                    </div>
                </div>
                <div class="grid-cell" data-label="تاريخ التعيين">
                    <span class="value">{{ $request->assigned_at ? $request->assigned_at->format('Y/m/d') : 'N/A' }}</span>
                </div>
                <div class="grid-cell" data-label="الحالة">
                    <span class="status-badge {{ str_replace(' ', '-', strtolower($request->status)) }}">
                        @if($request->status == 'in-progress')
                            قيد التنفيذ
                        @elseif($request->status == 'Assigned')
                            تم التعيين
                        @else
                            {{ $request->status }}
                        @endif
                    </span>
                </div>
                <div class="grid-cell actions-cell" data-label="الإجراء">
                     <a href="{{ route('representative.view_request', $request->id) }}" class="action-btn view">
                        <i class="fas fa-eye"></i> عرض
                    </a>
                </div>
            </div>
        @endforeach
    @endif
</section>

@endsection