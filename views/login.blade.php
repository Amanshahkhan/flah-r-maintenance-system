<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <!--  Essential for responsive design -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>تسجيل الدخول</title>
  
  <!-- Importing the Tajawal font for a professional look -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
  
  <style>
    /* Using CSS Variables for an easily themeable and professional setup */
    :root {
      --primary-color: #007bff;
      --primary-hover-color: #0056b3;
      --background-color: #f4f7f6;
      --card-background-color: #ffffff;
      --text-color: #333;
      --label-color: #555;
      --border-color: #ddd;
      --border-focus-color: #007bff;
      --error-bg-color: #f8d7da;
      --error-text-color: #721c24;
      --error-border-color: #f5c6cb;
    }

    /* Basic Reset & Body Styling */
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Tajawal', sans-serif;
      direction: rtl;
      background-color: var(--background-color);
      color: var(--text-color);
      /* Center the login form vertically and horizontally */
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      line-height: 1.6;
    }

    /* The main container for the card */
    .login-container {
      width: 100%;
      max-width: 420px;
      padding: 15px;
    }
    
    /* The login card itself */
    .login-card {
      background-color: var(--card-background-color);
      padding: 40px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      border: 1px solid var(--border-color);
    }
    
    .login-card h2 {
      text-align: center;
      margin-bottom: 25px;
      font-weight: 700;
      font-size: 28px;
      color: var(--text-color);
    }

    /* Form Styling */
    .form-group {
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--label-color);
      font-size: 16px;
    }

    .form-control {
      width: 100%;
      padding: 12px 15px;
      font-size: 16px;
      font-family: 'Tajawal', sans-serif;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--border-focus-color);
      box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
    }

    /* Button Styling */
    .btn {
      display: block;
      width: 100%;
      padding: 12px 20px;
      font-size: 18px;
      font-weight: 700;
      font-family: 'Tajawal', sans-serif;
      color: #fff;
      background-color: var(--primary-color);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.2s ease-in-out;
      margin-top: 10px;
    }

    .btn:hover {
      background-color: var(--primary-hover-color);
    }

    /* Error Message Styling */
    .alert-error {
      color: var(--error-text-color);
      background-color: var(--error-bg-color);
      border: 1px solid var(--error-border-color);
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 8px;
      text-align: center;
    }
    
    /* Responsive adjustments for smaller screens */
    @media (max-width: 480px) {
        .login-card {
            padding: 30px 20px;
        }
        .login-card h2 {
            font-size: 24px;
        }
    }
  </style>
</head>
<body>

<div class="login-container">
  <div class="login-card">
    <h2>تسجيل الدخول</h2>

    @if (session('error'))
      <div class="alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
      @csrf
      <div class="form-group">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" name="email" id="email" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="password">كلمة المرور</label>
        <input type="password" name="password" id="password" class="form-control" required>
      </div>

      <button type="submit" class="btn">دخول</button>
    </form>
  </div>
</div>

</body>
</html>