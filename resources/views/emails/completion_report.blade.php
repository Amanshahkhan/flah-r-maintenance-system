{{-- In resources/views/emails/completion_report.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ملخص طلب الصيانة</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; }
        .details-table { width: 100%; border-collapse: collapse; }
        .details-table th, .details-table td { border: 1px solid #ccc; padding: 8px; text-align: right; }
        .details-table th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    {{-- If the admin added a custom message, display it --}}
    @if($customMessage)
        <p>{{ nl2br(e($customMessage)) }}</p>
        <hr>
    @endif

    <h1>ملخص طلب الصيانة رقم #{{ $maintenanceRequest->id }}</h1>
    
    <h3>بيانات العميل</h3>
    <p><strong>الاسم:</strong> {{ $maintenanceRequest->user->name }}</p>
    <p><strong>الهاتف:</strong> {{ $maintenanceRequest->user->mobile }}</p>

    <h3>تفاصيل الطلب</h3>
    <p><strong>السيارة:</strong> {{ $maintenanceRequest->vehicle_model }} ({{ $maintenanceRequest->vehicle_number }})</p>
    
    <h4>القطع والخدمات:</h4>
    @if($maintenanceRequest->products->isNotEmpty())
        <ul>
            @foreach($maintenanceRequest->products as $product)
                <li>{{ $product->item_description }} (الكمية: {{ $product->pivot->quantity }})</li>
            @endforeach
        </ul>
    @endif

    @if($maintenanceRequest->parts_manual)
        <h4>ملاحظات إضافية:</h4>
        <p>{{ $maintenanceRequest->parts_manual }}</p>
    @endif

    <h3>التكلفة والحالة</h3>
    <p><strong>الحالة:</strong> مكتمل</p>
    <p><strong>التكلفة الإجمالية:</strong> {{ number_format($maintenanceRequest->total_cost, 2) }} ريال</p>
    <p><strong>المندوب:</strong> {{ $maintenanceRequest->representative->name ?? 'N/A' }}</p>

    <br>
    <p>شكراً لتعاملكم معنا.</p>
</body>
</html>