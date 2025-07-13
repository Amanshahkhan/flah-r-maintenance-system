<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>بوابة العميل</h2>
        </div>
        <nav class="sidebar-nav">
            <ul>
                {{-- Dashboard Link --}}
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> <span>لوحة التحكم</span>
                    </a>
                </li>
                
                {{-- New Maintenance Request Link --}}
                <li class="{{ request()->routeIs('maintenance.form') ? 'active' : '' }}">
                    <a href="{{ route('maintenance.form') }}" class="nav-link">
                        <i class="fas fa-plus-circle"></i> <span>نموذج طلب صيانة</span>
                    </a>
                </li>

                {{-- Order History Link --}}
                <li class="{{ request()->routeIs('history') ? 'active' : '' }}">
                    <a href="{{ route('history') }}" class="nav-link">
                        <i class="fas fa-history"></i> <span>سجل الطلبات</span>
                    </a>
                </li>

                {{-- Monthly Expenses Link --}}
                <li class="{{ request()->routeIs('payment') ? 'active' : '' }}">
                    <a href="{{ route('payment') }}" class="nav-link">
                        <i class="fas fa-wallet"></i> <span>المصروفات الشهرية</span>
                    </a>
                </li>

                {{-- Logout Link - It's good practice to have it in the main nav --}}
                <li>
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <p>© 2025 Flah-R</p>
        </div>
    </aside>

    {{-- The main content area will go next to the sidebar --}}
    <main class="content-area">
        {{-- Your @yield('content') will be here --}}
    </main>
</div>

{{-- The mobile menu toggle button will be created and injected by JavaScript --}}