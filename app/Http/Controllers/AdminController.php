<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MaintenanceRequest; // ✅ IMPORTANT: Add this use statement at the top
use App\Models\Representative;     // You probably already have this
use App\Models\User; // Still needed for client users
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Good for logging errors
use Illuminate\Support\Facades\DB; // ✅ Import DB Facade for transactions
use App\Models\Product; // ✅ Import Product model
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestAssignedToClient;
use App\Mail\RequestAssignedToRepresentative;
use App\Mail\RequestCompletionReport; // We will create this next
use App\Mail\ForwardMaintenanceRequest;

class AdminController extends Controller
{

    public function listRepresentativesJson()
    {
        $representatives = Representative::select('id', 'name')->get(); // Fetch from your Representative model
        return response()->json($representatives);
    }


  // In app/Http/Controllers/AdminController.php

public function assignRepresentativeToRequest(Request $httpRequest, MaintenanceRequest $maintenanceRequest)
{
    // 1. Validate the incoming request (this part is correct)
    $validatedData = $httpRequest->validate([
        'representative_id' => 'required|exists:representatives,id',
    ]);
    
    try {
        // 2. Start a database transaction for safety
        DB::transaction(function () use ($maintenanceRequest, $validatedData) {
            
            // ✅ --- NEW, CORRECT LOGIC ---
            // Get all the products linked to this request through the relationship.
            // The 'pivot' object contains the extra data like 'quantity'.
            $requestedProducts = $maintenanceRequest->products; 

            // If for some reason there are no products, throw an error.
            if ($requestedProducts->isEmpty()) {
                throw new \Exception('لا يمكن تعيين الطلب لأنه لا يحتوي على منتجات.');
            }

            // Calculate the total cost based on the actual products and quantities requested.
            $totalCost = 0;
            foreach ($requestedProducts as $product) {
                // $product->price_with_vat is the price of one item.
                // $product->pivot->quantity is the quantity the user requested for this specific item.
                $totalCost += $product->price_with_vat * $product->pivot->quantity;
            }
            // ✅ --- END OF NEW LOGIC ---


            // The rest of the logic for checking the contract is the same and should work.
            $client = $maintenanceRequest->user;
            $contract = $client->contract;

            if (!$contract) {
                throw new \Exception('لا يوجد عقد مرتبط بهذا العميل.');
            }

            if ($contract->remaining_value < $totalCost) {
                throw new \Exception('الرصيد المتبقي في العقد غير كافٍ لتغطية تكلفة هذا الطلب.');
            }

            // Deduct the cost from the contract
            $contract->remaining_value -= $totalCost;
            $contract->save();

            // Update the maintenance request with the final cost and assign it
            $maintenanceRequest->update([
                'status' => 'in-progress',
                'representative_id' => $validatedData['representative_id'],
                'assigned_at' => now(),
                'total_cost' => $totalCost, // Save the calculated total cost
                'rejected_at' => null,
                'rejection_reason' => null,
            ]);
        }); // End of DB::transaction

        // The notification logic is the same and should work.
        try {
            $freshRequest = $maintenanceRequest->fresh()->load(['user', 'representative']);
            Mail::to($freshRequest->user->email)->send(new RequestAssignedToClient($freshRequest));
            Mail::to($freshRequest->representative->email)->send(new RequestAssignedToRepresentative($freshRequest));
        } catch (\Exception $e) {
            Log::error('Mail sending failed after assignment: ' . $e->getMessage());
        }

        // Return a success response
        return response()->json([
            'message' => 'تم تعيين المندوب وخصم التكلفة من العقد بنجاح!',
            'request' => $maintenanceRequest->fresh()->load(['user', 'representative'])
        ]);

    } catch (\Exception $e) {
        // Catch any errors from the transaction
        Log::error('Critical Error during request assignment: ' . $e->getMessage());
        return response()->json(['message' => 'فشل تعيين الطلب: ' . $e->getMessage()], 500);
    }
}


    // ... all your other methods like rejectRequest(), dashboard(), etc. ...

    public function rejectRequest(Request $httpRequest, MaintenanceRequest $maintenanceRequest)
    {
        $httpRequest->validate([
            'rejection_reason' => 'required|string|min:5|max:500',
        ]);

        try {
            $maintenanceRequest->update([
                'status' => 'rejected',
                'rejection_reason' => $httpRequest->rejection_reason,
                'rejected_at' => now(),
                'representative_id' => null,
                'assigned_at' => null,
                'completed_at' => null,
            ]);
            return response()->json([
                'message' => 'تم رفض الطلب بنجاح!',
                'request' => $maintenanceRequest->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error rejecting request ID ' . $maintenanceRequest->id . ': ' . $e->getMessage());
            return response()->json(['message' => 'فشل رفض الطلب. الرجاء المحاولة مرة أخرى.'], 500);
        }
    }

    public function completeRequest(MaintenanceRequest $maintenanceRequest)
    {
        if ($maintenanceRequest->status !== 'in-progress') {
            return response()->json(['message' => 'لا يمكن إكمال الطلب لأنه ليس قيد التنفيذ حالياً.'], 422);
        }

        try {
            $maintenanceRequest->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // TODO: Notify Client (e.g., email, notification)

            return response()->json([
                'message' => 'تم تحديد الطلب كمكتمل بنجاح!',
                'request' => $maintenanceRequest->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error completing request ID ' . $maintenanceRequest->id . ': ' . $e->getMessage());
            return response()->json(['message' => 'فشل إكمال الطلب. الرجاء المحاولة مرة أخرى.'], 500);
        }
    }

    public function show(MaintenanceRequest $maintenanceRequest)
    {
        // We will pass both 'request' and 'maintenanceRequest' for compatibility
        // with any view that might use one or the other.
        return view('admin.show_request', [
            'request' => $maintenanceRequest,
            'maintenanceRequest' => $maintenanceRequest, 
        ]);
    }

// In app/Http/Controllers/AdminController.php

// This is the correct method. Make sure it's the ONLY ONE with this name in the file.
public function showInProgressRequests()
{
    // --- Get Counts for the Summary Cards ---
    $allCount = MaintenanceRequest::count();
    $inProgressCount = MaintenanceRequest::where('status', 'in-progress')->count();
    $completedCount = MaintenanceRequest::where('status', 'completed')->count();
    $rejectedCount = MaintenanceRequest::where('status', 'rejected')->count();

    // --- Get the List of In-Progress Requests for the Table ---
    $requests = MaintenanceRequest::where('status', 'in-progress')
                                  ->with(['user', 'representative'])
                                  ->latest()
                                  ->get();

    // Pass all the data to the view
    return view('admin.request_progress', compact(
        'requests',
        'allCount',
        'inProgressCount',
        'completedCount',
        'rejectedCount'
    ));
}

// Method for COMPLETED requests
public function showCompletedRequests()
{
    // ... (logic to get counts is the same) ...
    $allCount = MaintenanceRequest::count();
    $inProgressCount = MaintenanceRequest::where('status', 'in-progress')->count();
    $completedCount = MaintenanceRequest::where('status', 'completed')->count();
    $rejectedCount = MaintenanceRequest::where('status', 'rejected')->count();

    $requests = MaintenanceRequest::where('status', 'completed')->with(['user', 'representative'])->latest()->get();
    $pageTitle = 'الطلبات المكتملة';

    // Point to the new reusable view
    return view('admin.request_completed', compact('requests', 'pageTitle', 'allCount', 'inProgressCount', 'completedCount', 'rejectedCount'));
}

public function showRejectedRequests()
{
    $allCount = MaintenanceRequest::count();
    $inProgressCount = MaintenanceRequest::where('status', 'in-progress')->count();
    $completedCount = MaintenanceRequest::where('status', 'completed')->count();
    $rejectedCount = MaintenanceRequest::where('status', 'rejected')->count();

    $requests = MaintenanceRequest::where('status', 'rejected')
                                  ->with(['user', 'representative'])
                                  ->latest()
                                  ->get();

    $pageTitle = 'الطلبات المرفوضة';
    // Reuse a flexible view or create a new one
    return view('admin.requests_admin', compact('requests', 'pageTitle', 'allCount', 'inProgressCount', 'completedCount', 'rejectedCount'));
}

public function dashboard()
{
    // Fetch ALL maintenance request records from the database
    $requests = MaintenanceRequest::all(); 

    // Fetch the count of users
    $clientsCount = User::where('role', 'user')->count();

    // Pass the collection of requests and the client count to the view
    return view('admin.dashboard', compact('requests', 'clientsCount'));
}

public function show_contract()
{

    return view('admin.show_contract');
}


public function showAllRequests()
{
    $allCount = MaintenanceRequest::count();
        $requests = MaintenanceRequest::with(['user.contract', 'representative'])->latest()->get();
    $inProgressCount = MaintenanceRequest::where('status', 'in-progress')->count();
    $completedCount = MaintenanceRequest::where('status', 'completed')->count();
    $rejectedCount = MaintenanceRequest::where('status', 'rejected')->count();

    $requests = MaintenanceRequest::with(['user', 'representative'])->latest()->get();
    
    $pageTitle = 'جميع طلبات الصيانة';
    return view('admin.requests_admin', compact('requests', 'pageTitle', 'allCount', 'inProgressCount', 'completedCount', 'rejectedCount'));
}



public function approveAndCloseRequest(MaintenanceRequest $maintenanceRequest)
{
   if ($maintenanceRequest->status !== 'Pending Review') {
        return back()->with('error', 'This request is not awaiting final review.');
    }

     $maintenanceRequest->update([
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    // You can add a final notification to the client here if you wish

    return back()->with('success', "تم إغلاق الطلب رقم #{$maintenanceRequest->id} بنجاح.");
}
  public function sendCopyToClient(MaintenanceRequest $maintenanceRequest)
    {
        // Security check: ensure the request is actually completed
        if ($maintenanceRequest->status !== 'completed') {
            return back()->with('error', 'لا يمكن إرسال بريد إلكتروني لطلب غير مكتمل.');
        }

        $client = $maintenanceRequest->user;

        // Check if the client and their email exist
        if (!$client || !$client->email) {
            return back()->with('error', 'لا يوجد بريد إلكتروني مسجل لهذا العميل.');
        }

        try {
            // The Mailable will handle the email content. We just need to send it.
            // We'll create a standard subject line.
            $subject = "ملخص طلب الصيانة المكتمل رقم #{$maintenanceRequest->id}";
            
            Mail::to($client->email)
                ->send(new RequestCompletionReport($maintenanceRequest, $subject, null)); // No custom message needed
            
            return back()->with('success', 'تم إرسال البريد الإلكتروني للعميل بنجاح!');

        } catch (\Exception $e) {
            Log::error('Failed to send client copy email: ' . $e->getMessage());
            return back()->with('error', 'فشل إرسال البريد الإلكتروني. يرجى مراجعة الإعدادات.');
        }
    }
 
// Add this new method inside the class
public function forwardRequest(Request $request, MaintenanceRequest $maintenanceRequest)
{
    // Validate the input from the modal
    $validated = $request->validate([
        'recipient_email' => 'required|email',
        'notes' => 'nullable|string|max:1000',
    ]);

    try {
        // Send the email to the manually entered address
        Mail::to($validated['recipient_email'])
            ->send(new ForwardMaintenanceRequest($maintenanceRequest, $validated['notes']));

        return response()->json(['success' => true, 'message' => 'تم إرسال الطلب بنجاح!']);

    } catch (\Exception $e) {
        \Log::error('Failed to forward request email: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ أثناء إرسال البريد الإلكتروني.'
        ], 500);
    }
}
}
