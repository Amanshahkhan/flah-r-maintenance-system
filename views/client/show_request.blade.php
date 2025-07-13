@extends('client.layouts.app')

@section('title', 'تفاصيل الطلب #' . $request->id)

@section('content')
<style>
    /* ... your existing styles ... */
    body { font-family: 'DejaVu Sans', 'tajawal', sans-serif; background-color: #f4f7f6; }
    .container { max-width: 850px; margin: 2rem auto; border: 1px solid #e0e0e0; padding: 30px 40px; background: white; }
    .print-button { display: block; width: 200px; margin: 20px auto; padding: 12px; background-color: #1abb9c; color: white; border: none; border-radius: 5px; cursor: pointer; text-align: center; font-size: 16px; }
    
    @media print {
      body { background-color: #fff; }
      .client-layout-header, .client-layout-sidebar, .print-button { display: none !important; }
      .main-content { margin: 0 !important; padding: 0 !important; }
      .container { box-shadow: none; border: none; margin: 0; padding: 0; width: 100%; }
    }
</style>

<div class="container" id="printable-area">
    <div class="header">
        <h1>أمر طلب صيانة</h1>
        <div class="sub-header">
            <span>رقم الطلب: {{ $request->id }}</span> |
            <span>تاريخ الطلب: {{ $request->created_at->format('Y/m/d') }}</span>
        </div>
    </div>
    

 <table class="details-table">
    <tr>
        <th>اسم العميل</th>
        <td>{{ $request->user->name ?? 'غير محدد' }}</td>
    </tr>
    <tr>
        <th>رقم التواصل</th>
        <td>{{ $request->user->mobile ?? 'غير محدد' }}</td>
    </tr>
    <tr>
        <th>رقم اللوحة</th>
        <td>{{ $request->vehicle_number }}</td>
    </tr>
    <tr>
        <th>طراز السيارة</th>
        <td>{{ $request->vehicle_model }}</td>
    </tr>

    {{-- ✅ THIS IS THE NEWLY ADDED ROW --}}
    <tr>
        <th>نوع السيارة</th>
        <td>{{ $request->vehicle_color }}</td>
    </tr>

</table>

   
    <table class="details-table">
        <tr>
            <th>القطع المطلوبة</th>
            <td>
                {{-- ✅ THIS NOW USES THE CORRECT RELATIONSHIP --}}
                @if($request->products->isNotEmpty())
                    <ul>
                        @foreach($request->products as $product)
                            <li>{{ $product->item_description }} (الكمية: {{ $product->pivot->quantity }})</li>
                        @endforeach
                    </ul>
                @else
                    لا يوجد
                @endif
            </td>
        </tr>
    </table>


    <table class="details-table">
        <tr>
            <th>حالة الطلب الحالية</th>
            <td>
                @php
                    $status_text = match($request->status) {
                        'completed' => 'مكتمل', 'in-progress' => 'قيد التنفيذ',
                        'Assigned' => 'تم التعيين', 'Pending Review' => 'بانتظار المراجعة',
                        'rejected' => 'مرفوض', 'pending' => 'قيد الانتظار',
                        default => ucfirst($request->status)
                    };
                    $status_class = 'status-' . str_replace(' ', '-', strtolower($request->status));
                @endphp
                <span class="status-badge {{ $status_class }}">{{ $status_text }}</span>
            </td>
        </tr>
        <tr>
            <th>التكلفة الإجمالية</th>
            <td>{{ number_format($request->total_cost, 2) }} ريال سعودي</td>
        </tr>
        <tr>
            <th>المندوب المسؤول</th>
            {{-- ✅ This will now display correctly because of the controller change --}}
            <td>{{ $request->representative->name ?? 'لم يتم التعيين' }}</td>
        </tr>
    </table>
    
   



    {{-- ✅ PRINT BUTTON IS NOW INSIDE THE CONTAINER --}}
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> طباعة الطلب
    </button>
</div>

@endsection