<?php

namespace Muserpol\Models\Contribution;

use Illuminate\Database\Eloquent\Model;
use DB;
class ContributionProcess extends Model
{
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo('Muserpol\User');
    }
    public function affiliate()
    {
        return $this->belongsTo('Muserpol\Models\Affiliate');
    }
    public function workflow()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\Workflow');
    }
    public function contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\Contribution', 'quotable')->withTimestamps();
    }
    public function aid_contributions()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\AidContribution', 'quotable')->withTimestamps();
    }
    public function reimbursements()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\Reimbursement', 'quotable')->withTimestamps();
    }
    public function aid_reimbursements()
    {
        return $this->morphedByMany('Muserpol\Models\Contribution\AidReimbursement', 'quotable')->withTimestamps();
    }
    public function voucher() {
        return $this->morphOne('Muserpol\Models\Voucher','payable');
    }
    public function wf_state()
    {
        return $this->belongsTo('Muserpol\Models\Workflow\WorkflowState', 'wf_state_current_id', 'id');
    }
    public function tags()
    {
        return $this->morphToMany('Muserpol\Models\Tag', 'taggable')->withPivot(['user_id', 'date'])->withTimestamps();
    }
    public function wf_records()
    {
        return $this->morphMany('Muserpol\Models\Workflow\WorkflowRecord', 'recordable');
    }
    public function direct_contribution()
    {
        return $this->belongsTo('Muserpol\Models\Contribution\DirectContribution');
    }
    public function aidContributionsWithReimbursements()
    {
        $contribution_ids = join(', ',ContributionProcess::find($this->id)->aid_contributions->pluck('id')->toArray());
        $reimbursement_ids = join(', ',ContributionProcess::find($this->id)->aid_reimbursements->pluck('id')->toArray());
        if($reimbursement_ids == '')
            $reimbursement_ids = '0';
        if ($contribution_ids == '')
            $contribution_ids = '0';
        $contributions = DB::select("
            SELECT
                contributions_reimbursements.month_year,
                contributions_reimbursements.affiliate_id,
                sum(contributions_reimbursements.rent) as rent,
                sum(contributions_reimbursements.dignity_rent) as dignity_rent,
                sum(contributions_reimbursements.quotable) as quotable,
                sum(contributions_reimbursements.mortuary_aid) as mortuary_aid,
                sum(contributions_reimbursements.interest) as interest,
                sum(contributions_reimbursements.total) as total,
                contributions_reimbursements.type as type
                FROM(
                SELECT
                    aid_reimbursements.id,
                    aid_reimbursements.affiliate_id,
                    aid_reimbursements.mortuary_aid,
                    aid_reimbursements.rent,
                    aid_reimbursements.dignity_rent,
                    aid_reimbursements.quotable,
                    aid_reimbursements.month_year,
                    aid_reimbursements.interest,
                    aid_reimbursements.total,
                    'R' as type
                        FROM aid_reimbursements
                        WHERE aid_reimbursements.deleted_at is null
                            and aid_reimbursements.id in (".$reimbursement_ids. ")
                UNION ALL
                SELECT
                    aid_contributions.id,
                    aid_contributions.affiliate_id,
                    aid_contributions.mortuary_aid,
                    aid_contributions.rent,
                    aid_contributions.dignity_rent,
                    aid_contributions.quotable,
                    aid_contributions.month_year,
                    aid_contributions.interest,
                    aid_contributions.total,
                    'C' as type
                        FROM aid_contributions
                        WHERE aid_contributions.deleted_at is null
                            and aid_contributions.id in (".$contribution_ids.")
            ) as contributions_reimbursements
                GROUP BY contributions_reimbursements.month_year, contributions_reimbursements.affiliate_id, contributions_reimbursements.type
                ORDER BY month_year DESC");
        return array_reverse($contributions);
    }
    public function contributionsWithReimbursements()
    {
        $contribution_ids = join(', ',ContributionProcess::find($this->id)->contributions->pluck('id')->toArray());
        $reimbursement_ids = join(', ',ContributionProcess::find($this->id)->reimbursements->pluck('id')->toArray());
        if($reimbursement_ids == '')
            $reimbursement_ids = '0';
        if($contribution_ids == '')
            $contribution_ids = '0';
        $contributions = DB::select("
            SELECT
                contributions_reimbursements.month_year,
                contributions_reimbursements.affiliate_id,
                sum(contributions_reimbursements.quotable) as quotable,
                sum(contributions_reimbursements.retirement_fund) as retirement_fund,
                sum(contributions_reimbursements.mortuary_quota) as mortuary_quota,
                sum(contributions_reimbursements.interest) as interest,
                sum(contributions_reimbursements.total) as total
                FROM(
                SELECT
                    reimbursements.id,
                    reimbursements.affiliate_id,
                    reimbursements.degree_id,
                    reimbursements.unit_id,
                    reimbursements.breakdown_id,
                    reimbursements.month_year,
                    reimbursements.type,
                    reimbursements.base_wage,
                    reimbursements.seniority_bonus,
                    reimbursements.study_bonus,
                    reimbursements.position_bonus,
                    reimbursements.border_bonus,
                    reimbursements.east_bonus,
                    reimbursements.public_security_bonus,
                    reimbursements.gain,
                    reimbursements.payable_liquid,
                    reimbursements.quotable,
                    reimbursements.retirement_fund,
                    reimbursements.mortuary_quota,
                    reimbursements.subtotal,
                    reimbursements.total,
                    reimbursements.interest
                        FROM reimbursements
                        WHERE reimbursements.deleted_at is null
                            and reimbursements.id in (".$reimbursement_ids. ")
                UNION ALL
                SELECT
                    contributions.id,
                    contributions.affiliate_id,
                    contributions.degree_id,
                    contributions.unit_id,
                    contributions.breakdown_id,
                    contributions.month_year,
                    contributions.type,
                    contributions.base_wage,
                    contributions.seniority_bonus,
                    contributions.study_bonus,
                    contributions.position_bonus,
                    contributions.border_bonus,
                    contributions.east_bonus,
                    contributions.public_security_bonus,
                    contributions.gain,
                    contributions.payable_liquid,
                    contributions.quotable,
                    contributions.retirement_fund,
                    contributions.mortuary_quota,
                    contributions.subtotal,
                    contributions.total,
                    contributions.interest
                        FROM contributions
                        WHERE contributions.deleted_at is null
                            and contributions.id in (".$contribution_ids.")
            ) as contributions_reimbursements
                GROUP BY contributions_reimbursements.month_year, contributions_reimbursements.affiliate_id, contributions_reimbursements.type
                ORDER BY month_year DESC");
        return array_reverse($contributions);
    }
}
