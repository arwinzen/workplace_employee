<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthControllerNojwt;
use App\Http\Controllers\AdminController;
use App\Models\User;

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

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('create-user', function(){

    $user = new User();
    $user->name = 'Arwin Goo';
    $user->email = 'arwingoo@invokeisdata.com';
    $user->password = bcrypt('password');
    $user->save();

    return response()->json('user created');
});

Route::get('queue-email', function(){

    $email_list['email'] = 'arwingoo90@gmail.com';
    $email_list['user'] = 'Arwin Goo';

    dispatch(new \App\Jobs\QueueJob($email_list));

    dd('Send Email Successfully');
});


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::prefix('admin')->group(function() {
    Route::any('/', [AdminController::class, 'login']);
    Route::any('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::any('/dashboard/{group}', [AdminController::class, 'display'])->name('display');
    Route::any('/dashboard/user/edit/{id}', [AdminController::class, 'editUsers'])->name('edit.user');
    Route::any('/dashboard/user/delete/{id}', [AdminController::class, 'delete'])->name('delete');
});

