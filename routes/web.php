<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\SuggestionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/profil', [PageController::class, 'profile'])->name('profile');

Route::get('/cerita', [StoryController::class, 'index'])->name('stories.index');
Route::get('/cerita/{story}', [StoryController::class, 'show'])->name('stories.show');

Route::get('/lagu', [SongController::class, 'index'])->name('songs.index');

Route::get('/saran', [SuggestionController::class, 'index'])->name('suggestions.index');
Route::post('/saran', [SuggestionController::class, 'store'])->name('suggestions.store');

