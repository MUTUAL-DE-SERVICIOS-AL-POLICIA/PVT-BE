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
                DB::raw("CASE
                    WHEN ecm.procedure_modality_id = 29 THEN CONCAT(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name)
                    WHEN ecm.procedure_modality_id = 30 THEN CONCAT(s.first_name, ' ', s.last_name, ' ', s.mothers_last_name, ' (', s.identity_card, ')')
                    ELSE CONCAT(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name)
                END as nombre_completo"),
                'ec.reception_date',
                'docs.documents as documents_presentados',
                DB::raw("CONCAT(u.first_name, ' ', u.last_name) as usuario_recepcion")
            )
            ->leftJoin('affiliates as a', 'a.id', '=', 'ec.affiliate_id')
            ->leftJoin('spouses as s', 'a.id', '=', 's.affiliate_id')
            ->leftJoin('eco_com_modalities as ecm', 'ecm.id', '=', 'ec.eco_com_modality_id')
            ->leftJoin('eco_com_procedures as ecp', 'ecp.id', '=', 'ec.eco_com_procedure_id')
            ->leftJoin('wf_records as wr', 'wr.recordable_id', '=', 'ec.id')
            ->leftJoin('users as u', 'u.id', '=', 'wr.user_id')
            ->leftJoin(DB::raw('(' . $all_documents->toSql() . ') as docs'), function ($join) {
                $join->on('docs.economic_complement_id', '=', 'ec.id');
            })
            ->where('ecp.year', '>=', '2017-01-01')
            ->where('ec.eco_com_reception_type_id', 2)
            ->where('wr.recordable_type', 'economic_complements')
            ->whereRaw("wr.message ILIKE ?", ['%recepcionó%'])
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
