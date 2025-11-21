<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cita extends Model
{
    use HasFactory;

    protected $fillable = [
        'fecha',
        'hora',
        'hora_fin',
        'duracion_total',
        'user_id',
        'total',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function servicios()
    {
        return $this->belongsToMany(Service::class, 'citasServicios', 'cita_id', 'service_id')
            ->using(CitaServicio::class);
    }
}
