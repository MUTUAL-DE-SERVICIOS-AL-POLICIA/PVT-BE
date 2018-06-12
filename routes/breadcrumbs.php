<?php 

Breadcrumbs::register('affiliate', function($breadcrumbs)
{
	$breadcrumbs->push('Afiliados', URL::to('affiliate'));
});

Breadcrumbs::register('show_affiliate', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->parent('affiliate');
	$breadcrumbs->push(ucwords(strtolower($affiliate->fullName())), URL::to('affiliate/'.$affiliate->id));
});
Breadcrumbs::register('show_affiliate_contributions', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->parent('show_affiliate', $affiliate);
	$breadcrumbs->push("Aportes", route('show_contribution', $affiliate->id));
});
Breadcrumbs::register('edit_affiliate_contributions', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->parent('show_affiliate_contributions', $affiliate);
	$breadcrumbs->push("Edición de Aportes", route('edit_contribution', $affiliate->id));
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
Breadcrumbs::register('show_qualification_retirement_fund', function($breadcrumbs, $retirement_fund)
{
	$breadcrumbs->parent('show_retirement_fund', $retirement_fund);
	$breadcrumbs->push	("Calificación", URL::to('ret_fun/'.$retirement_fund->id.'/qualification'));
});
Breadcrumbs::register('show_qualification_certification_retirement_fund', function($breadcrumbs, $retirement_fund, $number_contributions)
{
	$breadcrumbs->parent('show_qualification_retirement_fund', $retirement_fund);
	$breadcrumbs->push	("Certificación ".$number_contributions." Aportes", URL::to('ret_fun/'.$retirement_fund->id.'/qualification_certification'));
});
Breadcrumbs::register('create_retirement_fund', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->parent('retirement_fund');
	$breadcrumbs->push("Nuevo Tramite");
	$breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});


Breadcrumbs::register('quota_aid_mortuary', function($breadcrumbs)
{
	$breadcrumbs->push('Cuota y Auxilio Mortuorio', URL::to('quota_aid'));
});
Breadcrumbs::register('create_quota_aid', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->parent('quota_aid_mortuary');
	$breadcrumbs->push("Nuevo Tramite");
	$breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});

Breadcrumbs::register('classify_contributions', function($breadcrumbs,$retirement_fund)
{	
	$breadcrumbs->parent('show_retirement_fund',$retirement_fund);
	$breadcrumbs->push('Clasificacion de Aportes');
});

//	PAGO DE CONTRIBUCIONES


Breadcrumbs::register('payment_contributions', function($breadcrumbs, $affiliate)
{
	$breadcrumbs->push('Nuevo Aporte');
	$breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});

/* inbox */
Breadcrumbs::register('inbox', function($breadcrumbs)
{
	$breadcrumbs->push('Mi bandeja');
});

