<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FaqController;
use App\Http\Controllers\Api\V1\MenuController;
use App\Http\Controllers\Api\V1\PageController;
use App\Http\Controllers\Api\V1\PostController;
use App\Http\Controllers\Api\V1\QuestionController;
use App\Http\Controllers\Api\V1\QuestionTopicController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\SlideController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Version 1 Public REST API for Flutter Mobile & Web
Route::prefix('v1')->name('api.v1.')->group(function () {
    // CMS Endpoints
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/featured', [PostController::class, 'featured'])->name('posts.featured');
    Route::get('/posts/{slug}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');
    Route::get('/slides', [SlideController::class, 'index'])->name('slides.index');
    Route::get('/menus/{location}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    // Public Inquiries & FAQ Endpoints
    Route::get('/question-topics', [QuestionTopicController::class, 'index'])->name('question-topics.index');
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions', [QuestionController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('questions.store');
    Route::get('/faqs', [FaqController::class, 'index'])->name('faqs.index');
});
