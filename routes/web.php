<?php

use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::middleware('auth')->group(function() {
    Route::view('/home', 'home')->name('home');
    Route::redirect('/', '/home');
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
