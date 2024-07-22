<?php

namespace BookPanda\PaymentGateway;

use Filament\Panel;
use Filament\Contracts\Plugin;

class PaymentGatewayPlugin implements Plugin
{
    public function getId(): string
    {
        return 'payment-gateway';
    }

    public function register(Panel $panel): void
    {
        $panel->discoverResources(
            in: __DIR__ . '/Filament/Resources',
            for: 'BookPanda\\PaymentGateway\\Filament\\Resources'
        );
    }

    public function boot(Panel $panel): void
    {
    }
}
