<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'lastname'   => 'required|string|max:255',
            'firstname'  => 'required|string|max:255',
            'address'    => 'required|string',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email|unique:members,email',
            'password'   => 'required|min:6|confirmed',
        ]);

        Member::create([
            'lastname'  => $request->lastname,
            'firstname' => $request->firstname,
            'address'   => $request->address,
            'phone'     => $request->phone,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('login')
                         ->with('success', 'Registration successful! Please login.');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $member = Member::where('email', $request->email)->first();

        if ($member && Hash::check($request->password, $member->password)) {
            Session::put('user_id', $member->id);
            Session::put('user_email', $member->email);

            return redirect()->route('reservation.index');
        }

        return redirect()->route('login')
                        ->withErrors(['email' => 'Invalid email or password.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('introduction');
    }
}