<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product; 
use App\Models\Contract;
use PDF; 
use App\Models\User;
use App\Models\Representative; // ✅ IMPORTANT: Add this use statement
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewRequestForAdmin;
use Twilio\Rest\Client as TwilioClient; // ✅ Import the Twilio Client
use Illuminate\Support\Facades\DB; // Make sure this is imported

class ClientController extends Controller
{
 public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login.form')->with('error', 'يجب تسجيل الدخول أولاً');
        }

        // 1. VALIDATE ALL FIELDS, INCLUDING THE NEW ONES
        $validated = $request->validate([
            'vehicle_number' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'chassis_number' => 'required|string|max:255',
            'vehicle_images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:10240', // 10MB max
            'pdf_document' => 'nullable|file|mimes:pdf|max:5120', // 5MB max
            'products' => 'required|array|min:1',
            'products.*.quantity' => 'required|integer|min:1',
            'parts_manual' => 'nullable|string',
        ]);

        // 2. HANDLE FILE UPLOADS
        $imagePaths = [];
        if ($request->hasFile('vehicle_images')) {
            foreach ($request->file('vehicle_images') as $file) {
                // Using the 'public' disk we configured (storage/app/public/...)
                $path = $file->store('vehicle_images', 'public');
                $imagePaths[] = $path;
            }
        }

        $pdfPath = null;
        if ($request->hasFile('pdf_document')) {
            $pdfPath = $request->file('pdf_document')->store('request_documents', 'public');
        }

        // 3. USE A DATABASE TRANSACTION FOR SAFETY
        DB::beginTransaction();
        try {
            // 4. CREATE THE MAINTENANCE REQUEST WITH ALL FIELDS
            $newRequest = MaintenanceRequest::create([
                'user_id' => Auth::id(),
                'contract_id' => Auth::user()->contract_id, // Link the contract automatically
                'vehicle_number' => $validated['vehicle_number'],
                'vehicle_color' => $validated['vehicle_color'],
                'vehicle_model' => $validated['vehicle_model'],
                'chassis_number' => $validated['chassis_number'], // Add chassis number
                'vehicle_images' => $imagePaths,
                'pdf_document_path' => $pdfPath, // Add PDF path
                'parts_manual' => $validated['parts_manual'],
                'status' => 'pending',
            ]);

            // 5. ATTACH THE SELECTED PRODUCTS TO THE REQUEST
            $productData = [];
            // Find all selected products in one query for efficiency
            $productModels = Product::find(array_keys($validated['products']));

            foreach ($validated['products'] as $productId => $data) {
                $product = $productModels->find($productId);
                if ($product) {
                    $productData[$productId] = [
                        'quantity' => $data['quantity'],
                        'price_at_order' => $product->price_with_vat // Save the price at the time of order
                    ];
                }
            }

            if (!empty($productData)) {
                $newRequest->products()->attach($productData);
            }

            // If everything is successful, commit the changes to the database
            DB::commit();

            // 6. SEND NOTIFICATIONS (Optional)
            try {
                $admin = User::where('role', 'admin')->first();
                if ($admin && $admin->email) {
                    Mail::to($admin->email)->send(new NewRequestForAdmin($newRequest));
                }
            } catch (\Exception $e) {
                Log::error('Mail sending failed for new request: ' . $e->getMessage());
            }

            return redirect()->route('maintenance.form')->with('success', 'تم إرسال الطلب بنجاح.');

        } catch (\Exception $e) {
            // If anything fails, roll back all database changes
            DB::rollBack();
            Log::error('Failed to create maintenance request: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إرسال الطلب. يرجى المحاولة مرة أخرى.')->withInput();
        }
    }

public function payment()
{
   $user = Auth::user();

    // Directly access the contract through the relationship.
    // We use load('contract') to prevent the "N+1 query problem" if you
    // were to access other user details later. It's a good habit.
    $user->load('contract');
    
    // The user's contract is now available as a property.
    $contract = $user->contract;

    // Pass the contract object to the view. If the user has no contract,
    // $contract will be null, and your @if($contract) check will work perfectly.
    return view('client.payment', compact('contract'));
}

 // In ClientController.php


// In app/Http/Controllers/ClientController.php

public function showForm()
{
    $user = Auth::user();

    // Check if the user is linked to a contract
    if (!$user->contract_id) {
        return redirect()->route('dashboard')->with('error', 'You are not assigned to a contract and cannot create requests.');
    }

    // Find the user's contract and get the products linked to it
    $contract = Contract::with('products')->find($user->contract_id);

    if (!$contract) {
        return redirect()->route('dashboard')->with('error', 'The contract you are assigned to could not be found.');
    }

    // Get the list of allowed products from the contract relationship
    $products = $contract->products;

    // Pass this filtered list of products to the view
    return view('client.maintenance_request', compact('products'));
}

public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}




public function dashboard()
    {
        $userId = Auth::id();
        $requests = MaintenanceRequest::where('user_id', $userId)
                                      ->with('representative')
                                      ->latest()
                                      ->get();

        return view('client.dashboard', compact('requests'));
    }

public function showHistory()
    {
        $userId = Auth::id();
        $requests = MaintenanceRequest::where('user_id', $userId)
                                      ->with('representative')
                                      ->latest()
                                      ->get();

        return view('client.history', compact('requests'));
    }

public function downloadPdf($id)
{
    $request = MaintenanceRequest::findOrFail($id);

    $pdf = PDF::loadView('client.pdf.maintenance', compact('request'));
    return $pdf->download('maintenance_request_'.$id.'.pdf');
}

  public function index()
    {
       
        $users = User::withCount('contracts')->where('role', 'client')->get(); 
        return view('admin.layouts.app', compact('users')); 
    }

   
    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $message = 'User has been deactivated.';
            $newStatus = 'Inactive';
        } else {
            $user->email_verified_at = now();
            $message = 'User has been activated.';
            $newStatus = 'Active';
        }

        $user->save();

        return response()->json([
            'message' => $message,
            'newStatus' => $newStatus,
        ]);
    }

/**
 * Remove the specified client from storage.
 *
 * @param  int  $id
 * @return \Illuminate\Http\JsonResponse
 */


  public function showRepresentativeProfile(Representative $representative)
    {
        // Laravel's Route Model Binding automatically finds the representative.
        // We pass the found representative object to the new view.
        return view('client.representative_profile', [
            'representative' => $representative
        ]);
    }

  // In ClientController.php

// In ClientController.php

public function showRequestDetails(MaintenanceRequest $maintenanceRequest)
{
    // ** SECURITY CHECK **
    if ($maintenanceRequest->user_id !== Auth::id()) {
        abort(403, 'غير مصرح لك بعرض هذا الطلب.');
    }

    // ✅ EAGER LOAD THE RELATIONSHIPS
    // This tells Laravel to get the request, its user, and its representative all at once.
    $maintenanceRequest->load('user', 'representative', 'products');

    // Now, the $maintenanceRequest object is guaranteed to have the representative's data.
    return view('client.show_request', [
        'request' => $maintenanceRequest
    ]);
}


}
