<?php namespace RKooistra\SuperEloquentRepository\Providers;

use Illuminate\Support\ServiceProvider;

class SuperEloquentRepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        $configPath = __DIR__ . '/../config/google_identity.php';
//        $this->publishes([$configPath => config_path('google_identity.php')], 'km');
//        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }
}
