<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plato;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $platos = Plato::where('activo', true)
            ->orderBy('orden')
            ->get();

        $settings = Setting::whereIn('key', [
            'nosotros_imagen',
            'nosotros_mensaje',
            'nosotros_slogan',
            'nosotros_descripcion',
            'nosotros_palabras_clave',
        ])->pluck('value', 'key');

        $palabrasClave = json_decode(
            $settings->get('nosotros_palabras_clave', '[]'),
            true
        );

        if (! is_array($palabrasClave) || empty($palabrasClave)) {
            $palabrasClave = [
                'Sabor casero',
                'Atención cercana',
                'En tu hogar',
            ];
        }

        $nosotros = [
            'imagen' => $settings->get('nosotros_imagen'),
            'mensaje' => $settings->get(
                'nosotros_mensaje',
                'Cocinamos con dedicación'
            ),
            'slogan' => $settings->get(
                'nosotros_slogan',
                'Somos un equipo apasionado por la cocina casera y por crear momentos especiales alrededor de la mesa.'
            ),
            'descripcion' => $settings->get(
                'nosotros_descripcion',
                'Llevamos a tu hogar una experiencia cálida, práctica y confiable, preparando cada plato con ingredientes seleccionados y el cuidado que merece tu familia.'
            ),
            'palabras_clave' => array_slice($palabrasClave, 0, 3),
        ];

        return view(
            'home',
            compact(
                'platos',
                'nosotros'
            )
        );
    }
}
