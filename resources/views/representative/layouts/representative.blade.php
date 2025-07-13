<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'لوحة تحكم المندوب')</title>
    
    {{-- Google Fonts for a clean look --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    
    {{-- Font Awesome for Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Link to your custom CSS file --}}
      <link rel="stylesheet" href="{{asset('css/representative.css') }}">
</head>
<body>

    <div class="dashboard-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>نظ<span class="logo-alt">ــام</span> الصيانة</h2>
            </div>
          {{-- ... inside the <nav> element ... --}}
<nav class="sidebar-nav">
    <ul>
        <li class="{{ request()->routeIs('representative.dashboard') ? 'active' : '' }}">
            <a href="{{ route('representative.dashboard') }}"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a>
        </li>
        <li>
            <a href="{{ route('representative.dashboard') }}"><i class="fas fa-tasks"></i> الطلبات المسندة 
               @if(isset($stats['active_assignments']) && $stats['active_assignments'] > 0)
                <span class="badge">{{ $stats['active_assignments'] }}</span>
               @endif
            </a>
        </li>
        {{-- ✅ UPDATED LINKS --}}
        <li class="{{ request()->routeIs('representative.completed_tasks') ? 'active' : '' }}">
            <a href="{{ route('representative.completed_tasks') }}"><i class="fas fa-check-circle"></i> المهام المكتملة</a>
        </li>
        <li class="{{ request()->routeIs('representative.performance') ? 'active' : '' }}">
            <a href="{{ route('representative.performance') }}"><i class="fas fa-chart-line"></i> تقارير الأداء</a>
        </li>
    </ul>
</nav>
{{-- ... --}}
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-button">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

</body>
</html>