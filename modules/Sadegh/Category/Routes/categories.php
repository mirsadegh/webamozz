<?php

use Sadegh\Category\Http\Controllers\CategoryController;



Route::group(['middleware' => ['web','auth']],function(){
    Route::resource('categories',CategoryController::class)->middleware("permission:manage categories");

});

