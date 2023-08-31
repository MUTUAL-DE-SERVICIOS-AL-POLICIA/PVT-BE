<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Muserpol\Models\Affiliate;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;

class AffiliateSpouseReport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $report_type_id;
    public function __construct($report_type_id)
    {
        $this->report_type_id = $report_type_id;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = null;
        $columns = "";
        switch ($this->report_type_id) {
            case 21:
                $data = Affiliate::select(
                    'affiliates.id',
                    'affiliates.identity_card', 
                    'affiliates.first_name',
                    'affiliates.second_name',
                    'affiliates.last_name',
                    'affiliates.mothers_last_name',
                    'affiliates.surname_husband',
                    'affiliates.gender',
                    'affiliates.birth_date',
                    'affiliate_birth_city.name',
                    'affiliates.date_death',
                    'affiliates.reason_death',
                    'affiliates.death_certificate_number',                   
                    'spouses.identity_card as IdentityCard',
                    'spouses.first_name as FirstName',
                    'spouses.second_name as SecondName',
                    'spouses.last_name as LastName',
                    'spouses.mothers_last_name as MothersLastName',
                    'spouses.surname_husband as SurnameHusband',
                    'spouses.birth_date as BirthDate',
                    'spouse_birth_city.name as CityDate',
                    DB::raw("(CASE WHEN spouses.civil_status = 'V' THEN 'Viudo(a)' WHEN spouses.civil_status = 'C' THEN 'Casado(a)' WHEN spouses.civil_status = 'D' THEN 'Divorciado(a)' ELSE '' END) as Estadocivil"),
                    'spouses.date_death as DateDeath',
                    'spouses.reason_death as ReasonDeath',
                    'spouses.death_certificate_number as DeathDertificate',
                    'spouses.official',
                    'spouses.departure',
                    'spouses.book',
                    'spouses.marriage_date'
                    )
                    ->leftJoin('cities as affiliate_city','affiliates.city_identity_card_id','=','affiliate_city.id')
                    ->leftJoin('cities as affiliate_birth_city','affiliates.city_birth_id','=','affiliate_birth_city.id')
                    ->join('spouses','affiliates.id','=','spouses.affiliate_id')
                    ->leftJoin('cities as spouse_birth_city','spouses.city_birth_id','=','spouse_birth_city.id')
                    //->wherein('affiliates.id', [1,2,3])
                    ->get();
                break;
            case 22:
                $data = Affiliate::select(
                    'affiliates.id',
                    'affiliates.identity_card', 
                    'affiliates.first_name',
                    'affiliates.second_name',
                    'affiliates.last_name',
                    'affiliates.mothers_last_name',
                    'affiliates.surname_husband',
                    'affiliates.gender',
                    'affiliates.birth_date',
                    'affiliate_birth_city.name',
                    'affiliates.date_death',
                    'affiliates.reason_death',
                    'affiliates.death_certificate_number'
                    )
                    ->leftJoin('cities as affiliate_birth_city','affiliates.city_birth_id','=','affiliate_birth_city.id')
                    ->whereNotNull('affiliates.date_death')
                    ->orderBy('affiliates.date_death', 'DESC')
                    ->get();
                break;
            case 23:
                
            default:
                # code...
                break;
        }
        return $data;
    }
    public function headings(): array
    {
        $new_columns = [];
        
        switch ($this->report_type_id) {
            case 21:
                $new_columns = [
                    "CI conyugue ",
                    "Primer Nombre",
                    "Segundo Nombre",
                    "Paterno ",
                    "Materno ",
                    "Apellido casada ",
                    "Fecha Nacimiento ",
                    "Lugar Nacimiento ",
                    "Estado Civil ",
                    "Fecha de fallecimiento ",
                    "Causa de fallecimiento ",
                    "Nro de certificado defunción",
                    "Oficialia",
                    "Partida",
                    "Libro",
                    "fecha Matrimonio"
                ];
                break;
        }
        
        $default = [
            'NUP',
            'CI',
            "Primer Nombre ",
            "Segundo Nombre ",
            "Paterno ",
            "Materno ",
            "Apellido casada ",
            "Genero ",
            "Fecha Nacimiento ",
            "Lugar Nacimiento ",
            "Fecha de Fallecimiento ",
            "Causa de Fallecimiento ",
            "Nro de certificado defuncion ",
        ];
        return array_merge($default, $new_columns);
    }
}
