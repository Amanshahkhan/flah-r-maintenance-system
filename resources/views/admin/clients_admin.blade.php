@extends('admin.layouts.app')

@section('content')
<style>
    /* You can move these styles to your main admin CSS file for consistency */
    .page-container { padding: 20px; font-family: 'Tajawal', sans-serif; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e0e0e0; }
    .page-header h1 { font-size: 2rem; font-weight: 700; color: #333; }
    
    .data-card { background-color: #fff; border-radius: 8px; padding: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.07); }
    
    .card-toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .search-input { width: 100%; max-width: 400px; padding: 10px 15px; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; }
    .search-input:focus { border-color: #007bff; box-shadow: 0 0 0 2px rgba(0,123,255,.25); outline: none; }
    
    .data-grid-header, .client-item {
        display: grid;
        grid-template-columns: 50px 1.5fr 1.5fr 1fr 1fr 1fr 200px; 
        gap: 15px;
        align-items: center;
        text-align: right;
    }
    
    .data-grid-header { font-weight: bold; padding: 15px; border-bottom: 2px solid #e9ecef; background-color: #f8f9fa; }
    
    .client-item { padding: 15px; border-bottom: 1px solid #f1f1f1; transition: background-color 0.2s; }
    .client-item:hover { background-color: #fbfbfb; }
    .client-item:last-child { border-bottom: none; }
    
    .actions { display: flex; justify-content: flex-end; }
    .actions .btn { margin-right: 5px; }
    
    .status-label { padding: .3em .7em; font-size: 0.8em; font-weight: 700; border-radius: 50px; color: #fff; }
    .status-label.active { background-color: #28a745; }
    .status-label.inactive { background-color: #6c757d; }
    
    .empty-state { text-align: center; padding: 50px; color: #888; }

    /* Modal Styles */
    .modal { display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); justify-content: center; align-items: center; }
    .modal-content { background-color: #fefefe; margin: auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 500px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
    .modal-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 20px; }
    .modal-header h2, .modal-header h5 { margin: 0; font-size: 1.5rem; }
    .modal-close { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
    .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
    .modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #eee; }
    .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; padding: .75rem 1.25rem; margin-bottom: 1rem; border: 1px solid transparent; border-radius: .25rem; }
    .alert-danger ul { margin: 0; padding-right: 20px; }
</style>

<div class="page-container">
    <header class="page-header">
        <h1>إدارة العملاء</h1>
        <button id="addUserBtn" class="btn btn-primary">
            <i class="fas fa-plus"></i> إضافة عميل جديد
        </button>
    </header>

    <div class="data-card">
        <div class="card-toolbar">
            <input type="text" id="clientSearch" class="search-input" placeholder="ابحث بالاسم، البريد الإلكتروني، أو رقم العقد...">
        </div>

        <div class="data-grid-header">
            <div>#</div>
            <div>الاسم</div>
            <div>البريد الإلكتروني</div>
            <div>رقم العقد</div>
            <div>المنطقة</div>
            <div>الحالة</div>
            <div class="text-end">الإجراءات</div>
        </div>

        <div id="clients-list">
            @forelse ($users as $user)
            <div class="client-item" data-search-term="{{ strtolower($user->name . ' ' . $user->email . ' ' . ($user->contract->contract_number ?? '')) }}" data-row-id="{{ $user->id }}">
                <div>{{ $user->id }}</div>
                <div><strong>{{ $user->name }}</strong></div>
                <div>{{ $user->email }}</div>
                <div>{{ $user->contract->contract_number ?? '---' }}</div>
                <div>{{ $user->region ?? '---' }}</div>
                <div>
                    <span class="status-label {{ $user->email_verified_at ? 'active' : 'inactive' }}">
                        {{ $user->email_verified_at ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
                <div class="actions">
                    <button class="btn btn-sm btn-secondary view-user-btn" data-user-id="{{ $user->id }}" title="عرض التفاصيل"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-info edit-user-btn" data-user-details="{{ $user->toJson() }}" title="تعديل"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm {{ $user->email_verified_at ? 'btn-warning' : 'btn-success' }} toggle-status-btn" data-id="{{ $user->id }}" title="{{ $user->email_verified_at ? 'تعطيل' : 'تفعيل' }}"><i class="fas fa-toggle-on"></i></button>
                    <button class="btn btn-sm btn-danger delete-user-btn" data-id="{{ $user->id }}" title="حذف"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-users fa-3x mb-3"></i>
                <p>لا يوجد عملاء لعرضهم حالياً.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- MODAL FOR ADDING/EDITING USER --}}
<div id="user-register-modal" class="modal">
    <div class="modal-content">
        <header class="modal-header">
            <h2 id="modal-title"></h2>
            <span class="modal-close" data-modal-id="user-register-modal">×</span>
        </header>
        <div class="modal-body">
            <form id="register-user-form" novalidate>
                <input type="hidden" name="_method" id="form-method-field">
                <div id="form-errors" class="alert-danger" style="display:none;"></div>
                <div class="form-group"><label for="name">الاسم الكامل</label><input type="text" id="name" name="name" required></div>
                <div class="form-group"><label for="email">البريد الإلكتروني</label><input type="email" id="email" name="email" required></div>
                <div class="form-group"><label for="contract_number">رقم العقد</label><input type="text" id="contract_number" name="contract_number" placeholder="e.g., C-2024-XYZ-001"></div>
                <div class="form-group"><label for="mobile">رقم الهاتف</label><input type="tel" id="mobile" name="mobile"></div>
                <div class="form-group"><label for="region">المنطقة</label><input type="text" id="region" name="region" placeholder="مثال: الرياض"></div>
                <div class="form-group"><label for="address">العنوان التفصيلي</label><input type="text" id="address" name="address" placeholder="مثال: شارع الملك فهد، حي العليا"></div>
                <div class="form-group"><label for="password">كلمة المرور</label><input type="password" id="password" name="password" required></div>
                <div class="form-group"><label for="password_confirmation">تأكيد كلمة المرور</label><input type="password" id="password_confirmation" name="password_confirmation" required></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modal-cancel" data-modal-id="user-register-modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL FOR VIEWING USER DETAILS (Generated for each user) --}}
@foreach ($users as $user)
<div class="modal" id="viewClientModal{{ $user->id }}">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">بيانات العميل: {{ $user->name }}</h5>
            <span class="modal-close" data-modal-id="viewClientModal{{ $user->id }}">×</span>
        </div>
        <div class="modal-body text-end" dir="rtl">
            <p><strong>الاسم:</strong> {{ $user->name }}</p>
            <p><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
            <p><strong>الهاتف:</strong> {{ $user->mobile ?? 'غير محدد' }}</p>
            <p><strong>المنطقة:</strong> {{ $user->region ?? 'غير محدد' }}</p>
            <p><strong>العنوان:</strong> {{ $user->address ?? 'غير محدد' }}</p>
            <p><strong>رقم العقد:</strong> {{ $user->contract->contract_number ?? 'غير مرتبط بعقد' }}</p>
            <p><strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d') }}</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modal-cancel" data-modal-id="viewClientModal{{ $user->id }}">إغلاق</button>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    // --- SETUP ---
    const csrfToken = '{{ csrf_token() }}';

    // --- HELPER: Universal Toast Notification ---
    const showToast = (message, isError = false) => {
        // You can implement a more sophisticated toast library here
        alert(message);
    };
    
    // --- LIVE SEARCH ---
    const searchInput = document.getElementById('clientSearch');
    if (searchInput) {
        searchInput.addEventListener('keyup', () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            document.querySelectorAll('.client-item').forEach(item => {
                const itemSearchData = item.dataset.searchTerm || '';
                item.style.display = itemSearchData.includes(searchTerm) ? 'grid' : 'none';
            });
        });
    }

    // --- MODAL HANDLING ---
    const modals = document.querySelectorAll('.modal');
    
    // Function to open a specific modal
    const openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'flex';
    };

    // Function to close a specific modal
    const closeModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) modal.style.display = 'none';
    };
    
    // Add listeners to all buttons that open modals
    document.getElementById('addUserBtn').addEventListener('click', () => {
        prepareModalForAdd();
        openModal('user-register-modal');
    });

    document.querySelectorAll('.edit-user-btn').forEach(btn => {
        btn.addEventListener('click', (event) => {
            prepareModalForEdit(event);
            openModal('user-register-modal');
        });
    });

    document.querySelectorAll('.view-user-btn').forEach(btn => {
        btn.addEventListener('click', (event) => {
            const userId = event.currentTarget.dataset.userId;
            openModal(`viewClientModal${userId}`);
        });
    });

    // Add listeners to all buttons/spans that close modals
    document.querySelectorAll('.modal-close, .modal-cancel').forEach(btn => {
        btn.addEventListener('click', (event) => {
            const modalId = event.currentTarget.dataset.modalId;
            closeModal(modalId);
        });
    });

    // Close modal if clicking outside of the content
    window.addEventListener('click', (event) => {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    });


    // --- ACTION HANDLERS ---
    const prepareModalForEdit = (event) => {
        const registerForm = document.getElementById('register-user-form');
        const modalTitle = document.getElementById('modal-title');
        const methodField = document.getElementById('form-method-field');
        const userData = JSON.parse(event.currentTarget.dataset.userDetails);
        
        registerForm.reset();
        modalTitle.textContent = 'تعديل بيانات العميل';
        registerForm.action = `/admin/clients/${userData.id}`;
        methodField.value = 'PATCH';

        document.getElementById('name').value = userData.name || '';
        document.getElementById('email').value = userData.email || '';
        document.getElementById('mobile').value = userData.mobile || '';
        document.getElementById('region').value = userData.region || '';
        document.getElementById('address').value = userData.address || '';
        document.getElementById('contract_number').value = userData.contract ? userData.contract.contract_number : '';
        
        const passInput = document.getElementById('password');
        const passConfirmInput = document.getElementById('password_confirmation');
        passInput.placeholder = 'اتركه فارغاً لعدم التغيير';
        passConfirmInput.placeholder = 'اتركه فارغاً لعدم التغيير';
        passInput.required = false;
        passConfirmInput.required = false;
    };

    const prepareModalForAdd = () => {
        const registerForm = document.getElementById('register-user-form');
        const modalTitle = document.getElementById('modal-title');
        const methodField = document.getElementById('form-method-field');
        
        registerForm.reset();
        modalTitle.textContent = 'تسجيل عميل جديد';
        registerForm.action = "{{ route('admin.clients.store') }}";
        methodField.value = 'POST';
        
        const passInput = document.getElementById('password');
        const passConfirmInput = document.getElementById('password_confirmation');
        passInput.placeholder = '';
        passConfirmInput.placeholder = '';
        passInput.required = true;
        passConfirmInput.required = true;
    };

    // --- FETCH-BASED ACTIONS ---
    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', (event) => {
            const userId = event.currentTarget.dataset.id;
            if (!confirm('هل أنت متأكد من حذف هذا العميل؟')) return;
            
            fetch(`/admin/clients/${userId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) throw new Error(data.message || 'Error deleting user.');
                showToast(data.message);
                event.currentTarget.closest('.client-item').remove();
            })
            .catch(err => showToast(err.message || 'فشل في الاتصال بالخادم.', true));
        });
    });

    document.querySelectorAll('.toggle-status-btn').forEach(btn => {
        btn.addEventListener('click', (event) => {
            const button = event.currentTarget;
            const userId = button.dataset.id;
            const row = button.closest('.client-item');
            const statusLabel = row.querySelector('.status-label');

            if (!confirm('هل أنت متأكد من تغيير حالة هذا العميل؟')) return;

            fetch(`/admin/clients/${userId}/toggle-status`, {
                method: 'PATCH', 
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
            })
            .then(res => res.json().then(data => ({ ok: res.ok, data })))
            .then(({ ok, data }) => {
                if (!ok) throw new Error(data.message || 'Error toggling status.');
                
                showToast(data.message);
                
                if (data.is_active) {
                    statusLabel.textContent = 'نشط';
                    statusLabel.classList.remove('inactive');
                    statusLabel.classList.add('active');
                    button.classList.remove('btn-success');
                    button.classList.add('btn-warning');
                    button.title = 'تعطيل';
                } else {
                    statusLabel.textContent = 'غير نشط';
                    statusLabel.classList.remove('active');
                    statusLabel.classList.add('inactive');
                    button.classList.remove('btn-warning');
                    button.classList.add('btn-success');
                    button.title = 'تفعيل';
                }
            })
            .catch(err => showToast(err.message || 'فشل تغيير الحالة.', true));
        });
    });

    // --- FORM SUBMISSION FOR ADD/EDIT ---
    const registerForm = document.getElementById('register-user-form');
    if(registerForm) {
        registerForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const formData = new FormData(registerForm);
            const submitButton = registerForm.querySelector('button[type="submit"]');
            const formAction = registerForm.action;
            const formMethod = document.getElementById('form-method-field').value;
            
            // For PATCH requests, we need to add _method to FormData
            if (formMethod === 'PATCH') {
                formData.append('_method', 'PATCH');
            }
            
            submitButton.disabled = true;
            submitButton.textContent = 'جاري الحفظ...';

            fetch(formAction, {
                method: 'POST', // HTML forms only support GET/POST, method is spoofed
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => response.json().then(data => ({ status: response.status, data })))
            .then(({ status, data }) => {
                if (status >= 400) { // Check for client or server error status codes
                    if (status === 422 && data.errors) {
                        const formErrorsDiv = document.getElementById('form-errors');
                        let errorHtml = '<ul>';
                        for (const field in data.errors) {
                            errorHtml += `<li>${data.errors[field][0]}</li>`;
                        }
                        errorHtml += '</ul>';
                        formErrorsDiv.innerHTML = errorHtml;
                        formErrorsDiv.style.display = 'block';
                    }
                    throw new Error(data.message || 'An error occurred');
                }
                
                showToast(data.message);
                closeModal('user-register-modal');
                location.reload(); 
            })
            .catch(error => {
                console.error('Form submission error:', error.message);
                showToast(error.message, true);
            })
            .finally(() => {
                submitButton.disabled = false;
                submitButton.textContent = 'حفظ';
            });
        });
    }
});
</script>
@endpush