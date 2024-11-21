<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ApproverRepository;
use App\Repositories\Interfaces\ApproverRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ApproverRepositoryInterface::class, ApproverRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
