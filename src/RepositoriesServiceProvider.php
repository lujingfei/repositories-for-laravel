<?php
/**
 * Created by PhpStorm.
 * User: lujingfei
 * Date: 2019/5/17
 * Time: 2:30 PM
 */

namespace Geoff\Repositories;

use Illuminate\Support\ServiceProvider;

class RepositoriesServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('repositories', function ($app) {
            return new Repositories($app['session'], $app['config']);
        });
    }

    public function provides()
    {
        return ['repositories'];
    }
}
