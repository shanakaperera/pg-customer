<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\PkgUsersController;
use App\Http\Controllers\PlayersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MilesController;
use App\Http\Controllers\ChipsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Pkg Users

Route::get('pkg-users', [PkgUsersController::class, 'users'])
    ->name('pkg-users')
    ->middleware('auth');

Route::get('pkg-users/{email}/get', [PkgUsersController::class, 'show'])
    ->name('pkg-users.show')
    ->middleware('auth');

// Miles

Route::get('miles', [MilesController::class, 'index'])
    ->name('miles')
    ->middleware('auth');

Route::get('miles/create', [MilesController::class, 'create'])
    ->name('miles.create')
    ->middleware('auth');

Route::post('miles', [MilesController::class, 'store'])
    ->name('miles.store')
    ->middleware('auth');

Route::get('miles/{mile}/edit', [MilesController::class, 'edit'])
    ->name('miles.edit')
    ->middleware('auth');

Route::put('miles/{mile}', [MilesController::class, 'update'])
    ->name('miles.update')
    ->middleware('auth');

Route::delete('miles/{mile}', [MilesController::class, 'destroy'])
    ->name('miles.destroy')
    ->middleware('auth');

// Players

Route::get('players', [PlayersController::class, 'index'])
    ->name('players')
    ->middleware('auth');

Route::get('pkg-players', [PlayersController::class, 'players'])
    ->name('pkg-players')
    ->middleware('auth');

Route::get('players/player/show', [PlayersController::class, 'show'])
    ->name('players.show')
    ->middleware('auth');


Route::get('players/create', [PlayersController::class, 'create'])
    ->name('players.create')
    ->middleware('auth');

Route::post('players', [PlayersController::class, 'store'])
    ->name('players.store')
    ->middleware('auth');

Route::get('players/{player}/edit', [PlayersController::class, 'edit'])
    ->name('players.edit')
    ->middleware('auth');

Route::put('players/{player}', [PlayersController::class, 'update'])
    ->name('players.update')
    ->middleware('auth');

Route::delete('players/{player}', [PlayersController::class, 'destroy'])
    ->name('players.destroy')
    ->middleware('auth');

// Chips

Route::get('chips', [ChipsController::class, 'index'])
    ->name('chips')
    ->middleware('auth');

Route::get('chips/create', [ChipsController::class, 'create'])
    ->name('chips.create')
    ->middleware('auth');

Route::post('chips', [ChipsController::class, 'store'])
    ->name('chips.store')
    ->middleware('auth');

Route::get('chips/{chip}/edit', [ChipsController::class, 'edit'])
    ->name('chips.edit')
    ->middleware('auth');

Route::put('chips/{chip}', [ChipsController::class, 'update'])
    ->name('chips.update')
    ->middleware('auth');

Route::delete('chips/{chip}', [ChipsController::class, 'destroy'])
    ->name('chips.destroy')
    ->middleware('auth');

