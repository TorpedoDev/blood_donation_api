<?php

use App\Http\Controllers\User\DonationrequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\BloodbagController;
use App\Http\Controllers\User\DonationdateController;
use App\Models\Donationrequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    
});

Route::group( ['middleware' => 'auth:user'] , function(){

    Route::resource('bloodbag' , BloodbagController::class);
    Route::get('yourbloodrequests' , [BloodbagController::class , 'userRequests']);
    Route::get('approvebloodrequest/{id}' , [BloodbagController::class , 'approve']);
    Route::get('requestsamebloodtype' , [BloodbagController::class , 'sameBloodType']);

    Route::resource('donationrequest' , DonationrequestController::class);
    Route::get('yourdonationrequests' , [DonationrequestController::class , 'userRequests']);
    Route::get('approvedonationrequest/{id}' , [DonationrequestController::class , 'approve']);

    Route::resource('donationdate' , DonationdateController::class);
    Route::get('lastdonationdate' , [DonationdateController::class , 'latestDate']);
    Route::get('yourdonationstate' , [DonationdateController::class , 'donationState']);

});