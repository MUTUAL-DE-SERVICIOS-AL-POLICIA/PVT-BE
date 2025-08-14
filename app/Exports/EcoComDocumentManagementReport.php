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
        //documentos de la tabla antigua
        $documents_from_old_table = DB::table('eco_com_submitted_documents_1 as ecsd')
            ->select('ecsd.economic_complement_id', DB::raw("STRING_AGG(ecr.name, ', ') as documents"))
            ->leftJoin('eco_com_requirements as ecr', 'ecsd.eco_com_requirement_id', '=', 'ecr.id')
            ->leftJoin('economic_complements as ec2', 'ec2.id', '=', 'ecsd.economic_complement_id')
            ->leftJoin('eco_com_procedures as ecp2', 'ecp2.id', '=', 'ec2.eco_com_procedure_id')
            ->whereBetween('ecp2.year', ['2017-01-01', '2024-01-01'])
            ->groupBy('ecsd.economic_complement_id');

        //documentos de la tabla nueva
        $documents_from_new_table = DB::table('eco_com_submitted_documents as ecsd')
            ->select('ecsd.economic_complement_id', DB::raw("STRING_AGG(pd.name, ', ') as documents"))
            ->leftJoin('procedure_requirements as pr', 'ecsd.procedure_requirement_id', '=', 'pr.id')
            ->leftJoin('procedure_documents as pd', 'pr.procedure_document_id', '=', 'pd.id')
            ->leftJoin('economic_complements as ec3', 'ec3.id', '=', 'ecsd.economic_complement_id')
            ->leftJoin('eco_com_procedures as ecp3', 'ecp3.id', '=', 'ec3.eco_com_procedure_id')
            ->whereBetween('ecp3.year', ['2017-01-01', '2024-01-01'])
            ->groupBy('ecsd.economic_complement_id');

        $all_documents = $documents_from_old_table->unionAll($documents_from_new_table);

        return DB::table('economic_complements as ec')
            ->select(
                'ec.affiliate_id',
                'ec.code',
                DB::raw("CASE
                            WHEN ecm.procedure_modality_id = 29 THEN a.identity_card
                            WHEN ecm.procedure_modality_id = 30 THEN s.identity_card
                        END AS carnet_de_identidad_de_beneficiario"),
                DB::raw("CASE
                            WHEN ecm.procedure_modality_id = 29 THEN CONCAT(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name)
                            WHEN ecm.procedure_modality_id = 30 THEN
                                CASE
                                    WHEN s.id IS NULL THEN 'VIUDEDAD - CONYUGE NO ENCONTRADO'
                                    ELSE CONCAT(s.first_name, ' ', s.last_name, ' ', s.mothers_last_name, ')
                                END
                            ELSE CONCAT(a.first_name, ' ', a.second_name, ' ', a.last_name, ' ', a.mothers_last_name)
                        END AS nombre_completo"),
                'ec.reception_date',
                'docs.documents AS documents_presentados',
                DB::raw("(SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE id = COALESCE(wr.user_id, act.user_id)) as nombre_usuario")
            )
            ->leftJoin('affiliates as a', 'a.id', '=', 'ec.affiliate_id')
            ->leftJoin('spouses as s', 'a.id', '=', 's.affiliate_id')
            ->leftJoin('eco_com_procedures as ecp', 'ecp.id', '=', 'ec.eco_com_procedure_id')
            ->leftJoin('eco_com_modalities as ecm', 'ecm.id', '=', 'ec.eco_com_modality_id')
            ->leftJoin('wf_records as wr', function ($join) {
                $join->on('wr.recordable_id', '=', 'ec.id')
                     ->where('wr.recordable_type', 'like', 'economic_complements')
                     ->where('wr.message', 'ILIKE', '%recepcionó%');
            })
            ->leftJoin('activities as act', function ($join) {
                $join->on('act.economic_complement_id', '=', 'ec.id')
                     ->where('act.message', 'ILIKE', '%Creó al Complemento Económico%');
            })
            ->leftJoin(DB::raw('(' . $all_documents->toSql() . ') as docs'), function ($join) use ($all_documents) {
                $join->on('docs.economic_complement_id', '=', 'ec.id');
                $join->mergeBindings($all_documents);
            })
            ->where('ec.eco_com_reception_type_id', 2)
            ->whereBetween('ecp.year', ['2017-01-01', '2024-01-01'])
            ->orderBy('ec.reception_date', 'asc');
    }

    public function headings(): array
    {
        return [
            'NUP',
            'N° Tramite',
            'Carnet de Identidad del Beneficiario',
            'Nombre Completo del Beneficiario',
            'Fecha Recepcion del Tramite',
            'Detalle de toda la Documentación Presentada',
            'Usuario responsable de la Recepción de los documentos',
        ];
    }

    public function title(): string
    {
        return 'Gestión Documental';
    }
}
