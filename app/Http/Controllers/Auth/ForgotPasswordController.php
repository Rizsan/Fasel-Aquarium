<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return back()->with('success', __($status));
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => request('email')
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:6|confirmed'
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect('/login')->with('success','Password berhasil diubah.');
        }

        return back()->withErrors([
            'email'=>__($status)
        ]);
    }
}