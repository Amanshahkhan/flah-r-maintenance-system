@extends('admin.layouts.app')

@section('content')
<main class="admin-rep__main-content container-fluid">
    <header class="mb-4 pt-3">
        <h1 class="h3">تعديل بيانات الممثل: {{ $representative->name }}</h1>
    </header>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.representatives.update', $representative->id) }}" enctype="multipart/form-data" style="direction: rtl;">
                @csrf
                @method('PATCH') {{-- Important: Tells Laravel this is an UPDATE request --}}

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $representative->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $representative->email) }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">الهاتف <span class="text-danger">*</span></label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $representative->phone) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="region" class="form-label">المنطقة <span class="text-danger">*</span></label>
                        <input type="text" id="region" name="region" class="form-control" value="{{ old('region', $representative->region) }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="address" class="form-label">العنوان</label>
                    <input type="text" id="address" name="address" class="form-control" value="{{ old('address', $representative->address) }}">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">كلمة المرور (اتركه فارغاً لعدم التغيير)</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label for="avatar" class="form-label">الصورة الشخصية</label>
                    <input type="file" id="avatar" name="avatar" class="form-control">
                    @if($representative->avatar)
                        <small class="form-text text-muted">Current image: {{ $representative->avatar }}</small>
                    @endif
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('admin.representatives.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                    <button type="submit" class="btn btn-primary">تحديث البيانات</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection