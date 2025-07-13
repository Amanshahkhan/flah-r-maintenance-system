<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Contract; // Make sure this is imported
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Carbon; // Import Carbon for timestamp
use Illuminate\Support\Facades\Log; // Ensure Log is imported if you use it
class AdminClientController extends Controller
{
    
    public function index()
    {
        // Eager load the contract relationship to avoid N+1 query problems
        $users = User::where('role', 'user')->with('contract')->latest()->get();
        return view('admin.clients_admin', compact('users'));
    }

   public function store(Request $request)
    {
        // THIS IS THE CORRECTED VALIDATION BLOCK
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'contract_number' => ['nullable', 'string', 'max:255', 'exists:contracts,contract_number'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)],
            'region' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ], [
            'contract_number.exists' => 'رقم العقد المدخل غير موجود في النظام.',
        ]);
        // 2. Find the Contract ID from the Contract Number
        $contractId = null;
        if (!empty($validatedData['contract_number'])) {
            // Find the contract using the number you provided
            $contract = Contract::where('contract_number', $validatedData['contract_number'])->first();
            // Get its barcode (ID)
            $contractId = $contract->id;
        }

        // 3. Create the User with the contract_id link
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'mobile' => $validatedData['mobile'],
            'password' => Hash::make($validatedData['password']),
            'contract_id' => $contractId, // ✅ Use the ID for the database link
            'email_verified_at' => now(),
            'role' => 'user',
              // [ADDED] Save new fields
             'region' => $validatedData['region'],
            'address' => $validatedData['address'],
        ]);

        // 4. Respond with the new user data
        // We load the relationship so we can display the contract number in the table row
        $user->load('contract');
        
        return response()->json([
            'success' => true,
            'message' => 'تم إضافة العميل وربطه بالعقد بنجاح!',
            'user' => $user
        ]);
    }

    /**
     * Toggle the activation status of a user.
     */
    public function toggleStatus(User $client)
    {
        if ($client->email_verified_at) {
            // If active, deactivate
            $client->email_verified_at = null;
            $message = 'تم تعطيل العميل بنجاح.';
        } else {
            // If inactive, activate
            $client->email_verified_at = Carbon::now();
            $message = 'تم تفعيل العميل بنجاح.';
        }

        $client->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => !is_null($client->email_verified_at)
        ]);
    }


/**
     * ✅ ADD THIS NEW METHOD
     * Update the specified client in storage.
     */
    public function update(Request $request, User $client)
    {
        // 1. Validate the data. Note that some rules are different for updating.
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // We ignore the current user's email when checking for uniqueness
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$client->id],
            'mobile' => ['nullable', 'string', 'max:20'],
            'contract_number' => ['nullable', 'string', 'max:255', 'exists:contracts,contract_number'],
            // Password is not required, but if it's present, it must be confirmed
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)],
            'region' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // 2. Find the Contract ID
        $contractId = null;
        if (!empty($validatedData['contract_number'])) {
            $contract = Contract::where('contract_number', $validatedData['contract_number'])->first();
            $contractId = $contract->id;
        }
        $client->contract_id = $contractId;

        // 3. Update the user's other details
        $client->name = $validatedData['name'];
        $client->email = $validatedData['email'];
        $client->mobile = $validatedData['mobile'];
        $client->region = $validatedData['region'];
        $client->address = $validatedData['address'];

        // 4. Only update the password IF a new one was provided
        if (!empty($validatedData['password'])) {
            $client->password = Hash::make($validatedData['password']);
        }

        // 5. Save all the changes
        $client->save();
        
        // 6. Respond with the updated user data
        $client->load('contract'); // Reload the relationship
        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات العميل بنجاح!',
            'user' => $client
        ]);
    }


    // [MODIFIED] Using Route Model Binding for safety and convenience
    public function destroy(User $client)
    {
        try {
            $client->delete();
            return response()->json(['success' => true, 'message' => 'تم حذف العميل بنجاح.']);
        } catch (\Exception $e) {
            Log::error('Client Deletion Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف العميل.'], 500);
        }
    }

}