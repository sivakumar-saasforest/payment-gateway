<?php

namespace BookPanda\PaymentGateway\Providers;

use BookPanda\PaymentGateway\Base\PaymentGatewayHelpers;
use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use BookPanda\PaymentGateway\PaymentGatewayPlugin;

class PaymentGatewayServiceProvider extends ServiceProvider
{
    public function register()
    {
        Panel::configureUsing(fn (Panel $panel) => $panel->getId() !== 'admin' || $panel->plugin(new PaymentGatewayPlugin()));
        $this->publishes([
            __DIR__ . '/../../config/PaymentGateway.php' => config_path('PaymentGateway.php'),
        ], 'PaymentGateway-config');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'PaymentGateway');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../../resources/lang', 'PaymentGateway');
        $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/PaymentGateway')], 'translations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'PaymentGateway');
        $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/PaymentGateway')], 'views');
    }

    public function boot()
    {
        Relation::morphMap([]);

        $this->mergeConfigFrom(__DIR__ . '/../../config/PaymentGateway.php', 'PaymentGateway');

        app()->bind('XgPaymentGateway', function () {
            return new PaymentGatewayHelpers();
        });
    }
}
