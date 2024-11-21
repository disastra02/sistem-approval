<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ApproverRepository;
use App\Repositories\ApprovalStageRepository;
use App\Repositories\Interfaces\ApproverRepositoryInterface;
use App\Repositories\Interfaces\ApprovalStageRepositoryInterface;

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
        $this->app->bind(ApprovalStageRepositoryInterface::class, ApprovalStageRepository::class);
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
