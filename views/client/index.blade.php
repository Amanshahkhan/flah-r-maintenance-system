<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>تسجيل الدخول / التسجيل</title>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Tajawal', sans-serif;
      direction: rtl;
      background: #f5f5f5;
    }
    .container {
      width: 100%;
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input {
      width: 100%;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }
    button {
      width: 100%;
      padding: 10px;
      background-color: #1976d2;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
    .form-links, .toggle-link {
      display: block;
      margin-top: 10px;
      text-align: center;
      cursor: pointer;
      color: #1976d2;
    }
    .hidden {
      display: none;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Login -->
  <div id="loginForm">
    <form method="POST" action="{{ route('do.login') }}">
      @csrf
      <h2>تسجيل الدخول</h2>
      @if($errors->any())
        <div style="color:red;">{{ $errors->first() }}</div>
      @endif
      <div class="form-group">
        <label for="loginMobile">رقم الجوال</label>
        <input type="text" id="loginMobile" name="mobile" required />
      </div>
      <div class="form-group">
        <label for="loginPassword">كلمة المرور</label>
        <input type="password" id="loginPassword" name="password" required />
      </div>
      <button type="submit">دخول</button>
      <div class="form-links">
        <span onclick="toggleForm()">ليس لديك حساب؟ <strong>سجل الآن</strong></span>
      </div>
    </form>
  </div>

  <!-- Register -->
  <div id="registerForm" class="hidden">
    <form method="POST" action="{{ route('do.register') }}">
      @csrf
      <h2>إنشاء حساب</h2>
      @if($errors->any())
        <div style="color:red;">{{ $errors->first() }}</div>
      @endif
      <div class="form-group">
        <label for="registerName">الاسم الكامل</label>
        <input type="text" id="registerName" name="name" required />
      </div>
      <div class="form-group">
        <label for="registerIqama">رقم الإقامة</label>
        <input type="text" id="registerIqama" name="iqama_number" required />
      </div>
      <div class="form-group">
        <label for="registerMobile">رقم الجوال</label>
        <input type="text" id="registerMobile" name="mobile" required />
      </div>
      <div class="form-group">
        <label for="registerEmail">البريد الإلكتروني</label>
        <input type="email" id="registerEmail" name="email" required />
      </div>
      <div class="form-group">
        <label for="registerPassword">كلمة المرور</label>
        <input type="password" id="registerPassword" name="password" required />
      </div>
      <button type="submit">تسجيل</button>
      <span class="toggle-link" onclick="toggleForm()">لديك حساب؟ تسجيل الدخول</span>
    </form>
  </div>
</div>

<script>
  function toggleForm() {
    document.getElementById('loginForm').classList.toggle('hidden');
    document.getElementById('registerForm').classList.toggle('hidden');
  }
</script>

</body>
</html>
