<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Representative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class RepresentativeController extends Controller
{
    /** Display a list of all representatives. */
    public function index()
    {
        $representatives = Representative::all();
        // This points to your existing view file
        return view('admin.representatives_admin', compact('representatives'));
    }

    /** Show the form for creating a new representative. */
    public function create()
    {
        // This points to your existing view file
        return view('admin.Add_representative');
    }

    /** Store a newly created representative. */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:representatives,email',
            'phone' => 'required|string|max:255|unique:representatives,phone',
            'region' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            
        }
        $validated['password'] = Hash::make($validated['password']);
        $validated['activated_at'] = now();

        Representative::create($validated);
        
        // This redirects to your existing route name
        return redirect()->route('admin.representatives_admin')->with('success', 'تم إضافة المندوب بنجاح.');
    }

    /** Show the form for editing the specified representative. */
    public function edit(Representative $representative)
    {
        return view('admin.edit_representative', compact('representative'));
    }

    /** Update the specified representative. */
    public function update(Request $request, Representative $representative)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:representatives,email,' . $representative->id,
            'phone' => 'required|string|max:255|unique:representatives,phone,' . $representative->id,
            'region' => 'required|string|max:255',
            'password' => ['nullable', 'string', Password::min(8)],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            if ($representative->avatar) Storage::disk('public')->delete($representative->avatar);
           $validated['avatar'] = $request->file('avatar')->store('avatars');
        }
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $representative->update($validated);
        return redirect()->route('admin.representatives_admin')->with('success', 'تم تحديث بيانات المندوب بنجاح.');
    }

    /** Remove the specified representative. */
    public function destroy(Representative $representative)
    {
        if ($representative->avatar) Storage::disk('public')->delete($representative->avatar);
        $representative->delete();
        return redirect()->route('admin.representatives_admin')->with('success', 'تم حذف المندوب بنجاح.');
    }

    /** Toggle the activation status. */
    public function toggleStatus(Representative $representative)
    {
        $representative->activated_at = $representative->activated_at ? null : now();
        $representative->save();
        return response()->json(['success' => true, 'message' => 'تم تغيير الحالة بنجاح.']);
    }

     public function listJson()
    {
        // Fetch only active representatives if you have a status column,
        // otherwise, fetch all.
        $representatives = Representative::whereNotNull('activated_at') // Example for active
                                         ->select('id', 'name')
                                         ->get();
                                         
        return response()->json($representatives);
    }
}