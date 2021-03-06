<?php

use Spatie\Permission\Models\Permission;

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
    return view('index');
});
Route::get('/test', function () {


    // Permission::create(['name' => 'manage role-permissions']);
    auth()->user()->givePermissionTo('manage role-permissions');
    return auth()->user()->permissions;

    // if(!auth()->user()->can('manage categories')){
    //     return "your notprimted";
    // }
    // return "ok";


});




//Route::get('/verify-link/{user}', function () {
//     if (request()->hasValidSignature()){
//         return view('/');
//     }
//     return 'Faild';
//})->name('verify-link');
//
//Route::get('/test', function () {
//    return \URL::temporarySignedRoute('verify-link',now()->addMinutes(1),['user',1]);
//});


//Route::middleware([
//    'auth:sanctum',
//    config('jetstream.auth_session'),
//    'verified'
//])->group(function () {
//    Route::get('/dashboard', function () {
//        return view('dashboard');
//    })->name('dashboard');
//});
