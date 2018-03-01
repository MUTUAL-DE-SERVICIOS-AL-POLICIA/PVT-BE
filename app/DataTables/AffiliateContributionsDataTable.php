<?php

namespace Muserpol\DataTables;

use Muserpol\User;
use Yajra\DataTables\Services\DataTable;
use Muserpol\Models\Affiliate;

class AffiliateContributionsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables($query)
            ->addColumn('action', 'affiliatecontributions.action');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \Muserpol\User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $affiliate = Affiliate::find($this->affiliate_id);
        $contributions =$affiliate->contributions;

        return $contributions;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'affiliate_id',
            'created_at',
            'updated_at'
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AffiliateContributions_' . date('YmdHis');
    }
}
