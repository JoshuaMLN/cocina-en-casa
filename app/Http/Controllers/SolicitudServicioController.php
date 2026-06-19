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

    public function snapshot(Request $request): JsonResponse
    {
        $data = $request->validate([
            'latest_id' => ['nullable', 'integer', 'min:0'],
            'total' => ['nullable', 'integer', 'min:0'],
            'unread' => ['nullable', 'integer', 'min:0'],
            'force' => ['nullable', 'boolean'],
            'solicitudes_page' => ['nullable', 'integer', 'min:1'],
        ]);

        $summary = SolicitudServicio::query()
            ->selectRaw(
                'COALESCE(MAX(id), 0) as latest_id,
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN leido_at IS NULL THEN 1 ELSE 0 END), 0) as unread'
            )
            ->first();

        $latestId = (int) $summary->latest_id;
        $total = (int) $summary->total;
        $unread = (int) $summary->unread;
        $hasChanges =
            (int) ($data['latest_id'] ?? 0) !== $latestId ||
            (int) ($data['total'] ?? 0) !== $total ||
            (int) ($data['unread'] ?? 0) !== $unread;
        $force = (bool) ($data['force'] ?? false);

        $response = [
            'changed' => $hasChanges,
            'latest_id' => $latestId,
            'total' => $total,
            'unread' => $unread,
        ];

        if ($hasChanges || $force) {
            $solicitudes = SolicitudServicio::latest()
                ->paginate(
                    15,
                    ['*'],
                    'solicitudes_page',
                    (int) ($data['solicitudes_page'] ?? 1)
                );
            $solicitudes->withPath(route('admin.dashboard'));

            $response['html'] = view(
                'admin.tabs.solicitudes',
                compact('solicitudes')
            )->render();
        }

        return response()->json($response);
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
