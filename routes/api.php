<?php

use App\Http\Controllers\API\V1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\V1\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\API\V1\Auth\NewPasswordController;
use App\Http\Controllers\API\V1\Auth\PasswordResetLinkController;
use App\Http\Controllers\API\V1\Auth\RegisteredUserController;
use App\Http\Controllers\API\V1\Auth\VerifyEmailController;
use App\Http\Controllers\API\V1\CRUD\CategoryController;
use App\Http\Controllers\API\V1\CRUD\CommentController;
use App\Http\Controllers\API\V1\CRUD\PostController;
use App\Http\Controllers\API\V1\HomeController;
use App\Http\Controllers\API\V1\Page\PublicPostPageController;
use App\Http\Controllers\API\V1\ProfileController;
use Illuminate\Support\Facades\Route;

/*Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware(['check.public.token'])->group(function () {

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware(['verified'])
        ->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('check.auth.token')
        ->name('logout');

    // protecting the routes for only the authenticated users
    Route::middleware(['check.auth.token'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index']);

        Route::prefix('/app')->name('app.')->group(function () {
            Route::resource('/posts', PostController::class)->names('posts');
            Route::resource('/categories', CategoryController::class)->names('categories');
            Route::resource('/comments', CommentController::class)->names('comments');
        });

    });

    Route::get('/homepagePosts' ,[HomeController::class, 'getHomepagePosts'])->name('getHomepagePosts');
    Route::get('/homepageNavLinks' ,[HomeController::class, 'getNavLinks'])->name('getNavLinks');
    Route::get('/homepageCategories' ,[HomeController::class, 'getCategories'])->name('getCategories');
    Route::get('/homepageRecentPosts' ,[HomeController::class, 'getRecentPosts'])->name('getRecentPosts');

    Route::get('/posts/{slug}' , [PublicPostPageController::class, 'getPost'])->name('getPost');
    Route::get('/posts/{post}/comments' , [PublicPostPageController::class, 'getComments'])->name('getComments');
    Route::post('/posts/{post}/comments' , [PublicPostPageController::class, 'storeComments'])->name('storeComments');

});

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['throttle:6,1'])
    ->name('verification.send');


