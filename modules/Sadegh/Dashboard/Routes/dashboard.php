<?php

use Sadegh\Dashboard\Http\Controllers\DashboardController;
Route::group(['middleware'=> ['web','auth']],function(){
Route::get('/home',[DashboardController::class,'home'])->name('home');
});
