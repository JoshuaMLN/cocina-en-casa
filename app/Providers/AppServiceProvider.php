<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Setting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        RateLimiter::for('admin-settings-unlock', function (Request $request) {
            $adminId = $request->user('admin')?->getAuthIdentifier();

            return Limit::perMinutes(10, 5)
                ->by('admin-settings-unlock:' . ($adminId ?? $request->ip()))
                ->response(fn () => response()->json([
                    'message' => 'Demasiados intentos. Espera unos minutos antes de volver a intentarlo.',
                ], 429));
        });

        RateLimiter::for('admin-settings-update', function (Request $request) {
            $adminId = $request->user('admin')?->getAuthIdentifier();

            return Limit::perMinute(10)
                ->by('admin-settings-update:' . ($adminId ?? $request->ip()))
                ->response(fn () => back()->with(
                    'error',
                    'Realizaste demasiados cambios seguidos. Espera un minuto e inténtalo nuevamente.'
                ));
        });

        $whatsapp = '';
        $mensaje = '';
        $urlWhatsapp = '#';
        $whatsappAvailable = false;
        $footer = [
            'logo' => null,
            'description' => 'Comida casera preparada con dedicación en la comodidad de tu hogar.',
            'service_area' => '',
            'email' => '',
            'facebook' => '',
            'instagram' => '',
            'tiktok' => '',
        ];

        try {
            $settings = Setting::whereIn('key', [
                'navbar_logo',
                'whatsapp_country_code',
                'whatsapp_number',
                'whatsapp_message',
                'footer_description',
                'footer_service_area',
                'contact_email',
                'social_facebook',
                'social_instagram',
                'social_tiktok',
            ])->pluck('value', 'key');

            $codigoPais = $settings->get(
                'whatsapp_country_code',
                '51'
            );
            $numero = $settings->get('whatsapp_number');
            $mensaje = $settings->get('whatsapp_message', '');

            if (! empty($numero)) {
                $whatsapp = $codigoPais . $numero;
                $urlWhatsapp = "https://wa.me/$whatsapp";
                $whatsappAvailable = true;

                if (! empty($mensaje)) {
                    $urlWhatsapp .= '?text=' . urlencode($mensaje);
                }
            }

            $footer = [
                'logo' => $settings->get('navbar_logo'),
                'description' => $settings->get(
                    'footer_description',
                    $footer['description']
                ),
                'service_area' => $settings->get(
                    'footer_service_area',
                    ''
                ),
                'email' => $settings->get('contact_email', ''),
                'facebook' => $settings->get('social_facebook', ''),
                'instagram' => $settings->get('social_instagram', ''),
                'tiktok' => $settings->get('social_tiktok', ''),
            ];
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());
        }

        View::share('whatsapp', $whatsapp);
        View::share('whatsapp_message', $mensaje);
        View::share('whatsapp_url', $urlWhatsapp);
        View::share('whatsapp_available', $whatsappAvailable);
        View::share('footer', $footer);
    }
}
