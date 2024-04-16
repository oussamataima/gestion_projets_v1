<?php

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
        Volt::route('/create', 'employers.create')->name('create');
        Volt::route('/{user}/edit', 'employers.edit')->name('edit');
        Volt::route('/{user}', 'employers.show')->name('show');
    });

    // Managers
    Route::name('managers.')->prefix('managers')->group(function () {
        Volt::route('/', 'managers.index')->name('index');
        Volt::route('/create', 'managers.create')->name('create');
        Volt::route('/{user}/edit', 'managers.edit')->name('edit');
        Volt::route('/{user}', 'managers.show')->name('show');
    });
});


Volt::route('/register', 'register'); 
