<?php

use App\Http\Controllers\CreditController;
use App\Http\Controllers\Feature1Controller;
use App\Http\Controllers\Feature2Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard',);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/feature1', function () {
    $controller = new Feature1Controller();
    return app()->call([$controller, 'index']);
})->middleware(['auth', 'verified'])->name('feature1.index');

Route::post('/feature1/calculate', function () {
    $controller = new Feature1Controller();
    return app()->call([$controller, 'calculate']);
})->middleware(['auth', 'verified'])->name('feature1.calculate');

Route::get('/feature2', function () {
    $controller = new Feature2Controller();
    return app()->call([$controller, 'index']);
})->middleware(['auth', 'verified'])->name('feature2.index');

Route::post('/feature2/calculate', function () {
    $controller = new Feature2Controller();
    return app()->call([$controller, 'calculate']);
})->middleware(['auth', 'verified'])->name('feature2.calculate');






//The above approach (declaring new creditController instance) is not right for the way the buy-credits controller is built. If we assign a new instance, 
//the $request (value asigned in the api call) will not be injected by laravel for obvious reasons. I would need that my controller had a constructor that would access the $request,
// and then access it by the  $this class reference and change the way the code in controller was done in the tutorial.
Route::get('/buy-credits', [CreditController::class, 'index'])->middleware(['auth', 'verified'])->name('credit.index');
Route::get('/buy-credits/success', [CreditController::class, 'success'])->middleware(['auth', 'verified'])->name('credit.success');
Route::get('/buy-credits/cancel', [CreditController::class, 'cancel'])->middleware(['auth', 'verified'])->name('credit.cancel');

Route::post('/buy-credits/{package}', [CreditController::class, 'buyCredits'])->middleware(['auth', 'verified'])->name('credit.buy');
Route::post('/buy-credits/webhook', [CreditController::class, 'webhook'])->name('credit.webhook');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
