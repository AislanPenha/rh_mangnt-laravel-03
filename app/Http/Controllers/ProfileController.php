<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    //
    public function index(): View
    {
        $colaborator = User::with('detail', 'department')
                                ->findOrFail(Auth::user()->id);
                                
        return view('user.profile', compact('colaborator'));
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        // form validation
        $request->validate(
            [
                'current_password' => 'required|min:8|max:16',
                'new_password' => 'required|min:8|max:16|different:current_password',
                'new_password_confirmation' => 'required|same:new_password',
            ]
        );

        // Ajuda meu vscode a identificar essa $user
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = Auth::user();

        // check if the current password is correct
        if(!password_verify($request->current_password, $user->password)){
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();
        return redirect()->back()->with('success', 'Password updated successfully.');
    }

    public function updateUserData(Request $request): RedirectResponse
    {
        // form validation
        $request->validate(
            [
                'name' => 'required|min:3|max:255',
                // 'email' => 'required|email|max:255|unique:users,email,'. Auth::id(),
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore(Auth::id()),
                ],
            ]
        );

        // update user data
        /** @var \Illuminate\Foundation\Auth\User $user */
        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success_change_data', 'Profile updated successfully.');
    }

    public function updateUserAddress(Request $request): RedirectResponse
    {
        // form validation
        $request->validate(
            [
                'address' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
                'city' => 'required|string|max:50',
                'phone' => 'required|string|max:50',
            ]
        );

        // get user detail
        $user = User::with('detail')->findOrFail(Auth::user()->id);

        $user->detail->address = $request->address;
        $user->detail->zip_code = $request->zip_code;
        $user->detail->city = $request->city;
        $user->detail->phone = $request->phone;
        $user->detail->save();

        return redirect()->back()->with('success_change_address', 'Profile updated successfully.');
    }
}
