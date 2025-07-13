<aside class="sidebar">
    <div class="logo">
        <h2>نظ<span class="logo-alt">ــام</span> صيانة</h2>
    </div>
    <nav class="nav-menu">
        <ul>
            {{--
                The `Route::is('admin.dashboard')` checks if the current route's name is exactly 'admin.dashboard'.
                The `active` class is only added if this condition is true.
            --}}
            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> <span>لوحة التحكم</span>
                </a>
            </li>

            {{-- 
                For resources like contracts, we use a wildcard `*`.
                `Route::is('admin.contracts*')` will be true for:
                - admin.contracts_admin (your list page)
                - admin.contracts.manage_products (the products page)
                - etc.
            --}}
            <li class="{{ Route::is('admin.contracts*') || Route::is('admin.contracts_admin') ? 'active' : '' }}">
                <a href="{{ route('admin.contracts_admin') }}">
                    <i class="fas fa-file-contract"></i> <span>العقود</span>
                </a>
            </li>
            
            <li class="{{ Route::is('admin.requests*') || Route::is('admin.requests_admin') || Route::is('admin.request_progress') || Route::is('admin.request_completed') || Route::is('admin.request_rejects') ? 'active' : '' }}">
                <a href="{{ route('admin.requests_admin') }}">
                    <i class="fas fa-tools"></i> <span>الطلبات</span>
                </a>
            </li>

            <li class="{{ Route::is('admin.clients*') ? 'active' : '' }}">
                <a href="{{ route('admin.clients.index') }}">
                    <i class="fas fa-users"></i> <span>العملاء</span>
                </a>
            </li>
            
            <li class="{{ Route::is('admin.representatives*') || Route::is('admin.representatives_admin') ? 'active' : '' }}">
                <a href="{{ route('admin.representatives_admin') }}">
                    <i class="fas fa-user-tie"></i> <span>مندوب</span>
                </a>
            </li>

            <!--<li class="{{ Route::is('admin.products*') ? 'active' : '' }}">-->
            <!--    <a href="{{ route('admin.products') }}">-->
            <!--        <i class="fas fa-boxes"></i> <span>المنتجات</span>-->
            <!--    </a>-->
            <!--</li>-->
            
            <!--<li class="{{ Route::is('admin.reports*') ? 'active' : '' }}">-->
            <!--    <a href="{{ route('admin.reports.index') }}">-->
            <!--        <i class="fas fa-chart-bar"></i> <span>التقارير</span>-->
            <!--    </a>-->
            <!--</li>-->
                
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> <span>تسجيل الخروج</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
</aside>