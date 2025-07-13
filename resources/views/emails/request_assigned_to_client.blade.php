<!DOCTYPE html>
<html>
<head>
    <title>تحديث طلب الصيانة</title>
</head>
<body dir="rtl" style="font-family: sans-serif;">
    <h2>مرحباً {{ $request->user->name }}،</h2>
    <p>تمت الموافقة على طلب الصيانة الخاص بك (رقم {{ $request->id }}) وهو الآن قيد التنفيذ.</p>
    <p>لقد قمنا بتعيين الممثل <strong>{{ $request->representative->name }}</strong> لخدمتك. يمكنك توقع تواصل منه قريباً.</p>
    <p>شكراً لاستخدامك خدماتنا.</p>
    <a href="{{ route('client.view_request', $request->id) }}" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;">
        عرض تفاصيل الطلب
    </a>
</body>
</html>