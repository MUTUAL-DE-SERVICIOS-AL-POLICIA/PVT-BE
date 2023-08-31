<?php

namespace Muserpol\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use Muserpol\Models\Affiliate;
use Illuminate\Support\Collection;
use Muserpol\Models\ObservationType;
use Illuminate\Support\Str;
use Muserpol\Models\Tag;

class AffiliateTagSheet implements FromCollection, WithTitle, WithHeadings, ShouldAutoSize
{
    private $tag;
    private $affiliates;
    public function __construct(Tag $tag, Collection $affiliates)
    {
        $this->tag = $tag;
        $this->affiliates = $affiliates;
    }

    public function collection()
    {
        $data = collect([]);
        foreach ($this->affiliates as $a) {
            $tag = $a->tags->where('id', $this->tag->id)->first();
            if ($tag) {
                $a->tag_name = $this->tag->name;
                $data->push($a);
            }
        }
        return $data;
    }
    public function title(): string
    {
        return Str::limit(collect(explode('-', $this->tag->name))->last(), 20);
    }
    public function headings(): array
    {
        $new_columns = ['Etiqueta'];
        $default = [
            // 'NRO',
            'NUP',
            'CI',
            "Primer Nombre ",
            "Segundo Nombre ",
            "Paterno ",
            "Materno ",
            "Apellido casada ",
            "Fecha Nacimiento ",
            "NUA",
        ];
        return array_merge($default, $new_columns);
    }
}
