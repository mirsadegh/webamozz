<?php

namespace Sadegh\RolePermissions\Providers;

use Illuminate\Support\ServiceProvider;


class RolePermissionServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/role_permissions_routes.php');
        $this->loadMigrationsFrom(__DIR__."/../Database/Migrations/");
        $this->loadViewsFrom(__DIR__."/../Resources/Views/","RolePermissions");
        $this->loadJsonTranslationsFrom(__DIR__."/../Resources/lang");
    }

    public function boot()
    {
        config()->set('sidebar.items.role-permissions',[
            "icon" => "i-role-permissions",
            "title" => "نقش های کاربری",
            "url" => url('role-permissions'),
        ]);
    }
}



