<?php

namespace WalkerChiu\MorphRank;

use Illuminate\Support\ServiceProvider;

class MorphRankServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
           __DIR__ .'/config/morph-rank.php' => config_path('wk-morph-rank.php'),
        ], 'config');

        // Publish migration files
        $from = __DIR__ .'/database/migrations/';
        $to   = database_path('migrations') .'/';
        $this->publishes([
            $from .'create_wk_morph_rank_table.php'
                => $to .date('Y_m_d_His', time()) .'_create_wk_morph_rank_table.php',
        ], 'migrations');

        $this->loadTranslationsFrom(__DIR__.'/translations', 'php-morph-rank');
        $this->publishes([
            __DIR__.'/translations' => resource_path('lang/vendor/php-morph-rank'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                config('wk-morph-rank.command.cleaner')
            ]);
        }

        config('wk-core.class.morph-rank.level')::observe(config('wk-core.class.morph-rank.levelObserver'));
        config('wk-core.class.morph-rank.levelLang')::observe(config('wk-core.class.morph-rank.levelLangObserver'));
        config('wk-core.class.morph-rank.status')::observe(config('wk-core.class.morph-rank.statusObserver'));
        config('wk-core.class.morph-rank.statusLang')::observe(config('wk-core.class.morph-rank.statusLangObserver'));
    }

    /**
     * Merges user's and package's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        if (!config()->has('wk-morph-rank')) {
            $this->mergeConfigFrom(
                __DIR__ .'/config/morph-rank.php', 'wk-morph-rank'
            );
        }

        $this->mergeConfigFrom(
            __DIR__ .'/config/morph-rank.php', 'morph-rank'
        );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param String  $path
     * @param String  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        if (
            !(
                $this->app instanceof CachesConfiguration
                && $this->app->configurationIsCached()
            )
        ) {
            $config = $this->app->make('config');
            $content = $config->get($key, []);

            $config->set($key, array_merge(
                require $path, $content
            ));
        }
    }
}
