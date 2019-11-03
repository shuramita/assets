<?php
namespace Shura\Asset;

use Shura\Asset\Helpers\Helper;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Shura\Asset\Commands\Install;
use Shura\Asset\Commands\Reset;
use Shura\Asset\Commands\Uninstall;
use Shura\Asset\Models\AssetType;
use Shura\BackOffice\ViewComposers\Item;
use Shura\Asset\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;

class AssetServiceProvider extends ServiceProvider
{
    public $namespace = 'Asset';

    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', $this->namespace);
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/translations', $this->namespace);
        $this->loadJSONTranslationsFrom(__DIR__.'/translations');
        AliasLoader::getInstance()->alias('AssetHelper', 'Shura\Asset\Helpers\Helper');
        $router->aliasMiddleware('asset', 'Shura\Asset\Middleware\Asset');
        $router->aliasMiddleware('ass_check', 'Shura\Asset\Middleware\CheckEnviroment');
        $this->publishes([
            __DIR__.'/config/asset.php' => config_path('asset.php'),
        ]);
        if ($this->app->runningInConsole()) {
            $this->app->make(EloquentFactory::class)->load(__DIR__.'/database/factories');
            $this->commands([
                Uninstall::class,
                Install::class,
                Reset::class
            ]);
            
        }
        $this->publishes([
            __DIR__.'/assets/public' => public_path('packages/asset'),
        ], 'public');
        $this->registerAdminNavigator();

        $this->app->singleton('Shura\Asset\Authenticated', function ($app) {
            return User::find(auth()->id());
        });
        Relation::morphMap([
            'asset_photos' => 'Shura\Asset\Models\Asset',
            'building_photo' => 'Shura\Asset\Models\Building',
            'venue_photo' => 'Shura\Asset\Models\Venue',
            'type' => 'Shura\Asset\Models\VenueType',
            'amenity' => 'Shura\Asset\Models\Amenity',
            'event' => 'Shura\Asset\Models\EventType',
        ],false);

        Event::listen('App\Services\SystemInfoManager::info', function ($info) {
            $info->asset_types = $info->asset_types ?? AssetType::all();
            $info->price_options = Helper::collect('price_option.json');
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/asset.php', 'asset');

    }
    public function registerAdminNavigator(){
//            app('AdminNavigator')->registerNavigator(
//                'asset', new Item('Asset Manager','asset','admin','fa-newspaper')
//            );
            app('BackOfficeNavigator')->registerNavigator(
                'asset', new Item('Asset Manager','asset.index',['admin'],['type'=>'svg','file'=>'vendor.asset.layers-24px'])
            );

            app('BackOfficeNavigator')->registerNavigator(
                'setting', new Item('Asset Setting','asset.setting',['admin'],['type'=>'svg','file'=>'vendor.asset.nav-icon'])
            );
            /**
             * // example for register sub Nav
             * app('AdminNavigator')->registerSubNavigator(
             *    'asset', new Item('Assets','admin_list_assets','admin','fa-hotel')
             * );
             */

    }

}
