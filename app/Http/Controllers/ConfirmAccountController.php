<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ConfirmAccountController extends Controller
{
    public function confirmAccount(string $token): View
    {
        $user = User::where('confirmation_token', $token)->first();

        if(!$user) {
            abort(403, 'Invalid confirmation token');
        }
        
        return view('auth.confirm-account', compact('user'));
    }

    public function confirmSubmit(Request $request): View
    {
        // form validation
        $request->validate(
            [
                'token' => 'required|string|size:60',
                'password' =>[
                    'required',
                    'confirmed',
                    Password::min(8)->max(16)->mixedCase()->numbers(),
                ],
                'password_confirmation' =>[
                    'required',
                    'same:password',
                    Password::min(8)->max(16)->mixedCase()->numbers(),

                ],
            ]
        );
        $user = User::where('confirmation_token', $request->token)->first();
        $user->password = bcrypt($request->password);
        $user->confirmation_token = null;
        $user->email_verified_at = now();
        $user->save();

        return view('auth.welcome')->with('user', $user);
    }
}
