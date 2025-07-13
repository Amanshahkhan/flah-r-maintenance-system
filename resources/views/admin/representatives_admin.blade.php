@extends('admin.layouts.app')

@section('content')
<style>
    /* Add any specific styles you need here, or in your main CSS file */
    .status-label {
        padding: .25em .6em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25rem;
        color: #fff;
    }
    .status-label.active { background-color: #28a745; /* Green */ }
    .status-label.inactive { background-color: #6c757d; /* Grey */ }
    .btn-warning { background-color: #ffc107; color: #212529; }

    /* ✅ ADDED: Increased padding for table cells to make them bigger */
    .table th, .table td {
        vertical-align: middle;
        padding: 0.9rem; /* Increased from default */
    }
    .actions-group .btn, .actions-group form {
        margin: 0 2px; /* Adds a little space between buttons */
    }
</style>

<!-- Main Content -->
<main class="admin-rep__main-content container-fluid">
    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center mb-4 pt-3">
        <div>
            <h1 class="h3 mb-0">إدارة الممثلين</h1>
            <p class="text-muted mb-0">إدارة ممثلي الخدمة الميدانية وتعييناتهم.</p>
        </div>
        <a href="{{ route('admin.representatives.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة ممثل جديد
        </a>
    </header>

    <!-- Representatives Table Section -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover text-center mb-0" style="direction: rtl;">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد الإلكتروني</th>
                            <th>الهاتف</th>
                            <th>المنطقة</th>
                            <th>الحالة</th>
                            <th style="min-width: 250px;">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($representatives as $rep)
                        <tr id="rep-row-{{ $rep->id }}">
                            <td>{{ $rep->id }}</td>
                            <td>{{ $rep->name }}</td>
                            <td>{{ $rep->email }}</td>
                            <td>{{ $rep->phone }}</td>
                            <td>{{ $rep->region }}</td>
                            <td>
                                <span class="status-label {{ $rep->activated_at ? 'active' : 'inactive' }}">
                                    {{ $rep->activated_at ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td class="actions-group">
                                {{-- Action Buttons --}}
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $rep->id }}" title="عرض التفاصيل">
                                    <i class="fas fa-eye"></i>
                                </button>
                                
                                <button class="btn btn-sm {{ $rep->activated_at ? 'btn-warning' : 'btn-success' }} toggle-status-btn" data-id="{{ $rep->id }}" title="{{ $rep->activated_at ? 'تعطيل' : 'تفعيل' }}">
                                    <i class="fas {{ $rep->activated_at ? 'fa-lock-open' : 'fa-lock' }}"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.representatives.destroy', $rep->id) }}" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">لا يوجد ممثلين مضافين حالياً.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modals (placed outside the main loop for valid HTML) -->
    @foreach ($representatives as $rep)
        <div class="modal fade" id="viewModal{{ $rep->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $rep->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content text-end" dir="rtl">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewModalLabel{{ $rep->id }}">بيانات الممثل: {{ $rep->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 align-items-center">
                           
                            <div class="col-md-8">
                                <p><strong>الاسم:</strong> {{ $rep->name }}</p>
                                <p><strong>البريد الإلكتروني:</strong> {{ $rep->email }}</p>
                                <p><strong>الهاتف:</strong> {{ $rep->phone }}</p>
                                <p><strong>المنطقة:</strong> {{ $rep->region }}</p>
                                <p><strong>العنوان:</strong> {{ $rep->address ?? 'غير محدد' }}</p>
                                <p><strong>المهارات:</strong> {{ $rep->skills ?? 'غير محدد' }}</p>
                                <p><strong>ملاحظات:</strong> {{ $rep->notes ?? 'لا يوجد' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</main>
@endsection

@section('scripts')
{{-- ✅ ADD BOOTSTRAP 5 JS BUNDLE - This is required for modals to work! --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    document.querySelectorAll('.toggle-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const repId = this.dataset.id;
            if (!confirm('هل أنت متأكد من تغيير حالة هذا الممثل؟')) {
                return;
            }

            fetch(`/admin/representatives/${repId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.ok ? res.json() : Promise.reject('Network response was not ok.'))
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Failed to update status.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
});
</script>
@endsection