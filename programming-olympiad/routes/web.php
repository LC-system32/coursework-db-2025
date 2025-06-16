<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ParticipantsController;
use App\Http\Controllers\SubmissionsController;
use App\Http\Controllers\TeachersController;

use App\Http\Controllers\ImportController;
use App\Http\Controllers\FilterController;
use App\Http\Controllers\TablesController;
use App\Http\Controllers\AnalyticsController;

use App\Http\Controllers\Auth\LoginController;

Route::get('/participants', [ParticipantsController::class, 'participants'])->name('participants.index');
Route::get('/participants/{id}', [ParticipantsController::class, 'showParticipant'])->name('participants.moreDetails');

Route::get('/submissions', [SubmissionsController::class, 'submissions'])->name('submissions.index');
Route::get('/submissions/{id}', [SubmissionsController::class, 'showSubmission'])->name('submissions.moreDetails');

Route::get('/teachers', [TeachersController::class, 'teachers'])->name('teachers.index');
Route::get('/teachers/{id}', [TeachersController::class, 'showTeacher'])->name('teachers.moreDetails');

Route::prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/', [AnalyticsController::class, 'index'])->name('index');
    Route::get('/teachers', [AnalyticsController::class, 'teachers'])->name('teachers');
    Route::get('/participants', [AnalyticsController::class, 'participants'])->name('participants');
    Route::get('/submissions', [AnalyticsController::class, 'submissions'])->name('submissions');
    Route::get('/tests', [AnalyticsController::class, 'tests'])->name('tests');
});

Route::get('/', function () {
    return view('home');
})->name('home');
Route::get('/filter/{pageType}', [FilterController::class, 'filter'])->name('filter.generic');

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/import', [ImportController::class, 'import'])->name('import.view');
    Route::post('/import/importFile/{file}', [ImportController::class, 'importFile'])->name('import.importFile');

    Route::prefix('tables')->name('table.')->group(function () {
        Route::get('/', [TablesController::class, 'index'])->name('index');
        Route::get('{table}', [TablesController::class, 'list'])->name('list');
        Route::get('{table}/create', [TablesController::class, 'create'])->name('create');
        Route::post('{table}', [TablesController::class, 'store'])->name('store');
        Route::delete('{table}', [TablesController::class, 'destroy'])->name('destroyAll');
    });
});

Route::middleware(['web'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
