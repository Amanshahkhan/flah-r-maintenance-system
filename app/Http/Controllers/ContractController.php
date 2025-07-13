<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ContractsImport;
use App\Imports\ProductsForContractImport;

class ContractController extends Controller
{
    /**
     * Display a list of all contracts with search and pagination.
     */
    public function index(Request $request)
    {
        $query = Contract::query();

        if ($request->has('search') && $request->input('search') != '') {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('contract_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('contract_name', 'LIKE', "%{$searchTerm}%");
            });
        }

        // We get stats before pagination to count all matching records
        $totalValue = $query->sum('total_value');
        $activeContractsCount = $query->count();

        // Paginate the results
        $contracts = $query->latest()->paginate(15);

        return view('admin.contracts_admin', compact(
            'contracts',
            'totalValue',
            'activeContractsCount'
        ));
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'contract_number' => 'required|unique:contracts,contract_number',
            'contract_name' => 'required|string|max:255',
            'contract_date' => 'required|date',
            'start_date' => 'required|date',
            'total_value' => 'required|numeric',
            'remaining_value' => 'required|numeric',
        ]);

        Contract::create($validated);
        return back()->with('success', 'تم حفظ العقد بنجاح');
    }

   
    public function import(Request $request)
    {
        $request->validate([
            'contracts_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new ContractsImport, $request->file('contracts_file'));
            return back()->with('success', 'تم استيراد العقود بنجاح');
        } catch (\Exception $e) {
            \Log::error('Contract Import Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استيراد ملف العقود.');
        }
    }

   
    public function show(Contract $contract)
    {
        $contract->load('products');
        return view('admin.contracts.show', compact('contract'));
    }

   
 // In app/Http/Controllers/ContractController.php
public function manageProducts(Contract $contract)
{
    // This correctly gets all product objects belonging to the contract.
    $products = $contract->products()->latest()->get(); 
    
    // We pass both 'contract' and the list of its 'products' to the view.
    return view('admin.manage_products', compact('contract', 'products'));
}
    /**
     * Import products from Excel AND link them to THIS specific contract.
     */
    public function importProducts(Request $request, Contract $contract)
    {
        $request->validate([
            'products_file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {
            // Import the products, passing the contract's ID to the importer.
            Excel::import(new ProductsForContractImport($contract->id), $request->file('products_file'));
            return back()->with('success', 'تم استيراد المنتجات وربطها بالعقد بنجاح!');
        } catch (\Exception $e) {
            \Log::error('Product Import Error for Contract ' . $contract->id . ': ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء استيراد الملف.');
        }
    }

    /**
     * Deletes a specific product that belongs to a contract.
     * We need a route for this, which we will add next.
     */
   public function destroy(Contract $contract)
    {
        // Because we set up 'onDelete('cascade')' in the products migration,
        // when a contract is deleted, all of its associated products
        // will be automatically deleted from the 'products' table.
        $contract->delete();

        return redirect()->route('admin.contracts_admin')->with('success', 'تم حذف العقد وجميع منتجاته بنجاح.');
    }
}