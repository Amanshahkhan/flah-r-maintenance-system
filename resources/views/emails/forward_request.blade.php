<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Tajawal', sans-serif; text-align: right;">
    <h3>تحية طيبة،</h3>
    <p>تم توجيه أمر الصيانة التالي إليك للمراجعة أو للعلم.</p>

    {{-- Display the optional notes if they exist --}}
    @if($notes)
    <div style="border-right: 3px solid #eee; padding-right: 15px; margin: 15px 0;">
        <strong>ملاحظات إضافية من المرسل:</strong>
        <p style="white-space: pre-wrap;">{{ $notes }}</p>
    </div>
    @endif

    <p>يمكنك الاطلاع على تفاصيل الطلب الكاملة في ملف PDF المرفق.</p>
    <br>
    <p>مع خالص التقدير.</p>
</body>
</html>