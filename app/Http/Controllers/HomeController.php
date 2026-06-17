<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plato;

class HomeController extends Controller
{
    public function index()
    {
        $platos = Plato::where('activo', true)
            ->orderBy('orden')
            ->get();
        return view(
            'home',
            compact(
                'platos'
            )
        );
    }
}
