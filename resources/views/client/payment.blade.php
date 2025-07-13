
@extends('client.layouts.app')

@section('content')
                <!-- Page 3: Monthly Expenses -->
              <!-- Monthly Expenses Content -->
            <div id="monthly-expenses">
                <header class="page-header">
                    <h1>المصروفات الشهرية</h1>
                </header>

                <div class="expenses-layout">
                    <div class="expenses-main-column">
                        <section class="budget-summary card">
                            <div class="budget-header">
                                <i class="fas fa-chart-pie budget-icon"></i>
                                <h3>ملخص الميزانية - أكتوبر 2023</h3>
                            </div>
                            <div class="budget-figures">
                        @if($contract)
    {{-- This main container will use Flexbox to align the items --}}
    <div class="budget-figures">

        <!-- Card 1: Total Contract Value -->
        <div class="figure-item">
            <i class="fas fa-file-invoice-dollar figure-icon"></i>
            <div class="figure-text">
                <p>القيمة الإجمالية للعقد</p>
                <span class="budget-value total">
                    {{ number_format($contract->total_value, 2) }} ريال
                </span>
            </div>
        </div>

        <!-- Card 2: Remaining Value -->
        <div class="figure-item">
            <i class="fas fa-wallet figure-icon"></i>
            <div class="figure-text">
                <p>المتبقي من العقد</p>
                <span class="budget-value remaining">
                    {{ number_format($contract->remaining_value, 2) }} ريال
                </span>
            </div>
        </div>

        <!-- Card 3: Spent Value -->
        <div class="figure-item">
            <i class="fas fa-money-bill-wave figure-icon"></i>
            <div class="figure-text">
                <p>المبلغ المصروف</p>
                <span class="budget-value spent">
                    {{ number_format($contract->total_value - $contract->remaining_value, 2) }} ريال
                </span>
            </div>
        </div>

    </div>
@else
    <div class="alert alert-info">
        لا يوجد عقد مرتبط بحسابك لعرض البيانات المالية.
    </div>
@endif
                              
                            </div>
                            <div class="progress-bar-container">
                                <div class="progress-bar" id="budget-progress" style="width: 70%;">70%</div>
                            </div>
                        </section>

                       
                    </div>

                    <aside class="expenses-sidebar-column">
                        <section class="alerts-section card">
                            <legend><i class="fas fa-bell"></i> التنبيهات الهامة</legend>
                            <div class="alert warning" id="spending-alert" style="display: none;">
                                <i class="fas fa-exclamation-circle alert-icon"></i>
                                <div>
                                    <strong>تنبيه:</strong> أنت تقترب من حد الإنفاق الشهري الخاص بك!
                                </div>
                            </div>
                             <div class="alert danger" id="overspending-alert" style="display:none;">
                                <i class="fas fa-ban alert-icon"></i>
                                <div>
                                    <strong>خطر:</strong> لقد تجاوزت حد الإنفاق الشهري!
                                </div>
                            </div>
                            <div class="alert info" style="display: flex;"> <!-- Default visible -->
                                <i class="fas fa-info-circle alert-icon"></i>
                                <div>
                                    لا توجد تنبيهات جديدة حالياً.
                                </div>
                            </div>
                        </section>
                    </aside>
                </div>
            </div>

@endsection
