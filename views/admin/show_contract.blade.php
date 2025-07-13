<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>نموذج عقد صيانة</title>
  <style>
    body {
      font-family: 'DejaVu Sans', 'Tajawal', sans-serif;
      margin: 30px;
      background-color: #f9f9f9;
    }

    .contract-box {
      max-width: 800px;
      margin: auto;
      background: white;
      border: 1px solid #ccc;
      padding: 30px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
      line-height: 1.8;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .field {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      border-bottom: 1px solid #eee;
      padding-bottom: 8px;
    }

    .field-title {
      font-weight: bold;
      color: #333;
      width: 40%;
    }

    .field-value {
      width: 58%;
      text-align: right;
      color: #444;
    }

    .signature-section {
      display: flex;
      justify-content: space-between;
      margin-top: 40px;
    }

    .signature-box {
      width: 48%;
      text-align: center;
      border-top: 1px solid #000;
      padding-top: 10px;
      font-size: 14px;
    }

    .btn-print {
      margin-top: 30px;
      display: block;
      width: 160px;
      margin-left: auto;
      margin-right: auto;
      padding: 10px;
      background-color: #006699;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-print:hover {
      background-color: #004d73;
    }
  </style>
</head>
<body>

<div class="contract-box">
  <h2>عقد صيانة</h2>

  <div class="field">
    <div class="field-title">رقم العقد:</div>
    <div class="field-value">AC001</div>
  </div>

  <div class="field">
    <div class="field-title">اسم العقد:</div>
    <div class="field-value">عقد صيانة</div>
  </div>

  <div class="field">
    <div class="field-title">تاريخ العقد:</div>
    <div class="field-value">2023-02-12</div>
  </div>

  <div class="field">
    <div class="field-title">تاريخ بداية العقد:</div>
    <div class="field-value">2023-02-12</div>
  </div>

  <div class="field">
    <div class="field-title">قيمة العقد:</div>
    <div class="field-value">15,000 ريال</div>
  </div>

  <div class="field">
    <div class="field-title">المتبقي من العقد:</div>
    <div class="field-value">5,000 ريال</div>
  </div>

  <div class="signature-section">
    <div class="signature-box">توقيع العميل</div>
    <div class="signature-box">توقيع الشركة</div>
  </div>

  <button class="btn-print" onclick="window.print()">طباعة العقد</button>
</div>

</body>
</html>
