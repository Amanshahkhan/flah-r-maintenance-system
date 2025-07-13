<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    // ... index() and store() methods are unchanged ...
    public function index()
    {
        $products = Product::latest()->get();
        $totalValue = $products->sum('total_price');
        $productsCount = $products->count();
        return view('admin.product', compact('products', 'totalValue', 'productsCount'));
    }

    public function store(Request $request)
    {
        // Use the validated data for security
        $validated = $request->validate([
            'item'                 => 'nullable|string|max:255',
            'item_description'     => 'nullable|string',
            'specifications'       => 'nullable|string',
            'unit'                 => 'required|string|max:255',
            'quantity'             => 'required|integer|min:0',
            'unit_price'           => 'required|numeric|min:0',
            'discount'             => 'required|numeric|min:0',
            'price_after_discount' => 'required|numeric|min:0',
            'price_with_vat'       => 'required|numeric|min:0',
            'total_price'          => 'required|numeric|min:0',
        ]);

        Product::create($validated);
        return back()->with('success', 'تم إضافة المنتج بنجاح!');
    }


    // ✅ ADD THIS ENTIRE UPDATE METHOD
    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        // Validation is almost identical to the store method
        $validated = $request->validate([
            'item'                 => 'nullable|string|max:255',
            'item_description'     => 'nullable|string',
            'specifications'       => 'nullable|string',
            'unit'                 => 'required|string|max:255',
            'quantity'             => 'required|integer|min:0',
            'unit_price'           => 'required|numeric|min:0',
            'discount'             => 'required|numeric|min:0',
            'price_after_discount' => 'required|numeric|min:0',
            'price_with_vat'       => 'required|numeric|min:0',
            'total_price'          => 'required|numeric|min:0',
        ]);

        // Use the $product instance from route model binding to update
        $product->update($validated);

        return back()->with('success', 'تم تحديث المنتج بنجاح!');
    }


    // ... import() and destroy() methods are unchanged ...
    public function import(Request $request)
    {
        $request->validate([
            'products_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('products_file'));
            return back()->with('success', 'تم استيراد المنتجات بنجاح!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             $failures = $e->failures();
             $errorMessages = [];
             foreach ($failures as $failure) {
                 $errorMessages[] = 'Row ' . $failure->row() . ': ' . implode(', ', $failure->errors());
             }
             return back()->with('error', 'حدث خطأ في التحقق من صحة بيانات الملف: <br>' . implode('<br>', $errorMessages));
        } catch (\Exception $e) {
            \Log::error('Product Import Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->with('error', 'حدث خطأ غير متوقع أثناء استيراد الملف. يرجى مراجعة سجل الأخطاء.');
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'تم حذف المنتج بنجاح.');
    }
     public function bulkDestroy(Request $request)
    {
        // 1. Validate that we received an array of IDs
        $request->validate([
            'selected_products' => 'required|json',
        ]);

        // 2. Decode the JSON string of IDs into a PHP array
        $productIds = json_decode($request->input('selected_products'), true);

        // 3. Ensure it's a non-empty array
        if (empty($productIds)) {
            return back()->with('error', 'لم يتم تحديد أي منتجات للحذف.');
        }

        // 4. Delete all products where the ID is in the provided array
        Product::whereIn('id', $productIds)->delete();

        return back()->with('success', 'تم حذف المنتجات المحددة بنجاح.');
    }
}