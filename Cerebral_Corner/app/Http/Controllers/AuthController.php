<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show introduction page
    public function showIntroduction()
    {
        return view('introduction');
    }
    
    // Show login page
    public function showLogin()
    {
        return view('login');
    }
    
    // Process login
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        // Try to find member by email
        $member = Member::where('email', $request->email)->first();
        
        // Check if member exists AND password is correct
        if ($member && Hash::check($request->password, $member->password)) {
            // Store member info in session
            Session::put('member_id', $member->id);
            Session::put('member_name', $member->firstname . ' ' . $member->lastname);
            Session::put('member_email', $member->email);
            
            // Redirect to reservation page
            return redirect()->route('reservation.index')->with('success', 'Welcome back, ' . $member->firstname . '!');
        }
        
        // If login fails - redirect to login-failed page
        return redirect()->route('login.failed');
    }
    
    // Show login failed page
    public function loginFailed()
    {
        return view('login-failed');
    }
    
    // Show registration page
    public function showRegister()
    {
        return view('register');
    }
    
    // Process registration
    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'lastname' => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:members,email',
            'password' => 'required|min:6|confirmed',
        ]);
        
        // Create new member
        $member = Member::create([
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }
    
    // Show dashboard (optional)
    public function dashboard()
    {
        // Check if user is logged in
        if (!Session::has('member_id')) {
            return redirect()->route('login');
        }
        
        return view('dashboard', [
            'member_name' => Session::get('member_name'),
            'member_email' => Session::get('member_email'),
        ]);
    }
    
    // Logout
    public function logout()
    {
        // Clear all session data
        Session::flush();
        
        // Redirect to introduction page
        return redirect()->route('introduction')->with('success', 'You have been logged out. Farewell, hero!');
    }
}
