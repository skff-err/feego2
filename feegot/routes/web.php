<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StudentController;

Route::group(['middleware' => ['role:0,1,2']], function () {
    Route::get('/dashboard')->name('dashboard');
    Route::get('/homepage', [UserController::class, 'homepage'])->name('homepage');
});

Route::group(['middleware' => ['role:0']], function () {
    Route::get('/users', [UserController::class, 'index'])->name('manage-users');
    Route::post('/users', [UserController::class, 'store']);
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('deleteUser');
    Route::post('/users/update/{id}', [UserController::class, 'update']);

    Route::get('/admin/verify', [PaymentController::class, 'validatePayments'])->name('validate-payments');
    Route::post('/admin/{id}/approve', [PaymentController::class, 'validate'])->name('payments.validate');
    Route::post('/admin/{id}/reject', [PaymentController::class, 'deny'])->name('payments.deny');

    Route::get('/fees', [FeeController::class, 'index'])->name('manage-fees');
    Route::post('/fees', [FeeController::class, 'store']);
    Route::delete('/fees/{feeID}', [FeeController::class, 'destroy'])->name('deleteFees');
    Route::post('/fees/update/{id}', [FeeController::class, 'update']);
    Route::get('/classes/{year}', [ClassroomController::class, 'getClassesByYear']);


    Route::get('/classrooms', [ClassroomController::class, 'index'])->name('manage-classrooms');
    Route::post('/classrooms', [ClassroomController::class, 'store']);
    Route::delete('/classrooms/{id}', [ClassroomController::class, 'destroy'])->name('deleteClass');
    Route::post('/classrooms/update/{id}', [ClassroomController::class, 'update']);
});

Route::group(['middleware' => ['role:0,1']], function () {
    Route::match(['get', 'post'], '/report', [PaymentController::class, 'report'])->name('payment-reports');
    Route::post('/payment-reports/save', [PaymentController::class, 'save'])->name('payment-reports.save');

    Route::get('/teacher/verify', [PaymentController::class, 'verifyPayments'])->name('verify-payments');
    Route::post('/teacher/{id}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('/teacher/{id}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    Route::get('/students', [StudentController::class, 'index'])->name('manage-students');
    Route::post('/students', [StudentController::class, 'store']);
    Route::delete('/students/{IdentityCardNumber}', [StudentController::class, 'destroy'])->name('deleteStudents');
    Route::post('/students/update/{IdentityCardNumber}', [StudentController::class, 'update']);
});

Route::group(['middleware' => ['role:0,2']], function () {
    // Routes accessible only by guardians (role = 2)
    Route::get('/guardian', 'GuardianController@index');
    Route::get('/history', [PaymentController::class, 'paymentHistory'])->name('payment-history');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('savePayment');
    
    Route::get('/payments/create/{identityCardNumber}', [PaymentController::class, 'index'])->name('makePayment');
    Route::post('/payments', [PaymentController::class, 'store'])->name('savePayment');
    Route::get('/payments/{identityCardNumber}', [PaymentController::class, 'index'])->name('make-payment');
    Route::post('/payments/mock', [PaymentController::class, 'mockPayment'])->name('payment-gateway');
});
Route::get('/classes/{year}', [ClassroomController::class, 'getClassesByYear']);

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';