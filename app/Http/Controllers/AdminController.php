<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class AdminController extends Controller
{
    public function home(): View
    {
        Gate::allows('admin') ?: abort(403, 'You are not authorized to access this page');

        // get total number of colaborators (deleted_at is null)
        $data['total_colaborators'] = User::whereNull('deleted_at')->count();
        
        // get colaborators deleted
        $data['total_colaborators_deleted'] = User::onlyTrashed()->count();
        
        // total salary for all colaborators
        $data['total_salary'] = User::withoutTrashed()
                                    ->with('detail')
                                    ->get()->sum(function(User $colaborator){
                                        return $colaborator->detail->salary;
                                    });
        $data['total_salary'] = number_format($data['total_salary'], 2, ',', '.'). ' $';

        // total colaborators by department
        $data['total_colaborators_per_department'] = User::withoutTrashed()
                                    ->with('department')
                                    ->get()
                                    ->groupBy('department_id')
                                    ->map(function(Collection $department){
                                        return [
                                            'department' => $department->first()->department->name ?? '-',
                                            'total' => $department->count(),
                                        ];
                                    });
        // total salary by department
        $data['total_salary_by_department'] = User::withoutTrashed()
                                    ->with('department', 'detail')
                                    ->get()
                                    ->groupBy('department_id')
                                    ->map(function(Collection $department){
                                        return [
                                            'department' => $department->first()->department->name ?? '-',
                                            'total' => $department->sum(function(User $colaborator){
                                                return $colaborator->detail->salary;
                                            }),
                                        ];
                                    });
        $data['total_salary_by_department'] = $data['total_salary_by_department']->map(function(array $departament){
            return [
                'department' => $departament['department'],
                'total' => number_format($departament['total'], 2, ',', '.'). ' $'
            ];
        });
        // display admin home page
        return view('home', compact('data'));
    }
}
