<?php

namespace App\Providers;

use App\Interfaces\AdsInterface;
use App\Interfaces\OwnerAccountRepositoryInterface;
use App\Interfaces\PopupAdsInterface;
use App\Interfaces\SettingInterface;
use App\Repositories\AdsRepository;
use App\Repositories\OwnerAccountRepository;
use App\Repositories\PopupAdsRepository;
use App\Repositories\SettingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OwnerAccountRepositoryInterface::class, OwnerAccountRepository::class);
        $this->app->bind(PopupAdsInterface::class, PopupAdsRepository::class);
        $this->app->bind(AdsInterface::class, AdsRepository::class);
        $this->app->bind(SettingInterface::class,SettingRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
