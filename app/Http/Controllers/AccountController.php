<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.account');
    }

    public function updateEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|max:255|unique:users,email,'.Auth::user()->id.',id'
        ]);

        Auth::user()->update(['email' => request('email')]);

        session()->flash('success', 'Email update successful!');
        return redirect('/admin/account');
    }

    public function updatePassword(Request $request)
    {
        $this->validate($request, [
            'Old_Password' => 'required|min:6',
            'New_Password' => 'required|min:6'
        ]);

        if(!Hash::check(request('Old_Password'), Auth::user()->password))
            return redirect()->back()->withErrors('Incorrect old password');

        Auth::user()->update([
            'password' => bcrypt(request('New_Password'))
        ]);

        session()->flash('success', 'Password update successful!');
        return redirect('/admin/account');
    }
}
