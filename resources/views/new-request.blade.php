@extends('layouts.app')

@section('content')



<form id="maintenanceForm" method="POST" action="{{ route('maintenance.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <label for="vehicle_number">رقم سيارة:</label>
        <input type="text" id="vehicle_number" name="vehicle_number" required>
    </div>

    <div class="form-group">
        <label for="vehicle_color">لون سيارة:</label>
        <input type="text" id="vehicle_color" name="vehicle_color" required>
    </div>

    <div class="form-group">
        <label for="vehicle_model">طراز سيارة:</label>
        <input type="text" id="vehicle_model" name="vehicle_model" required>
    </div>

    <div class="form-group">
        <label for="vehicle_images">صور سيارة:</label>
        <input type="file" id="vehicle_images" name="vehicle_images[]" accept="image/*" multiple>
    </div>

    <div class="form-group">
        <label for="parts_select">المشاكل الكبيرة في السيارة:</label>
        <select id="parts_select" name="parts_select[]" multiple required>
            <option value="محرك لا يعمل">محرك لا يعمل</option>
            <option value="ارتفاع حرارة المحرك">ارتفاع حرارة المحرك</option>
            <option value="تسرب زيت">تسرب زيت</option>
            <option value="خلل في الفرامل">خلل في الفرامل</option>
            <option value="البطارية تالفة">البطارية تالفة</option>
            <option value="عطل في القير">عطل في القير</option>
            <option value="إطارات تالفة">إطارات تالفة</option>
            <option value="خلل في نظام الكهرباء">خلل في نظام الكهرباء</option>
            <option value="تكييف لا يعمل">تكييف لا يعمل</option>
            <option value="أصوات غريبة من السيارة">أصوات غريبة من السيارة</option>
        </select>
        <small>اضغط Ctrl (أو Cmd) لاختيار أكثر من خيار</small>
    </div>

    <div class="form-group">
        <label for="parts_manual">قطع يدوية:</label>
        <textarea id="parts_manual" name="parts_manual"></textarea>
    </div>

    <div class="form-group">
        <label for="authorized_personnel">المصرح له:</label>
        <input type="text" id="authorized_personnel" name="authorized_personnel">
    </div>

    <button type="submit">إرسال الطلب</button>
</form>

@endsection
