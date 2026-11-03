<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RhUserController extends Controller
{
    public function index(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        // $colaborators = User::where('role', 'rh')->get();
        $colaborators = User::with('detail')
                                ->where('role', 'rh')
                                ->orderBy('id', 'desc')
                                ->get();
        return view('colaborators.rh-users', compact('colaborators'));
    }

    public function newRhColaborator(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $departments = Department::all();

        return view('colaborators.add-rh-users', compact('departments'));
    }

    public function createRhColaborator(Request $request): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        // form validation
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'select_department' => 'required|exists:departments,id|in:2',
                'address' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
                'city' => 'required|string|max:50',
                'phone' => 'required|string|max:50',
                'salary' => 'required|decimal:2',
                'admission_date' => 'required|date_format:Y-m-d',
            ]
        );

        // check if department id === 2
        if($request->select_department != 2) {
            return redirect()->route('home');
        }

        // create user confirmation token
        $token = Str::random(60);

        // create new rh user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = 'rh';
        $user->department_id = $request->select_department;
        $user->permissions = '["rh"]';
        $user->save();

        // save user details
        $user->detail()->create([
            'address' => $request->address,
            'zip_code' => $request->zip_code,
            'city' => $request->city,
            'phone' => $request->phone,
            'salary' => $request->salary,
            'admission_date' => $request->admission_date,
        ]);

        // send email to user
        $url = route('confirm-account', $token);
        Mail::to($user->email)->send(new ConfirmAccountEmail($url));
        return redirect()->route('colaborators.rh-users')->with('success', 'Colaborator created successfully');
    }

    public function editRhColaborator(int $id): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        $colaborator = User::with('detail')->where('role', 'rh')->findOrFail($id);


        return view('colaborators.edit-rh-users', compact('colaborator'));
    }

    public function updateRhColaborator(Request $request): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        // form validation
        $request->validate(
            [
                'user_id' => 'required|exists:users,id',
                'salary' => 'required|decimal:2',
                'admission_date' => 'required|date_format:Y-m-d',
            ]
        );

        $user = User::findOrFail($request->user_id);

        $user->detail->update([
            'salary' => $request->salary,
            'admission_date' => $request->admission_date
        ]);

        return redirect()->route('colaborators.rh-users')->with('success', 'Colaborator updated successfully');
    }

    public function deleteRhColaborator(int $id): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        $colaborator = User::findOrFail($id);

        // display page for confirmation
        return view('colaborators.delete-rh-users', compact('colaborator'));
    }

    public function deleteRhColaboratorConfirm(int $id): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');



        $colaborator = User::findOrFail($id);

        $colaborator->delete();

        return redirect()->route('colaborators.rh-users')->with('success', 'Colaborator deleted successfully');
    }
}
