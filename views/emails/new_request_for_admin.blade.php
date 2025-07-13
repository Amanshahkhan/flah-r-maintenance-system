<!DOCTYPE html>
<html>
<head>
    <title>طلب صيانة جديد</title>
</head>
<body dir="rtl" style="font-family: sans-serif;">
    <h2>مرحباً أيها المدير،</h2>
    <p>لقد تم استلام طلب صيانة جديد من العميل: <strong>{{ $request->user->name }}</strong>.</p>
    <p><strong>تفاصيل الطلب:</strong></p>
    <ul>
        <li>رقم الطلب: {{ $request->id }}</li>
        <li>رقم المركبة: {{ $request->vehicle_number }}</li>
        <li>القطع المطلوبة: {{ implode(', ', $request->parts_select) }}</li>
    </ul>
    <p>الرجاء مراجعة الطلب في لوحة التحكم وتعيين ممثل له.</p>
    <a href="{{ route('admin.view_request', $request->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
        عرض الطلب
    </a>
</body>
</html>