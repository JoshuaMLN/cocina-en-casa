<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSolicitudServicioRequest extends FormRequest
{
    protected $errorBag = 'solicitudServicio';

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $telefono = preg_replace(
            '/(?!^\+)[^0-9]/',
            '',
            trim((string) $this->input('telefono'))
        );

        $this->merge([
            'nombre' => trim((string) $this->input('nombre')),
            'telefono' => $telefono !== '' ? $telefono : null,
            'email' => $this->filled('email')
                ? mb_strtolower(trim((string) $this->input('email')))
                : null,
            'mensaje' => $this->filled('mensaje')
                ? trim((string) $this->input('mensaje'))
                : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'origen' => ['required', 'in:modal,contacto'],
            'nombre' => ['required', 'string', 'max:100'],
            'telefono' => [
                'nullable',
                'required_without:email',
                'regex:/^\+?[0-9]{6,15}$/',
            ],
            'email' => [
                'nullable',
                'required_without:telefono',
                'email:rfc',
                'max:150',
            ],
            'mensaje' => ['nullable', 'string', 'max:1000'],
            'acepta_contacto' => ['accepted'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Ingresa tu nombre.',
            'telefono.required_without' => 'Ingresa un teléfono o un correo electrónico.',
            'telefono.regex' => 'El teléfono debe contener entre 6 y 15 dígitos.',
            'email.required_without' => 'Ingresa un correo electrónico o un teléfono.',
            'email.email' => 'Ingresa un correo electrónico válido.',
            'acepta_contacto.accepted' => 'Debes aceptar el uso de tus datos para poder contactarte.',
            'website.max' => 'No se pudo procesar la solicitud.',
        ];
    }
}
