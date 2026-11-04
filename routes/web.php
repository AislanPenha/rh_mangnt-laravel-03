<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ColaboratorsController;
use App\Http\Controllers\ConfirmAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RHManagementController;
use App\Http\Controllers\RhUserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
// use App\Models\User;
// use Illuminate\Mail\Message;
// use Illuminate\Support\Facades\Mail;

Route::middleware('guest')->group(function() {
    // email confirmation and password definition
    Route::get('/confirmation_account/{token}', [ConfirmAccountController::class, 'confirmAccount'])->name('confirm-account');
    Route::post('/confirmation_account', [ConfirmAccountController::class, 'confirmSubmit'])->name('confirm-submit');
});

Route::middleware('auth')->group(function() {
    Route::redirect('/', '/home');
    Route::get('/home', function(){
        
    // check if user is admin
    if(Auth::user()->role === 'admin') {
        return redirect()->route('colaborators.admin.home');
    } elseif(Auth::user()->role === 'rh') {
        return redirect()->route('colaborators.rh.management.home');
    } else {
        return redirect()->route('colaborators.colaborator');
    }
    })->name('home');

    // profile route
    Route::prefix('/user/profile')->name('user.')->group(function () {
        Route::get('/',                  [ProfileController::class, 'index'])         ->name('profile');
        Route::post('/update-password',  [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/update-user-data', [ProfileController::class, 'updateUserData'])->name('update-user-data');
        Route::post('/update-user-address', [ProfileController::class, 'updateUserAddress'])->name('update-user-address');
    });

    // department route
    Route::prefix('departments')->name('departments.')->group(function () {
        Route::get('/',                       [DepartmentController::class, 'index'])           ->name('departments');
        Route::get('/new-department',         [DepartmentController::class, 'newDepartment'])   ->name('new-department');
        Route::post('/create-department',     [DepartmentController::class, 'createDepartment'])->name('create-department');
        Route::get('/edit-department/{id}',   [DepartmentController::class, 'editDepartment'])  ->name('edit-department');
        Route::post('/update-department',     [DepartmentController::class, 'updateDepartment'])->name('update-department');
        Route::get('/delete-department/{id}', [DepartmentController::class, 'deleteDepartment'])->name('delete-department');
        Route::get('/delete-department-confirm/{id}',[DepartmentController::class,'deleteDepartmentConfirm'])->name('delete-department-confirm');
    });


    // rh colaborator routes
    Route::prefix('rh-users')->name('colaborators.rh.')->group(function () {

        Route::get('/',                        [RhUserController::class, 'index'])              ->name('rh-users');
        Route::get('/new-colaborator',         [RhUserController::class, 'newRhColaborator'])   ->name('new-colaborator');
        Route::post('/create-colaborator',     [RhUserController::class, 'createRhColaborator'])->name('create-colaborator');
        Route::get('/edit-colaborator/{id}',   [RhUserController::class, 'editRhColaborator'])  ->name('edit-colaborator');
        Route::post('/update-colaborator',     [RhUserController::class, 'updateRhColaborator'])->name('update-colaborator');
        Route::get('/delete-colaborator/{id}', [RhUserController::class, 'deleteRhColaborator'])->name('delete-colaborator');
        Route::get('/restore-colaborator/{id}', [RhUserController::class, 'restoreRhColaborator'])->name('restore-colaborator');
        Route::get('/delete-colaborator-confirm/{id}',[RhUserController::class,'deleteRhColaboratorConfirm'])->name('delete-colaborator-confirm');

        Route::get('/management/home', [RHManagementController::class, 'home'])->name('management.home');
        Route::get('/management/new-colaborator', [RHManagementController::class, 'newColaborator'])->name('management.new');
        Route::post('/management/create', [RHManagementController::class, 'createColaborator'])->name('management.create');
        Route::get('/management/edit/{id}', [RHManagementController::class, 'editColaborator'])->name('management.edit');
        Route::get('/management/details/{id}', [RHManagementController::class, 'showDetails'])->name('management.details');
        Route::post('/management/update', [RHManagementController::class, 'updateColaborator'])->name('management.update');
        Route::get('/management/delete/{id}', [RHManagementController::class, 'deleteColaborator'])->name('management.delete');
        Route::get('/management/delete-confirm/{id}', [RHManagementController::class, 'deleteColaboratorConfirm'])->name('management.delete-confirm');
        Route::get('/management/restore/{id}', [RHManagementController::class, 'restoreColaborator'])->name('management.restore');
    });
        
    // admin colaborators routes
    Route::prefix('colaborators')->name('colaborators.')->group(function () {
        Route::get('/',[ColaboratorsController::class,'index'])->name('all-colaborators');
        Route::get('/details/{id}',[ColaboratorsController::class,'showDetails'])->name('details');
        Route::get('/delete/{id}',[ColaboratorsController::class,'deleteColaborator'])->name('delete');
        Route::get('/restore/{id}', [ColaboratorsController::class, 'restoreColaborator'])->name('restore');
        Route::get('/delete-confirm/{id}',[ColaboratorsController::class,'deleteColaboratorConfirm'])->name('delete-confirm');

        // admin routes
        Route::get('/admin/home', [AdminController::class, 'home'])->name('admin.home');

        // colaborator routes
        Route::get('/colaborator', [ColaboratorsController::class, 'home'])->name('colaborator');
    });

});

// Route::get('/admin', function(){
//     $admin = User::with('detail', 'department')->find(1);
//     return view('admin', compact('admin'));
// });

// Route::get('/login', function(){
//     return view('auth.login');
// });

// Route::get('/email', function () {
//     try {
//         Mail::raw('Mensagem do Controle de RH MANGNT', function (Message $message) {
//             $message->to('teste@gmail.com')
//                     ->subject('Controlando tudo')
//                     ->from('controle@rhmangnt.com');
//         });

//         echo 'email enviado com sucesso.';

//     } catch (Exception $e) {

//         echo 'Error: '. $e;
//     }
// });
