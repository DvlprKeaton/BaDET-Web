<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\EmergencyTeamController;
use App\Http\Controllers\CasesController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SmsController;

use App\Models\Cases;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [AuthController::class, 'index'])->name('login');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post'); 
Route::get('registration', [AuthController::class, 'registration'])->name('register');
Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post'); 

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

//Map
Route::get('map', [MapController::class, 'index'])->name('map');

//barangay
Route::get('barangay', [BarangayController::class, 'index'])->name('barangay');

//users
Route::get('users', [UsersController::class, 'index'])->name('users');
Route::get('profile', [UsersController::class, 'show'])->name('profile');
Route::get('profileUpdate', [UsersController::class, 'edit'])->name('profileUpdate');
Route::post('update', [UsersController::class, 'update'])->name('update');

//cases
Route::get('cases', [CasesController::class, 'index'])->name('cases');
Route::get('infocases/{id}', [CasesController::class, 'info'])->name('case.info');
Route::get('dispatch/{id}', [CasesController::class, 'dispatch'])->name('case.dispatch');
Route::post('submitDispatch', [CasesController::class, 'store'])->name('submitDispatch');

//emergencyteam
Route::get('team', [EmergencyTeamController::class, 'index'])->name('team');
Route::get('addTeam', [EmergencyTeamController::class, 'create'])->name('addTeam');
Route::post('submitTeam', [EmergencyTeamController::class, 'store'])->name('submitTeam');
Route::get('editTeam/{id}', [EmergencyTeamController::class, 'edit'])->name('response.edit');
Route::get('myTeam/{id}', [EmergencyTeamController::class, 'myTeam'])->name('myTeam');
Route::post('updateTeam', [EmergencyTeamController::class, 'update'])->name('updateTeam');
Route::get('resolve/{id}', [EmergencyTeamController::class, 'resolve'])->name('resolve');

//dashboard
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard'); 

//SMS
Route::post('/send-sms', [SmsController::class, 'sendSms']);
Route::get('/sms', [SmsController::class, 'index']);

//Notification
Broadcast::channel('cases.created', function ($user, Cases $case) {
    return true;
});
