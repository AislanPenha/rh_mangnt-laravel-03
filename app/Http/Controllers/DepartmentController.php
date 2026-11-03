<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    //
    public function index(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $departments = Department::all();

        return view('department.departments', compact('departments'));
    }

    public function newDepartment(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        $departments = Department::all();

        return view('department.add-department');
    }

    public function createDepartment(Request $request): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');
        
        // form validation
        $request->validate(
            [
                'name' => 'required|string|max:50|unique:departments'
            ]
        );

        Department::create([
            'name' => $request->name
        ]);

        return redirect()->route('departments.departments');
    }

    public function editDepartment(int $id): View|RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        if($this->isDepartmentEditableOrDeletable($id)) {
            return redirect()->route('departments.departments');
        }
        // $user = User::with('permissions')->findOrFail($id);
        $department = Department::findOrFail($id);

        return view('department.edit-department', compact('department'));
    }

    public function updateDepartment(Request $request): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        $id = $request->id;

        $request->validate(
            [
                'id' => 'required',
                'name' => 'required|string|min:3|max:50|unique:departments,name,' . $id
            ]
        );
        
        if($this->isDepartmentEditableOrDeletable($id)) {
            return redirect()->route('departments.departments');
        }

        $department = Department::findOrFail($id);

        $department->update([
            'name' => $request->name
        ]);

        return redirect()->route('departments.departments');
    }

    public function deleteDepartment(int $id): View|RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        if($this->isDepartmentEditableOrDeletable($id)) {
            return redirect()->route('departments.departments');
        }

        $department = Department::findOrFail($id);

        // display page for confirmation
        return view('department.delete-department', compact('department'));
    }

    public function deleteDepartmentConfirm(int $id): RedirectResponse
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        if($this->isDepartmentEditableOrDeletable($id)) {
            return redirect()->route('departments.departments');
        }

        $department = Department::findOrFail($id);

        $department->delete();

        return redirect()->route('departments.departments');
    }

    private function isDepartmentEditableOrDeletable(int $id): bool
    {
        return in_array(intval($id), [1, 2]);
    }
}
