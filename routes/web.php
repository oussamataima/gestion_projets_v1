<?php

use App\Http\Middleware\isAdmin;
use App\Http\Middleware\isEmployer;
use App\Http\Middleware\isManager;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/login', 'login')->name('login');
 
// Define the logout
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
 
    return redirect('/');
});
 
// Protected routes here
Route::middleware('auth')->group(function () {
    Volt::route('/', 'employers.index');
    // Employers
    Route::name('employers.')->prefix('employers')->group(function () {
        Volt::route('/', 'employers.index')->name('index');
        Volt::route('/create', 'employers.create')->name('create')->middleware([isAdmin::class]);
        Volt::route('/{user}/edit', 'employers.edit')->name('edit')->middleware([isAdmin::class]);
        Volt::route('/{user}', 'employers.show')->name('show');
    });

    // Managers
    Route::name('managers.')->prefix('managers')->group(function () {
        Volt::route('/', 'managers.index')->name('index');
        Volt::route('/create', 'managers.create')->name('create')->middleware([isAdmin::class]);
        Volt::route('/{user}/edit', 'managers.edit')->name('edit')->middleware([isAdmin::class]);
        Volt::route('/{user}', 'managers.show')->name('show');
    });

    //projects
    Route::name('projects.')->prefix('projects')->group(function () {
        Volt::route('/', 'projects.index')->name('index');
        Volt::route('/create', 'projects.create')->name('create')->middleware([isAdmin::class]);
        Volt::route('/{project}/edit', 'projects.edit')->name('edit')->middleware([isAdmin::class]);
        Volt::route('/{project}', 'projects.show')->name('show');

        // tasks
        Volt::route('/{project}/tasks/create', 'tasks.create')->middleware([isManager::class]);
        Volt::route('/{project}/tasks/{task}/edit', 'tasks.edit')->middleware([isManager::class]);
    });
    // Tasks
    Route::name('tasks.')->prefix('tasks')->group(function () {
        Volt::route('/', 'tasks.index')->name('index')->middleware([isEmployer::class]);
        // Volt::route('/{user}/edit', 'tasks.edit')->name('edit');
        Volt::route('/{task}', 'tasks.show')->name('show')->middleware([isEmployer::class]);
    });
});

