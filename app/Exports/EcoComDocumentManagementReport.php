<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Support\Facades\DB;

class EcoComDocumentManagementReport implements FromQuery, WithHeadings, ShouldAutoSize, WithTitle
{
    public function query()
    {
        $documents_from_old_table = DB::table('eco_com_submitted_documents_1 as ecsd')
            ->select('ecsd.economic_complement_id', DB::raw("STRING_AGG(ecr.name, ', ') as documents"))
            ->leftJoin('eco_com_requirements as ecr', 'ecsd.eco_com_requirement_id', '=', 'ecr.id')
            ->groupBy('ecsd.economic_complement_id');

        $documents_from_new_table = DB::table('eco_com_submitted_documents as ecsd')
            ->select('ecsd.economic_complement_id', DB::raw("STRING_AGG(pd.name, ', ') as documents"))
            ->leftJoin('procedure_requirements as pr', 'ecsd.procedure_requirement_id', '=', 'pr.id')
            ->leftJoin('procedure_documents as pd', 'pr.procedure_document_id', '=', 'pd.id')
            ->groupBy('ecsd.economic_complement_id');

        $all_documents = $documents_from_old_table->unionAll($documents_from_new_table);

        return DB::table('economic_complements as ec')
            ->select(
                'ec.affiliate_id',
                'ec.code',
                DB::raw("CONCAT(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name) as nombre_completo"),
                'ec.reception_date',
                'docs.documents as documentos_presentados'
            )
            ->leftJoin('affiliates as a', 'a.id', '=', 'ec.affiliate_id')
            ->leftJoin('eco_com_procedures as ecp', 'ecp.id', '=', 'ec.eco_com_procedure_id')
            ->leftJoin(DB::raw('(' . $all_documents->toSql() . ') as docs'), function ($join) {
                $join->on('docs.economic_complement_id', '=', 'ec.id');
            })
            ->where('ecp.year', '>=', '2017-01-01')
            ->where('ec.eco_com_reception_type_id', 2)
            ->orderBy('ec.reception_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID Afiliado',
            'Nro. Trámite',
            'Nombre Completo',
            'Fecha de Recepción',
            'Documentos Presentados',
        ];
    }

    public function title(): string
    {
        return 'Gestión Documental';
    }
}
