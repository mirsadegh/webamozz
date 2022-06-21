<?php

namespace Sadegh\Category\Providers;

use Illuminate\Support\ServiceProvider;


class CategoryServiceProvider extends ServiceProvider
{
     public function register()
     {
        $this->loadRoutesFrom(__DIR__.'/../Routes/categories.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views/','Categories');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations/');
     }

     public function boot()
     {
        $this->app->booted(function(){
           config()->set('sidebar.items.categories',[
            "icon" => "i-categories",
            "title" => "دسته بندی ها",
            "url" => url('categories'),
        ]);
        });

     }
}
