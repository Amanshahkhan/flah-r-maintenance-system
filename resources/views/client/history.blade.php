@extends('client.layouts.app')

@section('title', 'سجل الطلبات')

@section('content')
<style>
    /* You can move this to your client_style.css file */
    .search-bar-container {
        margin-bottom: 1.5rem;
    }
    .search-input {
        width: 100%;
        max-width: 450px;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
    }
    .search-input:focus {
        border-color: #1abb9c;
        box-shadow: 0 0 0 2px rgba(26, 187, 156, 0.25);
        outline: none;
    }
</style>

<div id="request-history">
    <header class="page-header">
        <h1>سجل الطلبات</h1>
    </header>

    {{-- ✅ 1. ADD THE SEARCH BAR --}}
    <section class="search-bar-container">
        <input type="text" id="requestSearch" class="search-input" placeholder="بحث برقم الطلب، رقم اللوحة، التاريخ، أو الحالة...">
    </section>

    <section class="table-section card">
        <legend><i class="fas fa-list-ul"></i> قائمة الطلبات</legend>
        <div class="table-responsive">
            <table id="requestsTable">
                <thead>
                    <tr>
                        <th>معرّف الطلب</th>
                        <th>رقم العقد</th>
                        <th>التاريخ</th>
                        <th>رقم السيارة</th>
                        <th>المندوب</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                    {{-- ✅ 2. ADD THE DATA ATTRIBUTE FOR SEARCHING --}}
                    <tr data-search-term="{{ strtolower(
                        'REQ-' . str_pad($request->id, 3, '0', STR_PAD_LEFT) . ' ' .
                        ($request->user?->contract?->contract_number ?? '') . ' ' .
                        $request->created_at->format('Y-m-d') . ' ' .
                        $request->vehicle_model . ' ' . $request->vehicle_number . ' ' .
                        ($request->representative->name ?? 'لم يتم التعيين') . ' ' .
                        $request->status
                    ) }}">
                        <td data-label="معرّف الطلب">REQ-{{ str_pad($request->id, 3, '0', STR_PAD_LEFT) }}</td>
                        <td data-label="رقم العقد">{{ $request->user?->contract?->contract_number ?? 'غير متوفر' }}</td>
                        <td data-label="التاريخ">{{ $request->created_at->format('Y-m-d') }}</td>
                        <td data-label="سيارة">{{ $request->vehicle_model }} - {{ $request->vehicle_number }}</td>
                        <td data-label="المندوب">
                            @if ($request->representative)
                                <a href="{{ route('client.view_rep_profile', $request->representative->id) }}">
                                    {{ $request->representative->name }}
                                </a>
                            @else
                                لم يتم التعيين بعد
                            @endif
                        </td>
                        <td data-label="الحالة">
                            @if($request->status == 'pending')
                                <span style="color: orange; font-weight: bold;">قيد المراجعة</span>
                            @elseif($request->status == 'in-progress')
                                <span style="color: blue; font-weight: bold;">قيد التنفيذ</span>
                            @elseif($request->status == 'completed')
                                <span style="color: green; font-weight: bold;">مكتمل</span>
                            @elseif($request->status == 'rejected')
                                <span style="color: red; font-weight: bold;">مرفوض</span>
                            @else
                                <span style="color: gray;">{{ $request->status }}</span>
                            @endif
                        </td>
                        <td data-label="الإجراءات">
                            <a href="{{ route('client.view_request', $request->id) }}" class="action-btn view-btn">
                                <i class="fas fa-eye"></i> عرض
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">لا توجد طلبات حتى الآن.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@push('scripts')
{{-- ✅ 3. ADD THE JAVASCRIPT FOR THE FILTER --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('requestSearch');
    const tableRows = document.querySelectorAll('#requestsTable tbody tr');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase().trim();

            tableRows.forEach(row => {
                // Ensure the row has the data-search-term attribute before trying to access it
                const rowSearchData = row.dataset.searchTerm || '';
                
                // If the row's data includes the search term, show it. Otherwise, hide it.
                if (rowSearchData.includes(searchTerm)) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        });
    }
});
</script>
@endpush