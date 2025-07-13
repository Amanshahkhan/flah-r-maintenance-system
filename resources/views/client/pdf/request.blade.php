<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلب صيانة</title>
    <style>
        body {
            font-family: 'Amiri', sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #333;
            padding: 8px;
        }
        .header {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>طلب صيانة</h2>
    <table>
        <tr><th class="header">رقم الطلب</th><td>REQ-{{ str_pad($request->id, 3, '0', STR_PAD_LEFT) }}</td></tr>
        <tr><th class="header">تاريخ الطلب</th><td>{{ $request->created_at->format('Y-m-d') }}</td></tr>
        <tr><th class="header">رقم المركبة</th><td>{{ $request->vehicle_number }}</td></tr>
        <tr><th class="header">لون المركبة</th><td>{{ $request->vehicle_color }}</td></tr>
        <tr><th class="header">موديل المركبة</th><td>{{ $request->vehicle_model }}</td></tr>
        <tr><th class="header">الأجزاء المطلوبة</th><td>{{ implode(', ', json_decode($request->parts_select)) }}</td></tr>
        <tr><th class="header">أجزاء مضافة يدويًا</th><td>{{ $request->parts_manual ?? 'لا يوجد' }}</td></tr>
        <tr><th class="header">المخول بالصيانة</th><td>{{ $request->authorized_personnel ?? 'لا يوجد' }}</td></tr>
    </table>
</body>
</html>
