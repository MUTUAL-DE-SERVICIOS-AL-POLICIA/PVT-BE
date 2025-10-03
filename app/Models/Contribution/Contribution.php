<?php

namespace Muserpol\Models\Contribution;

use InvalidArgumentException;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Contribution extends Model
{

    use SoftDeletes;

    protected $fillable = [

        'user_id',
        'affiliate_id',
        'type',
        'degree_id',
        'unit_id',
        'breakdown_id',
        'category_id',
        'month_year',
        'base_wage',
        'dignity_pension',
        'seniority_bonus',
        'study_bonus',
        'position_bonus',
        'border_bonus',
        'east_bonus',
        'public_security_bonus',
        'gain',
        'payable_liquid',
        'quotable',
        'retirement_fund',
        'mortuary_quota',
        'mortuary_aid',
        'subtotal',
        'ipc',
        'total'

    ];
    
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }

    public function degree()
    {        
        return $this->belongsTo(\Muserpol\Models\Degree::class);
    }

    public function category()
    {
        return $this->belongsTo('Muserpol\Models\Category');
    }

    public function contribution_type()
    {
        return $this->belongsTo('Muserpol\Models\Contribution\ContributionType');
    }
    public function unit()
    {
        return $this->belongsTo('Muserpol\Models\Unit');
    }
    public function breakdown()
    {
        return $this->belongsTo('Muserpol\Models\Breakdown');
    }
    public function voucher()
    {
        return $this->belongsToMany('Muserpol\Models\Voucher')->withTimestamps();
    }

    public function contribution_process()
    {
        return $this->morphToMany('Muserpol\Models\Contribution\ContributionProcess', 'quotable')->withTimestamps();
    }

    // Scopes

    public function scopeAddReimbursement(Builder $query, $affiliateId, array $sumColumns = [])
    {
        // Detectar las columnas seleccionadas en contributions
        $columns = $query->getQuery()->columns;

        if (empty($columns)) {
            throw new InvalidArgumentException(
                "Debes especificar columnas en contributions, incluyendo 'affiliate_id' y 'month_year'"
            );
        }

        // Verificar que existan las columnas obligatorias
        foreach (['affiliate_id', 'month_year'] as $required) {
            if (!in_array($required, $columns)) {
                throw new InvalidArgumentException(
                    "Falta la columna obligatoria '{$required}' en el SELECT de contributions"
                );
            }
        }

        // Query de reimbursements con las mismas columnas
        $reimbursementsQuery = Reimbursement::query()
            ->select($columns)
            ->where('affiliate_id', $affiliateId)
            ->whereNull('deleted_at')
            ->whereIn('month_year', $query->pluck('month_year'));

        // Unión
        $union = $query->unionAll($reimbursementsQuery);
        $unionQueryBuilder = $union->getQuery();

        // Construir dinámicamente el SELECT final
        $finalSelect = [
            'cr.affiliate_id',
            'cr.month_year',
        ];

        foreach ($sumColumns as $col) {
            $finalSelect[] = DB::raw("SUM(cr.{$col}) as {$col}");
        }

        // Query final agrupada
        return DB::table(DB::raw("({$unionQueryBuilder->toSql()}) as cr"))
            ->mergeBindings($unionQueryBuilder)
            ->select($finalSelect)
            ->groupBy('cr.affiliate_id', 'cr.month_year')
            ->orderBy('cr.month_year');
    }
}
