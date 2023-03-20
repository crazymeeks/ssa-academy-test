<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'users', 'middleware' => ['auth']], function($route){
    $route->get('/', [UserController::class, 'index'])->name('users.index');
    $route->get('/trashed', [UserController::class, 'getTrashedUsers'])->name('users.trashed');
    $route->get('/create', [UserController::class, 'createUser'])->name('users.create');
    $route->get('/{id}', [UserController::class, 'editUser'])->name('users.edit');
    $route->get('/{id}/show', [UserController::class, 'showUserDetails'])->name('users.show');
    
    $route->post('/', [UserController::class, 'postCreate'])->name('users.post.create');
    $route->put('/', [UserController::class, 'updateUser'])->name('users.update');
    $route->delete('/', [UserController::class, 'softDeleteUser'])->name('users.soft.delete');
    $route->delete('/permanent', [UserController::class, 'permanentlyDeleteUser'])->name('users.delete');
    $route->post('/restore', [UserController::class, 'restoreUser'])->name('users.restore');
    $route->post('/upload', [UserController::class, 'uploadPhoto'])->name('users.upload');
});
