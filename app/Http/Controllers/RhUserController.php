<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class RhUserController extends Controller
{
    public function index(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $colaborators = User::where('role', 'rh')->get();

        return view('colaborators.rh-users', compact('colaborators'));
    }

    public function newColaborator(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $departments = Department::all();

        return view('colaborators.add-rh-users', compact('departments'));
    }

    public function createColaborator(Request $request): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        // form validation
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'select_department' => 'required|exists:departments,id',
            ]
        );

        // create new rh user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = 'rh';
        $user->department_id = $request->select_department;
        $user->permissions = '["rh"]';
        $user->save();
        return redirect()->route('colaborators.rh-users')->with('success', 'Colaborator created successfully');
    }
}
