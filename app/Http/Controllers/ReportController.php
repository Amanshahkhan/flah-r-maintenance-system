<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\MaintenanceRequest;
use App\Models\Representative;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the main reports page shell.
     */
    public function index()
    {
        return view('admin.reports_admin');
    }

    /**
     * This is the main router for all report data requests.
     */
    public function getReportData(Request $request, $type)
    {
        switch ($type) {
            case 'maintenance':
                return $this->getMaintenanceReport($request);
            case 'representatives':
                return $this->getRepresentativesReport($request);
            // Add other cases here later
            default:
                return response()->json(['error' => 'Invalid report type'], 404);
        }
    }

    /**
     * ✅ THIS IS THE ONLY getMaintenanceReport METHOD YOU SHOULD HAVE
     * Generates a detailed, paginated list of Maintenance Requests, with filters.
     */
    private function getMaintenanceReport(Request $request)
    {
        // Start with a base query and eager load all needed relationships
        $query = MaintenanceRequest::with(['user.contract', 'representative', 'products']);

        // Apply filters from the request URL
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('representative_id')) {
            $query->where('representative_id', $request->representative_id);
        }
        if ($request->filled('contract_id')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('contract_id', $request->contract_id);
            });
        }

        // Order the results and paginate
        $requests = $query->latest()->paginate(25);

        // Get options for the filter dropdowns
        $filterOptions = [
            'representatives' => Representative::select('id', 'name')->get(),
            'contracts' => Contract::select('id', 'contract_number', 'contract_name')->get(),
        ];

        // Return the paginated data and filter options as JSON
        return response()->json([
            'requests' => $requests,
            'filter_options' => $filterOptions,
        ]);
    }

    /**
     * Generates data for the Representatives Performance report.
     */
    private function getRepresentativesReport(Request $request)
    {
        $reps = Representative::query()
            ->withCount(['maintenanceRequests as completed_requests' => function ($query) {
                $query->where('status', 'completed');
            }])
            ->withAvg(['maintenanceRequests as avg_completion_time' => function ($query) {
                $query->where('status', 'completed')->whereNotNull('assigned_at')->whereNotNull('completed_at');
            }], DB::raw('DATEDIFF(completed_at, assigned_at)'))
            ->get(['id', 'name']);
        
        return response()->json($reps);
    }
}