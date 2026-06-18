<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSolicitudServicioRequest;
use App\Models\SolicitudServicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SolicitudServicioController extends Controller
{
    public function store(
        StoreSolicitudServicioRequest $request
    ): RedirectResponse {
        $data = $request->safe()->only([
            'nombre',
            'telefono',
            'email',
            'mensaje',
        ]);

        SolicitudServicio::create([
            ...$data,
            'estado' => SolicitudServicio::ESTADO_NUEVO,
            'ip_address' => $request->ip(),
            'user_agent' => mb_substr(
                (string) $request->userAgent(),
                0,
                1000
            ),
        ]);

        $destination = $request->input('origen') === 'contacto'
            ? route('home') . '#contacto'
            : route('home');

        return redirect()->to($destination)->with(
            'success',
            'Recibimos tu solicitud. Nos comunicaremos contigo pronto.'
        );
    }

    public function marcarLeida(
        SolicitudServicio $solicitud
    ): JsonResponse {
        if ($solicitud->leido_at === null) {
            $solicitud->update([
                'leido_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function actualizarEstado(
        Request $request,
        SolicitudServicio $solicitud
    ): RedirectResponse {
        $data = $request->validate([
            'estado' => [
                'required',
                Rule::in(SolicitudServicio::estados()),
            ],
        ]);

        $solicitud->update([
            'estado' => $data['estado'],
            'leido_at' => $solicitud->leido_at ?? now(),
        ]);

        return back()->with(
            'success',
            'Estado de la solicitud actualizado.'
        );
    }

    public function destroy(
        SolicitudServicio $solicitud
    ): RedirectResponse {
        $solicitud->delete();

        return back()->with(
            'success',
            'Solicitud eliminada correctamente.'
        );
    }
}
