@extends('representative.layouts.representative')

@section('title', 'المهام المكتملة')

@section('content')

<header class="main-header">
    <h1><i class="fas fa-check-circle"></i> سجل المهام المكتملة</h1>
    <p>هنا يمكنك مراجعة جميع الطلبات التي قمت بإغلاقها بنجاح.</p>
</header>

<section class="content-table">
    @if($requests->isEmpty())
        <div class="alert-info">
            لم تقم بإكمال أي مهام بعد.
        </div>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>السيارة</th>
                        <th>التكلفة الإجمالية</th>
                        <th>تاريخ الإكمال</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td data-label="رقم الطلب">#{{ $request->id }}</td>
                            <td data-label="العميل">{{ $request->user->name }}</td>
                            <td data-label="السيارة">{{ $request->vehicle_model }}</td>
                            <td data-label="التكلفة">
                                {{ number_format($request->total_cost, 2) }} ريال
                            </td>
                            <td data-label="تاريخ الإكمال">
                                {{ $request->completed_at->format('Y-m-d') }}
                            </td>
                            <td data-label="الإجراءات">
                                <a href="{{ route('representative.view_request', $request->id) }}" class="action-btn view">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Links --}}
        <div class="pagination-container">
            {{ $requests->links() }}
        </div>
    @endif
</section>

<style>
.pagination-container {
    padding: 1.5rem;
    display: flex;
    justify-content: center;
}
.alert-info {
    text-align: center;
    padding: 2rem;
    font-size: 1.1rem;
    color: #0c5460;
    background-color: #d1ecf1;
    border: 1px solid #bee5eb;
    border-radius: 5px;
    margin: 1.5rem;
}
</style>

@endsection