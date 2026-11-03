<?php

use App\Http\Controllers\ConfirmAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RhUserController;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::middleware('guest')->group(function() {
    // email confirmation and password definition
    Route::get('/confirmation_account/{token}', [ConfirmAccountController::class, 'confirmAccount'])->name('confirm-account');
    Route::post('/confirmation_account', [ConfirmAccountController::class, 'confirmSubmit'])->name('confirm-submit');
});

Route::middleware('auth')->group(function() {
    Route::view('/home', 'home')->name('home');
    Route::redirect('/', '/home');

    // profile route
    Route::prefix('/user/profile')->name('user.')->group(function () {
        Route::get('/',                  [ProfileController::class, 'index'])         ->name('profile');
        Route::post('/update-password',  [ProfileController::class, 'updatePassword'])->name('update-password');
        Route::post('/update-user-data', [ProfileController::class, 'updateUserData'])->name('update-user-data');
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
    Route::prefix('rh-users')->name('colaborators.')->group(function () {

        Route::get('/',                        [RhUserController::class, 'index'])              ->name('rh-users');
        Route::get('/new-colaborator',         [RhUserController::class, 'newRhColaborator'])   ->name('new-colaborator');
        Route::post('/create-colaborator',     [RhUserController::class, 'createRhColaborator'])->name('create-colaborator');
        Route::get('/edit-colaborator/{id}',   [RhUserController::class, 'editRhColaborator'])  ->name('edit-colaborator');
        Route::post('/update-colaborator',     [RhUserController::class, 'updateRhColaborator'])->name('update-colaborator');
        Route::get('/delete-colaborator/{id}', [RhUserController::class, 'deleteRhColaborator'])->name('delete-colaborator');
        Route::get('/delete-colaborator-confirm/{id}',[RhUserController::class,'deleteRhColaboratorConfirm'])->name('delete-colaborator-confirm');
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
