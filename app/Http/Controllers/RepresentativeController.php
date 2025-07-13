<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenanceRequest;
use App\Models\Representative;
use Illuminate\Support\Facades\Storage; // ✅ IMPORTANT: Add this for file handling
use Illuminate\Support\Facades\DB;      // ✅ IMPORTANT: Add this for performance queries

class RepresentativeController extends Controller
{
    public function dashboard()
    {
        $representative = Auth::guard('representative')->user();
       $activeRequests = MaintenanceRequest::with('user', 'products') // ✅ ADD 'products' HERE
            ->where('representative_id', $representative->id)
            ->whereIn('status', ['in-progress', 'Assigned'])
            ->orderBy('created_at', 'desc')
            ->get();
        $stats = [
            'active_assignments' => $activeRequests->count(),
            'completed_this_month' => MaintenanceRequest::where('representative_id', $representative->id)
                ->where('status', 'Completed')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->count(),
        ];
        return view('representative.dashboard', [
            'representative' => $representative,
            'activeRequests' => $activeRequests,
            'stats' => $stats,
        ]);
    }

  // In app/Http/Controllers/RepresentativeController.php

public function showRequestDetails(MaintenanceRequest $maintenanceRequest)
{
    // Security check: Ensure the logged-in representative is the one assigned to the request.
    $loggedInRepId = Auth::guard('representative')->id();
    if ($maintenanceRequest->representative_id !== $loggedInRepId) {
        abort(403, 'غير مصرح لك بالوصول لهذا الطلب.');
    }
    $maintenanceRequest->load('user', 'representative', 'products');

    // Your existing logic to get stats for the header is fine.
    $stats = [
        'active_assignments' => MaintenanceRequest::where('representative_id', $loggedInRepId)
                                                  ->whereIn('status', ['in-progress', 'Assigned'])
                                                  ->count(),
    ];
    
    return view('representative.view_request', [
        'request' => $maintenanceRequest,
        'stats' => $stats,
    ]);
}
    // --- ADD THE MISSING METHODS BELOW THIS LINE ---

    /**
     * ✅ Handles the upload for the "Parts Received" document.
     */
    public function uploadPartsReceipt(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $request->validate([
            'parts_receipt_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Security check
        if ($maintenanceRequest->representative_id !== Auth::id()) {
            abort(403);
        }

        // Store the file in 'public/receipts/parts'
        $path = $request->file('parts_receipt_doc')->store('receipts/parts', 'public');

        // Update the database with the file path
        $maintenanceRequest->update([
            'parts_receipt_doc_path' => $path,
        ]);

        return back()->with('success', 'تم رفع مستند استلام القطع بنجاح.');
    }

    /**
     * ✅ Handles the upload for the "Installation Complete" document and updates status.
     */
    public function uploadInstallReceipt(Request $request, MaintenanceRequest $maintenanceRequest)
    {
        $request->validate([
            'install_complete_doc' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        // Security check
        if ($maintenanceRequest->representative_id !== Auth::id()) {
            abort(403);
        }

        // Store the file in 'public/receipts/installation'
        $path = $request->file('install_complete_doc')->store('receipts/installation', 'public');

        // Update the database, and change the status to 'Pending Review' for the admin
        $maintenanceRequest->update([
            'install_complete_doc_path' => $path,
            'status' => 'Pending Review',
        ]);

        return redirect()->route('representative.dashboard')->with('success', 'تم رفع مستند التركيب وإرسال الطلب للمراجعة.');
    }

    // You can add the other methods here as well...

    public function showCompletedTasks()
    {
        $representative = Auth::guard('representative')->user();
        $completedRequests = MaintenanceRequest::with('user')
            ->where('representative_id', $representative->id)
            ->where('status', 'Completed')
            ->orderBy('completed_at', 'desc')
            ->paginate(15);
        return view('representative.completed_tasks', [
            'requests' => $completedRequests
        ]);
    }

    public function showPerformance()
    {
        $representative = Auth::guard('representative')->user();
        $performanceStats = [
            'total_completed' => MaintenanceRequest::where('representative_id', $representative->id)
                ->where('status', 'Completed')->count(),
            'total_value' => MaintenanceRequest::where('representative_id', $representative->id)
                ->where('status', 'Completed')->sum('total_cost'),
            'avg_completion_time_days' => MaintenanceRequest::where('representative_id', $representative->id)
                ->where('status', 'Completed')
                ->whereNotNull(['assigned_at', 'completed_at'])
                ->select(DB::raw('AVG(DATEDIFF(completed_at, assigned_at)) as avg_days'))
                ->value('avg_days'),
        ];
        return view('representative.performance', [
            'stats' => $performanceStats
        ]);
    }

    public function destroy($id)
    {
        $representative = Representative::findOrFail($id);
        $representative->delete();
        
        return redirect()->route('admin.representatives_admin')->with('success', 'تم حذف المندوب بنجاح.');
    }

    public function toggleStatus(Representative $representative)
{
    $representative->activated_at = $representative->activated_at ? null : now();
    $representative->save();
    
    return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
}

}