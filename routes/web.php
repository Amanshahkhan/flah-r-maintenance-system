<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminClientController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\Admin\RepresentativeController as AdminRepController;

// --- Public & Authentication Routes ---
Route::get('/', function () { return view('landing'); })->name('landing');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Logged-in Client Routes ---
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
    Route::get('/history', [ClientController::class, 'showHistory'])->name('history');
    Route::get('/payment', [ClientController::class, 'payment'])->name('payment');
    Route::get('/maintenance-request', [ClientController::class, 'showForm'])->name('maintenance.form');
    Route::post('/maintenance-request', [ClientController::class, 'store'])->name('maintenance.store');
    Route::get('/representative-profile/{representative}', [ClientController::class, 'showRepresentativeProfile'])->name('client.view_rep_profile');
    Route::get('/requests/{maintenanceRequest}', [ClientController::class, 'showRequestDetails'])->name('client.view_request');
    Route::get('/maintenance-request/download/{id}', [ClientController::class, 'downloadPDF'])->name('download.pdf');
  });

   // --- Representative Routes ---
   Route::middleware('auth:representative')->prefix('representative')->name('representative.')->group(function () {
    Route::get('/dashboard', [RepresentativeController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests/{maintenanceRequest}', [RepresentativeController::class, 'showRequestDetails'])->name('view_request');
    Route::post('/requests/{maintenanceRequest}/upload-parts-receipt', [RepresentativeController::class, 'uploadPartsReceipt'])->name('upload.parts_receipt');
    Route::post('/requests/{maintenanceRequest}/upload-install-receipt', [RepresentativeController::class, 'uploadInstallReceipt'])->name('upload.install_receipt');
    Route::get('/completed-tasks', [RepresentativeController::class, 'showCompletedTasks'])->name('completed_tasks');
    Route::get('/performance', [RepresentativeController::class, 'showPerformance'])->name('performance');
   });

   // --- ADMIN ROUTES ---
   Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // --- Client Management ---
    Route::get('/clients', [AdminClientController::class, 'index'])->name('clients.index');
    Route::post('/clients', [AdminClientController::class, 'store'])->name('clients.store');
    Route::patch('/clients/{client}', [AdminClientController::class, 'update'])->name('clients.update');
    Route::patch('/clients/{client}/toggle-status', [AdminClientController::class, 'toggleStatus'])->name('clients.toggleStatus');
    Route::delete('/clients/{client}', [AdminClientController::class, 'destroy'])->name('clients.destroy');

    // --- Request Management ---
    Route::get('/requests', [AdminController::class, 'showAllRequests'])->name('requests_admin');
    Route::get('/requests/progress', [AdminController::class, 'showInProgressRequests'])->name('request_progress');
    Route::get('/requests/completed', [AdminController::class, 'showCompletedRequests'])->name('request_completed');
    Route::get('/requests/rejected', [AdminController::class, 'showRejectedRequests'])->name('request_rejects');
    Route::get('/requests/{maintenanceRequest}', [AdminController::class, 'show'])->name('view_request');
    Route::post('/requests/{maintenanceRequest}/assign', [AdminController::class, 'assignRepresentativeToRequest'])->name('requests.assign');
    Route::post('/requests/{maintenanceRequest}/reject', [AdminController::class, 'rejectRequest'])->name('requests.reject');
    Route::post('/requests/{maintenanceRequest}/complete', [AdminController::class, 'completeRequest'])->name('requests.complete');
    Route::patch('/requests/{maintenanceRequest}/approve-final', [AdminController::class, 'approveAndCloseRequest'])->name('requests.approve_final');


    // --- Contract Management ---
    Route::get('/contracts', [ContractController::class, 'index'])->name('contracts_admin');
    Route::post('/contracts', [ContractController::class, 'store'])->name('contracts.store');
    Route::post('/contracts/import', [ContractController::class, 'import'])->name('contracts.import');
    Route::delete('/contracts/{contract}', [ContractController::class, 'destroy'])->name('contracts.destroy');
    
    // --- Contract-Specific Product Management ---
    Route::get('/contracts/{contract}/manage-products', [ContractController::class, 'manageProducts'])->name('contracts.manage_products');
    Route::post('/contracts/{contract}/import-products', [ContractController::class, 'importProducts'])->name('contracts.import_products');
    Route::delete('/products/{product}/destroy', [ProductController::class, 'destroy'])->name('contracts.destroy_product'); // Pointing to ProductController is better

    // In routes/web.php, inside the admin group...
   Route::resource('representatives', AdminRepController::class)->names([
    'index' => 'representatives_admin', // Custom name for the list page
    'create' => 'representatives.create',
    'store' => 'representatives.store',   // The name for the POST action
    'edit' => 'representatives.edit',
    'update' => 'representatives.update',
    'destroy' => 'representatives.destroy'
   ])->except(['show']); 
   Route::patch('/representatives/{representative}/toggle-status', [AdminRepController::class, 'toggleStatus'])->name('representatives.toggleStatus');
   Route::get('/representatives/list-json', [AdminRepController::class, 'listJson'])->name('representatives.listJson');


   Route::post('/requests/{maintenanceRequest}/forward', [AdminController::class, 'forwardRequest'])->name('requests.forward');
   
    // --- Product Management (Master List) ---
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
    Route::delete('/products/bulk-destroy', [ProductController::class, 'bulkDestroy'])->name('products.bulk_destroy');
    Route::patch('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
  Route::post('/requests/{maintenanceRequest}/send-to-client', [AdminController::class, 'sendCopyToClient'])->name('requests.sendToClient');
    // --- Reports ---
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/data/{type}', [ReportController::class, 'getReportData'])->name('reports.data');
    });
   
// --- Fallback Route ---
Route::fallback(function () {
    return redirect()->route('login.form');
});