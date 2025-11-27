<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Muserpol\Models\EconomicComplement\EconomicComplement;


use Maatwebsite\Excel\Concerns\WithMapping;

class AffiliateDeceasedSemesterExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $eco_com_procedure_id;
    private $index = 1;

    public function __construct($eco_com_procedure_id)
    {
        $this->eco_com_procedure_id = $eco_com_procedure_id;
    }

    public function map($row): array
    {
        return [
            $this->index++,
            $row['nup'],
            $row['ci'],
            $row['full_name'],
            $row['modalidad'],
            $row['last_complement_number'],
            $row['death_date'],
            $row['death_cert_number'],
            $row['cause_death'],
            $row['death_reg_date'],
            $row['user'],
        ];
    }

    public function collection()
    {
        $results = [];

        EconomicComplement::where('eco_com_procedure_id', $this->eco_com_procedure_id)
            ->with([
                'affiliate.affiliate_records_pvt.user',
                'affiliate.spouse.records.user',
                'eco_com_modality.procedure_modality'
            ])
            ->chunk(200, function ($complements) use (&$results) {
                foreach ($complements as $complement) {
                    $person = null;
                    $affiliate = $complement->affiliate;
                    $death_record = null;
                    $record_date = '';
                    $record_user = '';

                    if ($complement->isOldAge()) {
                        $person = $affiliate;
                        if ($person && $person->date_death) {
                            $death_record = $person->affiliate_records_pvt->filter(function($record) {
                                return str_contains($record->message, 'fecha de fallecimiento');
                            })->last();
                            if ($death_record) {
                                $record_date = $death_record->created_at ? $death_record->created_at->format('d/m/Y H:i:s') : '';
                                $record_user = $death_record->user ? $death_record->user->username : '';
                            }
                        }
                    } elseif ($complement->isWidowhood()) {
                        if ($affiliate && $affiliate->spouse->first()) {
                            $person = $affiliate->spouse->first();
                            if ($person && $person->date_death) {
                                $death_record = $person->records->filter(function($record) {
                                    return str_contains($record->action, ' fallecimiento');
                                })->last();
                                if ($death_record) {
                                    $record_date = $death_record->created_at ? $death_record->created_at->format('d/m/Y H:i:s') : '';
                                    $record_user = $death_record->user ? $death_record->user->username : '';
                                }
                            }
                        }
                    }

                    if ($person && $person->date_death) {
                        $results[] = [
                            'nup' => $affiliate->id,
                            'ci' => $person->identity_card,
                            'full_name' => $person->fullName(),
                            'modalidad' => $complement->eco_com_modality->procedure_modality->name,
                            'last_complement_number' => $complement->code,
                            'death_date' => $person->date_death ?? '',
                            'death_cert_number' => $person->death_certificate_number,
                            'cause_death' => $person->reason_death,
                            'death_reg_date' => $record_date,
                            'user' => $record_user,
                        ];
                    }
                }
            });

        return collect($results);
    }

    public function headings(): array
    {
        return [
            'N°',
            'NUP',
            'CI',
            'Nombre Completo',
            'Modalidad',
            'Numero de su ultimo tramite de complemento economico',
            'Fecha de defunción',
            'N. cert de defunción',
            'Causa de fallecimiento',
            'Fecha de registro de defunción',
            'Usuario que realizo el registro de defunción',
        ];
    }
}
