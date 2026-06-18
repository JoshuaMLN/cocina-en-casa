<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

use App\Models\Setting;
use App\Models\Plato;
use App\Models\SolicitudServicio;


class AdminController extends Controller
{
    /* ==========================
       PANEL ADMIN
       ========================== */
    public function index()
    {
        $settings = Setting::whereIn('key', [
            'navbar_logo',
            'whatsapp_country_code',
            'whatsapp_number',
            'whatsapp_message',
            'nosotros_imagen',
            'nosotros_mensaje',
            'nosotros_slogan',
            'nosotros_descripcion',
            'nosotros_palabras_clave',
            'footer_description',
            'footer_service_area',
            'contact_email',
            'social_facebook',
            'social_instagram',
            'social_tiktok',
        ])->pluck('value', 'key');

        $logo = $settings->get('navbar_logo');
        $codigoPais = $settings->get('whatsapp_country_code', '51');
        $numero = $settings->get('whatsapp_number');
        $mensaje = $settings->get('whatsapp_message');

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

        $footer = [
            'description' => $settings->get(
                'footer_description',
                'Comida casera preparada con dedicación en la comodidad de tu hogar.'
            ),
            'service_area' => $settings->get('footer_service_area', ''),
            'email' => $settings->get('contact_email', ''),
            'facebook' => $settings->get('social_facebook', ''),
            'instagram' => $settings->get('social_instagram', ''),
            'tiktok' => $settings->get('social_tiktok', ''),
        ];

        $platos = Plato::orderBy('orden')->get();
        $solicitudes = SolicitudServicio::latest()
            ->paginate(15, ['*'], 'solicitudes_page');
        $solicitudesNoLeidas = SolicitudServicio::whereNull(
            'leido_at'
        )->count();

        return view(
            'admin.index',
            compact(
                'logo',
                'codigoPais',
                'numero',
                'mensaje',
                'nosotros',
                'footer',
                'platos',
                'solicitudes',
                'solicitudesNoLeidas'
            )
        );
    }

    /* ==========================
       FOOTER
       ========================== */
    public function updateFooter(Request $request)
    {
        $data = $request->validate([
            'footer_description' => 'nullable|string|max:180',
            'footer_service_area' => 'nullable|string|max:120',
            'contact_email' => 'nullable|email:rfc|max:150',
            'social_facebook' => 'nullable|url:http,https|max:255',
            'social_instagram' => 'nullable|url:http,https|max:255',
            'social_tiktok' => 'nullable|url:http,https|max:255',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => trim((string) $value)]
            );
        }

        return back()->with(
            'success',
            'Información del footer actualizada correctamente.'
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
       NOSOTROS
       ========================== */
    public function updateNosotros(Request $request)
    {
        $request->validate([
            'cropped_nosotros_image' => 'nullable|string',
            'nosotros_mensaje' => 'required|string|max:45',
            'nosotros_slogan' => 'required|string|max:180',
            'nosotros_descripcion' => 'required|string|max:600',
            'nosotros_palabras_clave' => 'required|array|max:3',
            'nosotros_palabras_clave.0' => 'required|string|max:40',
            'nosotros_palabras_clave.*' => 'nullable|string|max:40',
        ]);

        $palabrasClave = collect($request->nosotros_palabras_clave)
            ->map(fn ($palabra) => trim((string) $palabra))
            ->filter()
            ->unique()
            ->take(3)
            ->values();

        if ($palabrasClave->isEmpty()) {
            throw ValidationException::withMessages([
                'nosotros_palabras_clave' => 'Agrega al menos una palabra clave.',
            ]);
        }

        if ($request->filled('cropped_nosotros_image')) {
            $imageBase64 = $this->decodeCroppedImage(
                $request->cropped_nosotros_image
            );

            $fileName = 'nosotros/nosotros_' .
                time() .
                '_' .
                Str::random(5) .
                '.webp';

            Storage::disk('public')->put($fileName, $imageBase64);

            $imagenAnterior = Setting::where(
                'key',
                'nosotros_imagen'
            )->value('value');

            Setting::updateOrCreate(
                ['key' => 'nosotros_imagen'],
                ['value' => $fileName]
            );

            if (
                $imagenAnterior &&
                Storage::disk('public')->exists($imagenAnterior)
            ) {
                Storage::disk('public')->delete($imagenAnterior);
            }
        }

        $valores = [
            'nosotros_mensaje' => trim($request->nosotros_mensaje),
            'nosotros_slogan' => trim($request->nosotros_slogan),
            'nosotros_descripcion' => trim($request->nosotros_descripcion),
            'nosotros_palabras_clave' => $palabrasClave->toJson(
                JSON_UNESCAPED_UNICODE
            ),
        ];

        foreach ($valores as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with(
            'success',
            'Sección Nosotros actualizada correctamente.'
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

    private function decodeCroppedImage(string $image): string
    {
        if (! preg_match(
            '/^data:image\/(?:png|jpeg|webp);base64,/',
            $image
        )) {
            throw ValidationException::withMessages([
                'cropped_nosotros_image' => 'La imagen recortada no es válida.',
            ]);
        }

        $imageBase64 = base64_decode(
            substr($image, strpos($image, ',') + 1),
            true
        );

        if ($imageBase64 === false) {
            throw ValidationException::withMessages([
                'cropped_nosotros_image' => 'No se pudo procesar la imagen.',
            ]);
        }

        if (strlen($imageBase64) > 5 * 1024 * 1024) {
            throw ValidationException::withMessages([
                'cropped_nosotros_image' => 'La imagen procesada supera el límite de 5 MB.',
            ]);
        }

        return $imageBase64;
    }
}
