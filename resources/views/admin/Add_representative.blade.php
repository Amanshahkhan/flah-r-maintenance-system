@extends('admin.layouts.app')

@section('content')
<main class="admin-rep__main-content">
    <header class="admin-rep__header mb-4">
        <h1 class="admin-rep__title">إضافة ممثل جديد</h1>
        <p class="admin-rep__subtitle">يرجى تعبئة الحقول المطلوبة (*).</p>
    </header>

    <form method="POST" action="{{ route('admin.representatives.store') }}" enctype="multipart/form-data" class="card p-4" style="direction: rtl;">
        @csrf

      
        <div class="form-group mb-3">
            <label for="name">الاسم الكامل <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email --}}
        <div class="form-group mb-3">
            <label for="email">البريد الإلكتروني <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Phone --}}
        <div class="form-group mb-3">
            <label for="phone">رقم الهاتف <span class="text-danger">*</span></label>
            <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required>
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Region --}}
        <div class="form-group mb-3">
            <label for="region">المنطقة <span class="text-danger">*</span></label>
            <input type="text" id="region" name="region" class="form-control @error('region') is-invalid @enderror" value="{{ old('region') }}" required>
            @error('region')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group mb-3">
            <label for="password">كلمة المرور <span class="text-danger">*</span></label>
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

     

        {{-- Address --}}
        <div class="form-group mb-3">
            <label for="address">العنوان</label>
            <textarea id="address" name="address" class="form-control @error('address') is-invalid @enderror" rows="2">{{ old('address') }}</textarea>
            @error('address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Skills --}}
        <div class="form-group mb-3">
            <label for="skills">المهارات (افصل بينها بفاصلة)</label>
            <input type="text" id="skills" name="skills" class="form-control @error('skills') is-invalid @enderror" value="{{ old('skills') }}" placeholder="مثل: سباكة, كهرباء">
            @error('skills')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Notes --}}
        <div class="form-group mb-3">
            <label for="notes">ملاحظات</label>
            <textarea id="notes" name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('admin.representatives_admin') }}" class="btn btn-secondary me-2">إلغاء</a>
            <button type="submit" class="btn btn-primary">حفظ الممثل</button>
        </div>
    </form>
</main>
@endsection