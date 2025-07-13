<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{



// In app/Http/Controllers/AuthController.php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt to log in as a Web user (Client or Admin)
    if (Auth::guard('web')->attempt($credentials)) {
        $user = Auth::guard('web')->user();

        if ($user->role === 'user' && is_null($user->email_verified_at)) {
            Auth::guard('web')->logout();
            return back()->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة.');
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('dashboard')); // Client dashboard
    }

    // If web login fails, attempt to log in as a Representative
    if (Auth::guard('representative')->attempt($credentials)) {
        $representative = Auth::guard('representative')->user();

        if (is_null($representative->activated_at)) {
            Auth::guard('representative')->logout();
            return back()->with('error', 'حسابك غير نشط. يرجى التواصل مع الإدارة.');
        }

        $request->session()->regenerate();
        return redirect()->intended(route('representative.dashboard'));
    }

    // If both fail
    return back()->withErrors([
        'email' => 'البيانات المدخلة لا تتطابق مع سجلاتنا.',
    ])->onlyInput('email');
}
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('representative')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
    public function showLogin() {
        return view('login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'iqama' => 'required|string',
            'mobile' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
        ]);

        User::create([
            'name' => $request->name,
            'iqama' => $request->iqama,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
        ]);

        return redirect()->route('login.form')->with('success', 'تم التسجيل بنجاح');
    }

    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.clients.index', compact('users'));
    }
        
 
}