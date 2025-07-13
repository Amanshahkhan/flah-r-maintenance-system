@extends('admin.layouts.app')

@section('content')
<main class="admin-rep__main-content">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">إدارة الممثلين</h1>
        <a href="{{ route('admin.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة ممثل جديد
        </a>
    </header>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>الهاتف</th>
                        <th>المنطقة</th>
                        <th>الحالة</th>
                        <th width="250px">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($representatives as $rep)
                    <tr id="rep-row-{{ $rep->id }}">
                        <td>{{ $rep->name }}</td>
                        <td>{{ $rep->email }}</td>
                        <td>{{ $rep->phone }}</td>
                        <td>{{ $rep->region }}</td>
                        <td>
                            <span class="status-label {{ $rep->activated_at ? 'active' : 'inactive' }}">
                                {{ $rep->activated_at ? 'نشط' : 'غير نشط' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.edit', $rep->id) }}" class="btn btn-sm btn-info">تعديل</a>
                            <button class="btn btn-sm {{ $rep->activated_at ? 'btn-warning' : 'btn-success' }} toggle-status-btn" data-id="{{ $rep->id }}">
                                {{ $rep->activated_at ? 'تعطيل' : 'تفعيل' }}
                            </button>
                            <form method="POST" action="{{ route('admin.destroy', $rep->id) }}" style="display:inline-block;" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">حذف</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">لا يوجد ممثلين مضافين حالياً.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<style>
    .status-label { padding: .25em .6em; font-size: 75%; font-weight: 700; border-radius: .25rem; color: #fff; }
    .status-label.active { background-color: #28a745; }
    .status-label.inactive { background-color: #6c757d; }
    .btn-warning { background-color: #ffc107; color: #212529; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    document.querySelectorAll('.toggle-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const repId = this.dataset.id;
            fetch(`/admin/representatives/${repId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Failed to update status.');
                }
            });
        });
    });
});
</script>
@endsection