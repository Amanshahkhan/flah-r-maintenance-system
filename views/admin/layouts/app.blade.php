<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>لوحة تحكم المشرف</title> <!-- Or whatever title -->
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_style2.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&display=swap" rel="stylesheet">
    @yield('styles') <!-- For page-specific styles -->
</head>
<body>
    <div class="dashboard-container">
        @include('admin.layouts.sidebar')

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <!-- Core JS libraries FIRST -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Add other global JS libraries here if needed (e.g., Bootstrap JS) -->

    <!-- Page-specific scripts LAST -->
    @yield('scripts')
    {{-- Or if you use stacks: @stack('scripts') --}}
  @stack('scripts')
</body>
</html>



