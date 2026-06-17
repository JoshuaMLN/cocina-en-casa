<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Setting;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {

            if (!Schema::hasTable('settings')) {
                return;
            }

            $codigoPais = Setting::where(
                'key',
                'whatsapp_country_code'
            )->value('value') ?? '51';

            $numero = Setting::where(
                'key',
                'whatsapp_number'
            )->value('value');

            $mensaje = Setting::where(
                'key',
                'whatsapp_message'
            )->value('value');

            $whatsapp = $codigoPais . $numero;

            $urlWhatsapp = "https://wa.me/$whatsapp";

            if (!empty($mensaje)) {
                $urlWhatsapp .= '?text=' . urlencode($mensaje);
            }

            View::share('whatsapp', $whatsapp);
            View::share('whatsapp_message', $mensaje);
            View::share('whatsapp_url', $urlWhatsapp);

        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }
    }
}
