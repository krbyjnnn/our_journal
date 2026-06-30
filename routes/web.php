<?php

use App\Http\Controllers\AuthController;
use App\Models\Entry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Separate Login Views
Route::get('/login-kerb', function () {
    return view('login-kerb');
});

Route::get('/login-yannie', function () {
    return view('login-yannie');
});

// Authentication Handling
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Secure Dashboard Split Router
Route::get('/dashboard', function () {
    $user = Auth::user();
    $entries = Entry::where('user_id', $user->id)->latest()->get();
    
    // Exact name matching track split
    if ($user->name === 'Kerb') {
        return view('dashboard-kerb', compact('entries'));
    }
    
    return view('dashboard-yannie', compact('entries'));
})->middleware('auth')->name('dashboard');

// Save entry action handler
Route::post('/entries', function (Request $request) {
    $request->validate([
        'title'   => 'required|string|max:255',
        'content' => 'required|string',
        'mood'    => 'required|string',
    ]);

    Entry::create([
        'user_id' => Auth::id(),
        'title'   => $request->input('title'),
        'body'    => $request->input('content'), 
        'mood'    => $request->input('mood'),
    ]);

    return redirect()->route('dashboard');
})->middleware('auth');