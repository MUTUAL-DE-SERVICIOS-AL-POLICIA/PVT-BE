<?php 

Breadcrumbs::register('affiliate', function($breadcrumbs)
{
	$breadcrumbs->push('Afiliados', URL::to('affiliate'));
});

Breadcrumbs::register('show_affiliate', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->parent('affiliate');
	$breadcrumbs->push($affiliate->first_name, URL::to('affiliate/'.$affiliate->id));
});

Breadcrumbs::register('retirement_fund', function($breadcrumbs)
{
	$breadcrumbs->push('Fondo de Retiro', URL::to('ret_fun'));
});
Breadcrumbs::register('show_retirement_fund', function($breadcrumbs, $retirement_fund)
{
	$breadcrumbs->parent('retirement_fund');
	$breadcrumbs->push	("Trámite Nro. ".$retirement_fund->code, URL::to('ret_fun/'.$retirement_fund->id));
});