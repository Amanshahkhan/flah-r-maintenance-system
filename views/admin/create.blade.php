@extends('admin.layouts.app')
@section('content')
<main class="admin-rep__main-content">
    <header class="mb-4"><h1 class="h3">إضافة ممثل جديد</h1></header>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data" style="direction: rtl;">
                @csrf
                {{-- Include your form fields here (Name, Email, Phone, etc.) --}}
                {{-- Example for Name field --}}
                <div class="mb-3">
                    <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                {{-- ... Add all other form fields ... --}}
                 <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور <span class="text-danger">*</span></label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                    <button type="submit" class="btn btn-primary">حفظ الممثل</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection