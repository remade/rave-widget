<?php

namespace Remade\RaveWidget\Laravel;

use Illuminate\Support\ServiceProvider;
use Remade\RaveWidget\Widget;

class RaveWidgetServiceProvider extends ServiceProvider {

    /**
     * Register
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerWidget();
    }

    /**
     * Boot
     */
    public function boot()
    {
        // If you need to override the default config, copy config/config.php to /config/generators.config.php and update
        $this->publishes([
            __DIR__.'/../../config/config.php' => config_path('rave.widget.php'),
        ]);
    }

    /**
     * Register Config
     */
    protected function registerConfig()
    {
        $userConfigFile = $this->app->configPath().'/rave.widget.php';
        $packageConfigFile = __DIR__.'/../../config/config.php';
        $config = $this->app['files']->getRequire($packageConfigFile);

        if (file_exists($userConfigFile)) {
            $userConfig = $this->app['files']->getRequire($userConfigFile);
            $config = array_replace_recursive($config, $userConfig);
        }

        $this->app['config']->set('rave.widget', $config);
    }

    /**
     * Register Widget
     */
    protected function registerWidget()
    {
        $this->app->bind('rave.widget', function ($app) {
            return new Widget($app['config']->get('rave.widget'));
        });
    }

    /**
     * Check if current app is Lumen
     *
     * @return bool
     */
    protected function isLumen()
    {
        return str_contains($this->app->version(), 'Lumen');
    }

    public function provides()
    {
        return ['rave.widget'];
    }

}