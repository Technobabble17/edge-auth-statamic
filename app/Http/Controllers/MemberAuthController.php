<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Statamic\Facades\Entry;
use Illuminate\Support\Str;

class MemberAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.member.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('member')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/members/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegister()
    {
        return view('auth.member.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:entries,data->email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $entry = Entry::make()
            ->collection('members')
            ->slug(Str::slug($validated['email']))
            ->data([
                'title' => $validated['name'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

        $entry->save();

        $member = Member::fromEntry($entry);
        Auth::guard('member')->login($member);

        return redirect('/members/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
