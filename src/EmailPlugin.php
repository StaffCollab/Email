<?php

namespace StaffCollab\Email;

use Filament\Contracts\Plugin;
use Filament\Panel;
use StaffCollab\Email\Filament\Resources\EmailTemplateResource;

class EmailPlugin implements Plugin
{
    public function getId(): string
    {
        return 'email';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([
            EmailTemplateResource::class,
        ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
