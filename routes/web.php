<?php

use App\Http\Controllers\saveNotificationController;
use App\Http\Controllers\WebSocketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// Route::get('notifications',[saveNotificationController::class, 'saveNotification'])->name('save');
Auth::routes();
    
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/send-message', [WebSocketController::class, 'send_message']);
Route::get('/my-groups', [WebSocketController::class, 'my_groups']);
Route::post('/send-group-message', [WebSocketController::class, 'send_group_message']);

