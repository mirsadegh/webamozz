<?php

use Sadegh\RolePermissions\Http\Controllers\RolePermissionsController;

Route::group(['middleware' => ['web','auth']],function(){
    Route::resource('role-permissions',RolePermissionsController::class)->middleware("permission:manage role-permissions");

});

