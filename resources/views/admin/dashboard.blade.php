@extends('admin.layouts.app')

@section('content')

<body>
   

            <!-- Dashboard Stats Cards -->
           <section class="stats-cards-grid">

    <!-- Card 1: New Requests -->
    <div class="stat-card">
        <div class="stat-card-icon icon-mail">
            <i class="fas fa-envelope-open-text"></i>
        </div>
        <div class="stat-card-info">
            <h4>طلبات جديدة</h4>
         <p class="value" id="countAll">{{ $requests->count() }}</p>
        </div>
    </div>
    
    <!-- Card 2: Total Clients -->
    <div class="stat-card">
        <div class="stat-card-icon icon-truck"> {{-- Changed icon to fas fa-users for clients --}}
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-card-info">
            <h4>إجمالي العملاء</h4>
            {{-- This replaces the static number 150 with the dynamic variable --}}
            <span class="value" id="clientsCount">{{ $clientsCount }}</span>
        </div>
    </div>

</section>

            <section class="performance-chart-section">
                <div class="card">
                    <div class="card-header">
                        <h3>أداء المناطق (الطلبات المكتملة)</h3>
                        <a href="reports_admin.html" class="button-link">عرض التقرير المفصل <i class="fas fa-chart-line fa-xs"></i></a>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="regionalPerformanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/client_script.js"></script>

    <!-- for menu using client js -->
    
    <script>
        // I'm including it here for completeness.
        document.addEventListener('DOMContentLoaded', function () {
            const dashboardContainer = document.querySelector('.dashboard-container');

            // --- Basic Chart.js Example for Regional Performance (Placeholder) ---
            const regionalChartCtx = document.getElementById('regionalPerformanceChart');
            if (regionalChartCtx) {
                new Chart(regionalChartCtx, {
                    type: 'bar',
                    data: {
                        labels: ['المنطقة الشمالية', 'المنطقة الجنوبية', 'المنطقة الشرقية', 'المنطقة الغربية', 'المنطقة الوسطى'],
                        datasets: [{
                            label: 'الطلبات المكتملة',
                            data: [65, 59, 80, 81, 56],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.6)','rgba(20, 184, 166, 0.6)','rgba(139, 92, 246, 0.6)','rgba(245, 158, 11, 0.6)','rgba(16, 185, 129, 0.6)'
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)','rgba(20, 184, 166, 1)','rgba(139, 92, 246, 1)','rgba(245, 158, 11, 1)','rgba(16, 185, 129, 1)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, indexAxis: 'y',
                        scales: {
                            y: { beginAtZero: true, grid: { color: 'rgba(200, 200, 200, 0.2)' }, ticks: { color: '#6B7280', font: { family: 'Tajawal, Cairo, sans-serif' } } },
                            x: { grid: { display: false }, ticks: { color: '#6B7280', font: { family: 'Tajawal, Cairo, sans-serif' } } }
                        },
                        plugins: {
                            legend: { display: true, position: 'top', labels: { color: '#4B5563', font: { family: 'Tajawal, Cairo, sans-serif' } } },
                            tooltip: { backgroundColor: 'rgba(0, 0, 0, 0.7)', titleColor: '#fff', bodyColor: '#fff', titleFont: { family: 'Tajawal, Cairo, sans-serif' }, bodyFont: { family: 'Tajawal, Cairo, sans-serif' } }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>

@endsection