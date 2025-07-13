<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ \App\Helpers\Arabic::fixPDF('أمر صيانة') }} #{{ $request->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            direction: rtl;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
        }
        .main-table td {
            vertical-align: top;
            padding: 5px;
        }
        h1 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        .header-info {
            text-align: left;
            margin-bottom: 20px;
            font-size: 13px;
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            text-align: right;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding-bottom: 8px;
            text-align: right;
        }
        /* ✅ THIS IS THE KEY CHANGE */
        .info-label {
            font-weight: bold;
            text-align: left; /* Force the label to the left */
            width: 35%;       /* Give it some space */
            padding-left: 10px;
        }
        ul {
            list-style-type: none;
            padding-right: 0;
            margin: 0;
            text-align: right;
        }
        li {
            margin-bottom: 5px;
        }
        .problem-notes {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            text-align: right;
        }
    </style>
</head>
<body>

    <h1>{{ \App\Helpers\Arabic::fixPDF('أمر طلب صيانة') }}</h1>

    <div class="header-info">
        {{ \App\Helpers\Arabic::fixPDF('رقم الطلب:') }} {{ $request->id }} | 
        {{ \App\Helpers\Arabic::fixPDF('تاريخ الطلب:') }} {{ $request->created_at->format('Y/m/d') }}
    </div>

    <table class="main-table">
        <tr>
            {{-- Right Column --}}
            <td style="width: 50%;">
                <div class="section-title">{{ \App\Helpers\Arabic::fixPDF('بيانات العميل والسيارة') }}</div>
                
                <table class="info-table">
                    <tr>
                        <td>{{ $request->user->name ?? '' }}</td>
                        <td class="info-label">{{ \App\Helpers\Arabic::fixPDF('اسم العميل') }}</td>
                    </tr>
                    <tr>
                        <td>{{ $request->user->mobile ?? '' }}</td>
                        <td class="info-label">{{ \App\Helpers\Arabic::fixPDF('رقم التواصل') }}</td>
                    </tr>
                    <tr>
                        <td>{{ \App\Helpers\Arabic::fixPDF($request->vehicle_model) }}</td>
                        <td class="info-label">{{ \App\Helpers\Arabic::fixPDF('طراز السيارة') }}</td>
                    </tr>
                    <tr>
                        <td>{{ \App\Helpers\Arabic::fixPDF($request->vehicle_number) }}</td>
                        <td class="info-label">{{ \App\Helpers\Arabic::fixPDF('رقم اللوحة') }}</td>
                    </tr>
                </table>
            </td>

            {{-- Left Column --}}
            <td style="width: 50%;">
                <div class="section-title">{{ \App\Helpers\Arabic::fixPDF('تفاصيل طلب الصيانة') }}</div>

                @if($request->products && $request->products->isNotEmpty())
                    <strong>{{ \App\Helpers\Arabic::fixPDF('القطع المطلوبة') }}</strong>
                    <ul>
                        @foreach($request->products as $index => $product)
                            <li>{{ $index + 1 }}) {{ \App\Helpers\Arabic::fixPDF($product->item_description) }} ({{ \App\Helpers\Arabic::fixPDF('الكمية') }}: {{ $product->pivot->quantity }})</li>
                        @endforeach
                    </ul>
                @endif

                @if($request->parts_manual)
                <div class="problem-notes">
                    <strong>{{ \App\Helpers\Arabic::fixPDF('الطلب / المشكلة') }}</strong>
                    <p>{{ \App\Helpers\Arabic::fixPDF($request->parts_manual) }}</p>
                </div>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>