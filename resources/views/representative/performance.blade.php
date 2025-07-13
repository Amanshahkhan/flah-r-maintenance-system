@extends('representative.layouts.representative')

@section('title', 'تقارير الأداء')

@section('content')

<header class="main-header">
    <h1><i class="fas fa-chart-line"></i> تقارير أدائك</h1>
    <p>نظرة عامة على إنجازاتك ومعدلات الأداء.</p>
</header>

<section class="summary-cards">
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-check-double"></i>
        </div>
        <div class="card-content">
            <h3>إجمالي المهام المنجزة</h3>
            <p>{{ $stats['total_completed'] }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <div class="card-content">
            <h3>إجمالي قيمة الأعمال</h3>
            <p>{{ number_format($stats['total_value'], 2) }} <span style="font-size: 1rem;">ريال</span></p>
        </div>
    </div>
    <div class="card">
        <div class="card-icon">
            <i class="fas fa-history"></i>
        </div>
        <div class="card-content">
            <h3>متوسط وقت الإنجاز</h3>
            <p>{{ number_format($stats['avg_completion_time_days'], 1) }} <span style="font-size: 1rem;">أيام</span></p>
        </div>
    </div>
</section>

@endsection