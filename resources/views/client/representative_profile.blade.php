{{-- You can extend your main client layout if you want the header/footer --}}
@extends('client.layouts.app') 

@section('title', 'الملف الشخصي للمندوب: ' . $representative->name)

@section('content')
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <title>الملف الشخصي لـ {{ $representative->name }}</title>
  <style>
    body {
      font-family: 'Tahoma', 'Tajawal', sans-serif;
      background-color: #f4f7f9;
      line-height: 1.8;
      margin: 0;
      padding: 0;
    }
    .profile-container {
      padding: 40px 20px;
    }
    .profile-card {
      max-width: 800px;
      background-color: #fff;
      margin: auto;
      border-radius: 10px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      padding: 30px;
      position: relative;
    }
  .back-button {
  background-color: #0d6efd;
  color: #fff;
  border: none;
  border-radius: 5px;
  padding: 10px 20px;
  cursor: pointer;
  font-size: 16px;
  margin-bottom: 20px;
  transition: background-color 0.3s ease;
}
.back-button:hover {
  background-color: #0b5ed7;
}
    .profile-header {
      text-align: center;
      padding-bottom: 20px;
      border-bottom: 1px solid #e9ecef;
      margin-bottom: 25px;
    }
    .profile-header h2 {
      font-size: 28px;
      margin: 0;
      color: #212529;
    }
    .profile-header p {
      font-size: 16px;
      color: #6c757d;
      margin-top: 8px;
    }
    .info-section {
      display: flex;
      justify-content: space-between;
      margin-bottom: 15px;
      font-size: 16px;
    }
    .info-title {
      font-weight: bold;
      color: #495057;
    }
    .info-value {
      color: #212529;
    }
    .section-title {
      font-size: 18px;
      font-weight: bold;
      margin-top: 30px;
      margin-bottom: 15px;
      border-bottom: 2px solid #0d6efd;
      padding-bottom: 8px;
      color: #0d6efd;
    }
    .skills-container .tag {
      background-color: #e9ecef;
      color: #495057;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 14px;
      display: inline-block;
      margin: 5px 5px 0 0;
    }
  </style>
</head>
<body>

<div class="profile-container">
  <div class="profile-card">
    
    <!-- Back button for mobile -->
<button class="back-button" onclick="window.history.back();">  →</button>

    <!-- Header: Name and Title -->
    <div class="profile-header">
      <h2>{{ $representative->name }}</h2>
      <p>مندوب صيانة معتمد</p>
    </div>

    <!-- Contact Info -->
    <div class="info-section">
      <span class="info-title">البريد الإلكتروني:</span>
      <span class="info-value">{{ $representative->email }}</span>
    </div>
    <div class="info-section">
      <span class="info-title">رقم الهاتف:</span>
      <span class="info-value">{{ $representative->phone }}</span>
    </div>
    <div class="info-section">
      <span class="info-title">المنطقة:</span>
      <span class="info-value">{{ $representative->region }}</span>
    </div>
    <div class="info-section">
      <span class="info-title">العنوان:</span>
      <span class="info-value">{{ $representative->address ?? 'غير محدد' }}</span>
    </div>

    <!-- Skills -->
    @if($representative->skills)
      <div class="section-title">المهارات</div>
      <div class="skills-container">
        @foreach(explode(',', $representative->skills) as $skill)
          <span class="tag">{{ trim($skill) }}</span>
        @endforeach
      </div>
    @endif

    <!-- Notes -->
    @if($representative->notes)
      <div class="section-title">ملاحظات</div>
      <p>{{ $representative->notes }}</p>
    @endif

  </div>
</div>

</body>
</html>
@endsection
