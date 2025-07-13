@extends('representative.layouts.representative')

{{-- Use $request here --}}
@section('title', 'تفاصيل الطلب #' . $request->id)

@section('content')

<header class="main-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        {{-- Use $request here --}}
        <h1>تفاصيل الطلب <span style="color: #1abb9c;">#{{ $request->id }}</span></h1>
        <a href="{{ route('representative.dashboard') }}" class="action-btn" style="background-color: #777;">
            <i class="fas fa-arrow-right"></i> الرجوع للوحة التحكم
        </a>
    </div>
</header>

<div class="details-grid">
    <section class="info-card">
        <div class="card-header">
            <h3><i class="fas fa-info-circle"></i> معلومات الطلب والعميل</h3>
        </div>
        <div class="card-body">
            {{-- Ensure all of these use $request --}}
            <p><strong>العميل:</strong> {{ $request->user->name }}</p>
            <p><strong>رقم التواصل:</strong> {{ $request->user->mobile }}</p>
            <hr>
        <p><strong>رقم المركبة:</strong> {{ $request->vehicle_number ?? 'غير محدد' }}</p>
        <p><strong>طراز السيارة:</strong> {{ $request->vehicle_model ?? 'غير محدد' }}</p>
       <p><strong>نوع السيارة:</strong> {{ $request->vehicle_color ?? 'غير محدد' }}</p>
      <p><strong>             رقم الشاصي</strong> {{ $request->chassis_number ?? 'غير محدد' }}</p>
            <p><strong>المشكلة المبلغ عنها:</strong> {{ $request->parts_manual }}</p>
{{-- Find this old line and delete it --}}
{{-- <p><strong>القطع المطلوبة:</strong> {{ implode(', ', $request->parts_select) }}</p> --}}

{{-- ✅ REPLACE IT WITH THIS NEW BLOCK --}}
<div>
    <strong>القطع المطلوبة:</strong>
    @if($request->products && $request->products->isNotEmpty())
        <ul style="padding-right: 20px; margin-top: 5px;">
            @foreach($request->products as $product)
                <li>{{ $product->item_description }} (الكمية: {{ $product->pivot->quantity }})</li>
            @endforeach
        </ul>
    @else
        <p>لا يوجد.</p>
    @endif
</div>            <p><strong>الحالة الحالية:</strong> <span class="status {{ str_replace(' ', '-', strtolower($request->status)) }}">{{ $request->status }}</span></p>
        </div>
    </section>

    <section class="info-card">
        <div class="card-header">
            <h3><i class="fas fa-file-upload"></i> رفع المستندات</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert-success" style="margin: 0 0 1rem 0;">{{ session('success') }}</div>
            @endif
            
            <p class="text-muted">بعد استلام وتسليم القطع، يرجى رفع المستندات المطلوبة هنا لإغلاق الطلب.</p>

            {{-- Use $request here --}}
            @if($request->parts_receipt_doc_path)
                <div class="document-link">
                    <i class="fas fa-check-circle text-success"></i>
                    <strong>تم رفع مستند استلام القطع:</strong>
                    {{-- Use $request here --}}
                    <a href="{{ Storage::url($request->parts_receipt_doc_path) }}" target="_blank">عرض المستند</a>
                </div>
            @else
                {{-- Use $request here --}}
               <form action="{{ route('representative.upload.parts_receipt', $request->id) }}" method="POST" enctype="multipart/form-data" class="upload-form">
    @csrf
    <label for="parts_receipt_doc">1. مستند استلام القطع من الجهة:</label>
    <input type="file" id="parts_receipt_doc" name="parts_receipt_doc" required>
    <button type="submit" ...>رفع مستند الاستلام</button>
</form>
            @endif

            <hr>

            {{-- Use $request here --}}
            @if($request->install_complete_doc_path)
                 <div class="document-link">
                    <i class="fas fa-check-circle text-success"></i>
                    <strong>تم رفع مستند التركيب:</strong>
                    {{-- Use $request here --}}
                    <a href="{{ Storage::url($request->install_complete_doc_path) }}" target="_blank">عرض المستند</a>
                </div>
            @else
                 {{-- Use $request here --}}
                 <form action="{{ route('representative.upload.install_receipt', $request->id) }}" method="POST" enctype="multipart/form-data" class="upload-form">
                    @csrf
                    <label for="install_complete_doc">2. مستند استلام تركيب القطع:</label>
                    <input type="file" id="install_complete_doc" name="install_complete_doc" required>
                    <button type="submit" class="action-btn" style="background-color: #2ecc71;"><i class="fas fa-check"></i> رفع وإرسال للمراجعة</button>
                </form>
            @endif
        </div>
    </section>
</div>

<style>
/* ... your styles ... */
.document-link {
    padding: 1rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
}
.text-success { color: #28a745; }
</style>

@endsection