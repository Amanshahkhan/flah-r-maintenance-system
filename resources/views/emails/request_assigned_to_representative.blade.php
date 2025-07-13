<!DOCTYPE html>
<html>
<head>
    <title>مهمة جديدة</title>
</head>
<body dir="rtl" style="font-family: sans-serif;">
    <h2>مرحباً {{ $request->representative->name }}،</h2>
    <p>لقد تم تعيين طلب صيانة جديد لك.</p>
    <p><strong>تفاصيل المهمة:</strong></p>
    <ul>
        <li>رقم الطلب: {{ $request->id }}</li>
        <li>اسم العميل: {{ $request->user->name }}</li>
        <li>رقم هاتف العميل: {{ $request->user->mobile }}</li>
        <li>المنطقة: {{ $request->user->region }}</li>
    </ul>
    <p>الرجاء مراجعة تفاصيل الطلب الكاملة في لوحة التحكم الخاصة بك.</p>
    <a href="{{ route('representative.view_request', $request->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #17a2b8; color: white; text-decoration: none; border-radius: 5px;">
        عرض تفاصيل المهمة
    </a>
</body>
</html>