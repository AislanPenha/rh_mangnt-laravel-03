<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ColaboratorsController extends Controller
{
    public function index(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $colaborators = User::withTrashed()
                                ->with('detail', 'department')
                                ->where('role', '<>', 'admin')
                                ->get();
        
        return view('colaborators.admin-all-colaborators')->with('colaborators', $colaborators);
    }

    public function showDetails(int $id): View|RedirectResponse
    {
        Gate::allows('admin', 'rh') ?: abort(403, 'You are not authorized to access this page');

        // check if id is the same as the auth user
        if(Auth::user()->id === $id) {
            return redirect()->route('home');
        }

        $colaborator = User::findOrFail($id);

        return view('colaborators.show-details', compact('colaborator'));
    }

    public function deleteColaborator(int $id): View|RedirectResponse
    {
        Gate::allows('admin', 'rh') ?: abort(403, 'You are not authorized to access this page');
        // check if id is the same as the auth user
        if(Auth::user()->id === $id) {
            return redirect()->route('home');
        }
        $colaborator = User::findOrFail($id);
        
        return view('colaborators.delete-colaborator-confirm')->with('colaborator', $colaborator);
    }

    public function deleteColaboratorConfirm(int $id): RedirectResponse
    {
        Gate::allows('admin', 'rh') ?: abort(403, 'You are not authorized to access this page');
        // check if id is the same as the auth user
        if(Auth::user()->id === $id) {
            return redirect()->route('home');
        }
        $colaborator = User::findOrFail($id);
        $colaborator->delete();

        return redirect()->route('colaborators.all-colaborators');
    }

    public function restoreColaborator(int $id): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $colaborator = User::withTrashed()->findOrFail($id);
        $colaborator->restore();

        return redirect()->route('colaborators.all-colaborators');
    }

    public function home(): View
    {
        Gate::allows('colaborator') ?: abort(403, 'You are not authorized to access this page');

        // get colaborator data
        $colaborator = User::with('detail', 'department')
                                ->where('id', Auth::user()->id)
                                ->first();
        
        return view('colaborators.show-details', compact('colaborator'));
    }
}
