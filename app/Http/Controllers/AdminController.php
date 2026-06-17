<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\Setting;
use App\Models\Plato;


class AdminController extends Controller
{
    /* ==========================
       PANEL ADMIN
       ========================== */
    public function index()
    {
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

        $platos = Plato::orderBy('orden')->get();

        return view(
            'admin.index',
            compact('codigoPais', 'numero', 'mensaje', 'platos')
        );
    }

    /* ==========================
       LOGO
       ========================== */
    public function updateLogo(Request $request)
    {
        // 1. Validamos que el texto base64 venga en la petición
        $request->validate([
            'cropped_logo' => 'required|string',
        ]);

        // 2. Extraemos la información del Base64
        // El formato suele ser: "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAA..."
        $image_parts = explode(";base64,", $request->cropped_logo);
        
        // Decodificamos la parte del código puro
        $image_base64 = base64_decode($image_parts[1]);

        // 3. Creamos un nombre único para la nueva imagen
        $fileName = 'logos/logo_' . time() . '_' . Str::random(5) . '.png';

        // 4. Guardamos el archivo físico en el disco (storage/app/public/logos)
        Storage::disk('public')->put($fileName, $image_base64);

        // 5. Buscar si ya había un logo antes y borrarlo
        $settingLogo = Setting::where('key', 'navbar_logo')->first();
        if ($settingLogo && $settingLogo->value) {
            Storage::disk('public')->delete($settingLogo->value);
        }

        // 6. Guardar la nueva ruta en la Base de Datos
        Setting::updateOrCreate(
            ['key' => 'navbar_logo'],
            ['value' => $fileName]
        );

        return back()->with('success', '¡Logo recortado y guardado correctamente!');
    }

    /* ==========================
       WHATSAPP
       ========================== */
    public function updateWhatsapp(Request $request)
    {
        $request->validate([
            'codigo_pais' => 'required',
            'numero' => ['required', 'regex:/^[0-9]{6,15}$/'],
            'mensaje' => 'nullable|string|max:1000',
        ]);

        $codigoPais = $request->input('codigo_pais', '51');
        
        Setting::updateOrCreate(
            ['key' => 'whatsapp_country_code'],
            ['value' => $codigoPais]
        );

        Setting::updateOrCreate(
            ['key' => 'whatsapp_number'],
            ['value' => $request->numero]
        );

        Setting::updateOrCreate(
            ['key' => 'whatsapp_message'],
            ['value' => trim($request->mensaje ?? '')]
        );

        return back()->with(
            'success',
            '¡Número y mensaje de WhatsApp actualizados correctamente!'
        );
    }

    /* ==========================
       PLATOS
       ========================== */
    /*CREAR*/
    public function storePlato(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:platos,nombre',
            'descripcion' => 'nullable|string|max:150',
            'cropped_image' => 'required|string',
        ]);

        $imageParts = explode(
            ';base64,',
            $request->cropped_image
        );

        $imageBase64 = base64_decode(
            $imageParts[1]
        );

        $fileName = 'platos/plato_' .
            time() .
            '_' .
            Str::random(5) .
            '.webp';

        Storage::disk('public')->put(
            $fileName,
            $imageBase64
        );

        $ultimoOrden = Plato::max('orden') ?? 0;

        Plato::create([
            'nombre' => trim($request->nombre),
            'descripcion' => $request->filled('descripcion')? trim($request->descripcion): null,
            'imagen' => $fileName,
            'activo' => true,
            'orden' => $ultimoOrden + 1,
        ]);

        return back()->with(
            'success',
            'Plato registrado correctamente.'
        );
    }
    /*ACTUALIZAR*/
    public function updatePlato(
        Request $request,
        Plato $plato
    )
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('platos', 'nombre')->ignore($plato->id),
            ],
            'descripcion' => 'nullable|string|max:150',
            'cropped_image' => 'nullable|string',
        ]);

        $plato->nombre = trim($request->nombre);

        $plato->descripcion = trim(
            $request->descripcion ?? ''
        );

        if ($request->filled('cropped_image')) {

            if (
                $plato->imagen &&
                Storage::disk('public')->exists($plato->imagen)
            ) {
                Storage::disk('public')->delete(
                    $plato->imagen
                );
            }

            $imageParts = explode(
                ';base64,',
                $request->cropped_image
            );

            $imageBase64 = base64_decode(
                $imageParts[1]
            );

            $fileName = 'platos/plato_' .
                time() .
                '_' .
                Str::random(5) .
                '.webp';

            Storage::disk('public')->put(
                $fileName,
                $imageBase64
            );

            $plato->imagen = $fileName;
        }

        $plato->save();

        return back()->with(
            'success',
            'Plato actualizado correctamente.'
        );
    }
    /*ELIMINAR*/
    public function destroyPlato(
        Plato $plato
    )
    {
        if (
            $plato->imagen &&
            Storage::disk('public')->exists($plato->imagen)
        ) {
            Storage::disk('public')->delete(
                $plato->imagen
            );
        }

        $plato->delete();

        return back()->with(
            'success',
            'Plato eliminado correctamente.'
        );
    }
    /*ACTIVAR / DESACTIVAR*/
    public function togglePlato(
        Plato $plato
    )
    {
        $plato->activo = ! $plato->activo;

        $plato->save();

        return back()->with(
            'success',
            'Estado actualizado correctamente.'
        );
    }
    /*REORDENAR*/
    public function reordenarPlatos(Request $request)
    {
        $request->validate([
            'platos' => 'required|array',
            'platos.*.id' => 'required|exists:platos,id',
            'platos.*.orden' => 'required|integer|min:1',
        ]);

        foreach ($request->platos as $platoData) {
            Plato::where('id', $platoData['id'])
                ->update([
                    'orden' => $platoData['orden']
                ]);
        }

        return response()->json([
            'success' => true
        ]);
    }
}
