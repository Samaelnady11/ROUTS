<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\GeofencingController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Api\ChildController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\StudentController; // إضافة المسار
use App\Http\Controllers\BusLocationController;
use App\Http\Controllers\TestGeofencingController;
use App\Http\Controllers\UserController;

// Authentication Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

// Routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('/parent/update', [UserController::class, 'updateParentProfile'])->name('parent.update');
});

// Students Routes
Route::apiResource('students', StudentController::class);
Route::post('students/{id}/check-geofence', [StudentController::class, 'checkGeofence']);

// Clients Routes
Route::apiResource('clients', ClientController::class);

// Buses Routes
Route::apiResource('buses', BusController::class);
Route::post('/bus/location/update', [BusController::class, 'updateLocation']);
Route::put('buses/{bus}/location', [BusLocationController::class, 'updateLocation'])
    ->name('buses.location.update');

// Routes for Bus Routes
Route::apiResource('routes', RouteController::class);


// Simulate Bus Movement
// Route::post('/test-geofencing/{bus}', [TestGeofencingController::class, 'simulateMovement']);

// Test FCM Notification
Route::post('/test-notification', [TestGeofencingController::class, 'testNotification']);
Route::post('/test-geofencing/{bus}', [TestGeofencingController::class, 'testCheckProximity']);
// Geofencing Routes
Route::post('/geofence/check', [GeofencingController::class, 'checkLocation']);
Route::post('/geofence', [GeofencingController::class, 'store']);

// Social Login Routes
Route::post('/auth/facebook-login', [SocialAuthController::class, 'facebookLogin']);
Route::get('/auth/facebook-callback', [SocialAuthController::class, 'handleCallback']);
Route::post('/google-login', [GoogleAuthController::class, 'googleLogin']);

// Notification Routes
Route::post('/send-emergency-notification', [NotificationController::class, 'sendEmergencyNotification']);

// Child Routes
Route::post('/children', [ChildController::class, 'store']);
Route::get('/children', [ChildController::class, 'index']);
//Route::get('/children/{id}', [ChildController::class, 'show']);

// SMS Routes
Route::post('/send-sms', [SMSController::class, 'sendSMS']);

// Student Details Route
Route::get('student-details', [StudentController::class, 'getStudentDetails']);

// Add these routes to your existing api.php file
Route::prefix('test')->group(function () {
    Route::post('simulate-movement/{bus}', [TestGeofencingController::class, 'simulateMovement']);
    Route::post('notification', [TestGeofencingController::class, 'testNotification']);
    Route::post('check-proximity/{bus}', [TestGeofencingController::class, 'checkProximity']);
});
