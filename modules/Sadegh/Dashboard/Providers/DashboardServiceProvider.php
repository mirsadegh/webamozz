<?php

namespace Sadegh\Dashboard\Providers;
use Illuminate\Support\ServiceProvider;

class DashboardServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/dashboard.php');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views','Dashboard');
    }
}