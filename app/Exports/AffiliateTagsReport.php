<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Muserpol\Models\Tag;
use Muserpol\Models\Affiliate;

class AffiliateTagsReport implements WithMultipleSheets
{
    use Exportable;
    public function sheets(): array
    {
        $sheets = [];
        $tags = Tag::whereIn('id', [17,18])->get();
        $affiliates = Affiliate::with('tags')
            ->select(
                'affiliates.id',
                'identity_card',
                'affiliates.first_name as affiliate_primer_nombre',
                'affiliates.second_name as affiliate_segundo_nombre',
                'affiliates.last_name as affiliate_paterno',
                'affiliates.mothers_last_name as affiliate_materno',
                'affiliates.surname_husband as affiliate_ap_casada',
                'affiliates.birth_date as affiliate_fecha_nacimiento',
                'affiliates.nua as affiliate_codigo_nua_cua'
            )
            ->has('tags')
            ->get();
        foreach ($tags as $t) {
            $sheets[] = new AffiliateTagSheet($t, $affiliates);
        }
        return $sheets;
    }
}
