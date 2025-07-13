@extends('client.layouts.app')

@section('content')

<div id="request-history">
    <header class="page-header">
        <h1>سجل الطلبات</h1>
    </header>

    <section class="table-section card">
        <legend><i class="fas fa-list-ul"></i> قائمة الطلبات</legend>
        <table>
            <thead>
                <tr>
                    <th>معرّف الطلب</th>
                    <th>رقم العقد</th>
                    <th>التاريخ</th>
                    <th>رقم سيارة </th>
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

            
@endsection



