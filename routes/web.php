<?php

use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::middleware('auth')->group(function() {
    Route::view('/home', 'home')->name('home');
    Route::redirect('/', '/home');

    // user profile page
    Route::get('/user/profile', 
                [ProfileController::class, 'index']
    )->name('user.profile');
    Route::post('/user/profile/update-password', 
                [ProfileController::class, 'updatePassword']
    )->name('user.profile.update-password');
    Route::post('/user/profile/update-user-data', 
                [ProfileController::class, 'updateUserData']
    )->name('user.profile.update-user-data');
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
