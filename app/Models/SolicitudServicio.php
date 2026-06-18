<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudServicio extends Model
{
    public const ESTADO_NUEVO = 'nuevo';
    public const ESTADO_EN_PROCESO = 'en_proceso';
    public const ESTADO_ATENDIDO = 'atendido';

    protected $table = 'solicitudes_servicio';

    protected $fillable = [
        'nombre',
        'telefono',
        'email',
        'mensaje',
        'estado',
        'leido_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'leido_at' => 'datetime',
        ];
    }

    public static function estados(): array
    {
        return [
            self::ESTADO_NUEVO,
            self::ESTADO_EN_PROCESO,
            self::ESTADO_ATENDIDO,
        ];
    }
}
