<?php

namespace Muserpol\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SalariesImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        // This method is required by ToCollection.
        // The main logic is now in the controller which uses Excel::toCollection.
    }

    public function rules(): array
    {
        return [
            '*.codigo_de_grado' => 'required|integer|exists:degrees,id',
            '*.grado_abreviado' => 'required|string',
            '*.grado_nombre_completo' => 'required|string',
            '*.año' => 'required|integer',
            '*.salario' => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.codigo_de_grado.required' => 'La columna codigo_de_grado es obligatoria.',
            '*.codigo_de_grado.integer' => 'El codigo_de_grado debe ser un número entero.',
            '*.codigo_de_grado.exists' => 'Uno de los Codigo de Grado no existe en el sistema.',
            '*.salario.numeric' => 'La columna salario debe ser un valor numérico.',
            '*.salario.min' => 'El salario no puede ser negativo.',
        ];
    }
}
