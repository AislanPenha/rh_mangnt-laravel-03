<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmAccountEmail;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RHManagementController extends Controller
{
    public function home(): View
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');

        // get all colaborators that are not role admin or role rh
        $colaborators = User::withTrashed()
                                ->with('detail', 'department')
                                ->where('role', 'colaborator')
                                ->get();
                                
        return view('colaborators.colaborators', compact('colaborators'));
    }

    public function newColaborator(): View
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');
        $departments = Department::where('id', '>', 2)->get();

        // if there are no departments, abort the request
        if($departments->count() === 0) {
            abort(403, 'There are no departments to add a new colaborator. Pelase contact the system administrator to add a new department.');
        }
        return view('colaborators.add-colaborator', compact('departments'));
    }

    public function createColaborator(Request $request): RedirectResponse
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');

        // form validation
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'select_department' => 'required|exists:departments,id',
                'address' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',
                'city' => 'required|string|max:50',
                'phone' => 'required|string|max:50',
                'salary' => 'required|decimal:2',
                'admission_date' => 'required|date_format:Y-m-d',
            ]
        );

        // check if department id === 2
        if($request->select_department <= 2) {
            return redirect()->route('home');
        }

        // create user confirmation token
        $token = Str::random(60);

        // create new rh user
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->confirmation_token = $token;
        $user->role = 'colaborator';
        $user->department_id = $request->select_department;
        $user->permissions = '["colaborator"]';
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
        return redirect()->route('colaborators.rh.management.home')->with('success', 'Colaborator created successfully');
    }

    public function editColaborator(int $id): View
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');

        $colaborator = User::with('detail')->findOrFail($id);
        $departments = Department::where('id', '>', 2)->get();
        
        return view('colaborators.edit-colaborator', compact('colaborator', 'departments'));

    }

    public function updateColaborator(Request $request): RedirectResponse
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');

        // form validation
        $request->validate(
            [
                'user_id' => 'required|exists:users,id',
                'salary' => 'required|decimal:2',
                'admission_date' => 'required|date_format:Y-m-d',
                'select_department' => 'required|exists:departments,id',
            ]
        );

        // check if departament is valid
        if($request->select_department <= 2) {
            return redirect()->route('home');
        }

        $user = User::with('detail')->findOrFail($request->user_id);
        $user->detail->salary = $request->salary;
        $user->detail->admission_date = $request->admission_date;
        $user->department_id = $request->select_department;

        $user->save();
        $user->detail->save();

        return redirect()->route('colaborators.rh.management.home');
    }

    public function showDetails(int $id): View
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');
        $colaborator = User::with('detail', 'department')->findOrFail($id);

        return view('colaborators.show-details', compact('colaborator'));
    }

    public function deleteColaborator(int $id): View
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');
        $colaborator = User::findOrFail($id);

        // display confirmation page
        return view('colaborators.delete-colaborator', compact('colaborator'));
    }

    public function deleteColaboratorConfirm(int $id): RedirectResponse
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');
        $colaborator = User::findOrFail($id);

        $colaborator->delete();

        // display confirmation page
        return redirect()->route('colaborators.rh.management.home');
    }

    public function restoreColaborator(int $id): RedirectResponse
    {
        Gate::allows('rh') ?: abort(403, 'You are not authorized to access this page');
        $colaborator = User::withTrashed()->findOrFail($id);

        $colaborator->restore();
        
        // display confirmation page
        return redirect()->route('colaborators.rh.management.home')->with('success', 'Colaborator restored with success');;
    }
}
