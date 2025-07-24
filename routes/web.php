<?php

use App\Http\Controllers\ProfileController;
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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KnowledgeCenterController;
use App\Http\Controllers\BotConfigController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TelegramController;
use App\Http\Controllers\ManageAdminController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/register', [RegisteredUserController::class, 'register'])->name('register');

Route::get('/chat', [TelegramController::class, 'index']);
Route::post('/send-message', [TelegramController::class, 'sendMessage']);
Route::get('/get-updates', [TelegramController::class, 'getUpdates']);


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
    Route::get('/chart-data', [DashboardController::class, 'getUserChartData']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/chat', [TelegramController::class, 'index'])->name('chat.index');

    Route::get('/knowledge-center', [KnowledgeCenterController::class, 'index'])->name('knowledge.index');
    Route::post('/knowledge-center/upload', [KnowledgeCenterController::class, 'store'])->name('knowledge.store');
    Route::patch('/knowledge-center/{id}/toggle', [KnowledgeCenterController::class, 'toggle'])->name('knowledge.toggle');
    Route::get('/knowledge-center/{id}/view', [KnowledgeCenterController::class, 'stream'])->name('knowledge.view');
    Route::delete('/knowledge-center/{id}', [KnowledgeCenterController::class, 'destroy'])->name('knowledge.center.destroy');
    
    Route::get('/bot-configuration', [BotConfigController::class, 'index'])->name('bot.index');
    Route::post('/bot-config', [BotConfigController::class, 'store'])->name('bot.config.store');
    Route::delete('/bot-config/{id}', [BotConfigController::class, 'destroy'])->name('bot.config.destroy');
    Route::patch('/bot-config/{id}/toggle', [BotConfigController::class, 'toggle'])->name('bot.config.toggle');

    Route::get('/manage-admin', [ManageAdminController::class, 'index'])->name('admin.index');
    Route::post('/manage-admin/store', [ManageAdminController::class, 'store'])->name('admin.store');
    Route::get('/manage-admin/delete/{id}', [ManageAdminController::class, 'destroy'])
    ->name('admin.delete'); 

    
Route::delete('/telegram-users/delete/{chat_id}', [TelegramController::class, 'deleteUser'])->name('telegram.user.delete');

Route::get('/messages/delete/{id}', [DashboardController::class, 'destroy'])
    ->name('messages.delete');
    Route::delete('/messages/{id}', [TelegramController::class, 'destroy'])->name('messages.destroy');


    Route::post('/upload-pdf', [UploadController::class, 'store'])->name('upload.pdf');

});

require __DIR__.'/auth.php';
