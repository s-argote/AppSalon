<?php

namespace App\Exports;

use App\Models\Service;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class IngresosExport implements FromCollection, WithHeadings, WithMapping
{
    public $inicio;
    public $fin;

    public function __construct($inicio, $fin)
    {
        $this->inicio = $inicio;
        $this->fin = $fin;
    }

    public function collection()
    {
        return Service::select('services.nombre')
            ->selectRaw('COUNT(citasServicios.service_id) as cantidad')
            ->selectRaw('SUM(services.precio) as total')
            ->join('citasServicios', 'services.id', '=', 'citasServicios.service_id')
            ->join('citas', 'citas.id', '=', 'citasServicios.cita_id')
            ->when(
                $this->inicio && $this->fin,
                fn($q) =>
                $q->whereBetween('citas.fecha', [$this->inicio, $this->fin])
            )
            ->groupBy('services.id', 'services.nombre')
            ->orderBy('total', 'desc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Servicio',
            'Cantidad Vendida',
            'Ingresos Totales',
        ];
    }

    public function map($row): array
    {
        return [
            $row->nombre,
            $row->cantidad,
            $row->total,
        ];
    }
}
