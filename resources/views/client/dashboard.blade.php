@extends('client.layouts.app')

@section('content')


<div id="dashboard-client">
                <header class="page-header">
                    <h1>لوحة التحكم الرئيسية</h1>
                </header>
                <section class="dashboard-overview">
                 
               <div class="welcome-message card">
                 <h3>مرحباً بك، {{ Auth::user()->name }}!</h3>
                 <p>هنا يمكنك إدارة طلبات الصيانة الخاصة بك، تتبع المصروفات، والمزيد.</p>
                 </div>

                   
                </section>

              
    <section class="table-section card">
        <legend><i class="fas fa-list-ul"></i> قائمة الطلبات</legend>
        <table>
            <thead>
                <tr>
                    <th>معرّف الطلب</th>
                    <th>رقم العقد</th>
                    <th>التاريخ</th>
                    <th>سيارة</th>
                    <th>المندوب</th>
                    <th>الحالة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $request)
        

                    <tr>
                        <td data-label="معرّف الطلب">REQ-{{ str_pad($request->id, 3, '0', STR_PAD_LEFT) }}</td>
<td data-label="رقم العقد">
    {{ $request->user && $request->user->contract ? $request->user->contract->contract_number : 'غير متوفر' }}
</td>
                        <td data-label="التاريخ">{{ $request->created_at->format('Y-m-d') }}</td>
                        <td data-label="سيارة">{{ $request->vehicle_model }} - {{ $request->vehicle_number }}</td>

                        {{-- The trigger link remains the same, it has all the data we need --}}
                       {{-- ... --}}
<td data-label="المندوب">
    @if ($request->representative)
        {{-- ✅ THIS IS THE CORRECTED LINK --}}
        <a href="{{ route('client.view_rep_profile', $request->representative->id) }}">
            {{ $request->representative->name }}
        </a>
    @else
        لم يتم التعيين بعد
    @endif
</td>


                        <td data-label="الحالة">
    @if($request->status == 'pending')
        <span style="color: orange; font-weight: bold;">قيد المراجعة</span>
    @elseif($request->status == 'in-progress')
        <span style="color: blue; font-weight: bold;">قيد التنفيذ</span>
    @elseif($request->status == 'completed')
        <span style="color: green; font-weight: bold;">مكتمل</span>
    @elseif($request->status == 'rejected')
        <span style="color: red; font-weight: bold;">مرفوض</span>
    @else
        <span style="color: gray;">{{ $request->status }}</span>
    @endif

                        </td>
                        {{-- ... --}}
<td data-label="الإجراءات">
    {{-- Find this old link: --}}
    {{-- <a href="{{ route('download.pdf', ['id' => $request->id]) }}" class="action-btn view-btn"> --}}
    
    {{-- ✅ REPLACE it with this new link: --}}
    <a href="{{ route('client.view_request', $request->id) }}" class="action-btn view-btn">
        <i class="fas fa-eye"></i> عرض
    </a>
</td>
{{-- ... --}}
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">لا توجد طلبات حتى الآن.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
            </div>
<style>
    /* =================================================================== */
/*  Responsive Table CSS (for mobile screens)                          */
/* =================================================================== */

/* Apply these styles only on screens smaller than 768px */
@media screen and (max-width: 768px) {

    /* Hide the table header on mobile, we will use data-labels instead */
    .table-section table thead {
        display: none;
    }

    /* Make each table row a separate "card" */
    .table-section table tr {
        display: block;
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Make table cells stack vertically */
    .table-section table td {
        display: block;
        text-align: right; /* Align data to the right (good for RTL) */
        padding: 10px;
        padding-right: 40%; /* Make space for the label */
        position: relative;
        border-bottom: 1px solid #eee;
    }

    .table-section table tr td:last-child {
        border-bottom: none; /* Remove border from the last cell in a row */
    }

    /* This is the magic: Create the label from the data-label attribute */
    .table-section table td::before {
        content: attr(data-label); /* Get the text from the data-label */
        position: absolute;
        top: 10px;
        right: 10px; /* Position the label to the right */
        font-weight: bold;
        width: 35%; /* Give the label a fixed width */
        text-align: right;
    }

    /* Adjust styling for status spans */
    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        color: white;
        font-size: 0.9em;
    }
    .status.pending { background-color: orange; }
    .status.in-progress { background-color: blue; }
    .status.completed { background-color: green; }
    .status.rejected { background-color: red; }
}
</style>
            
@endsection