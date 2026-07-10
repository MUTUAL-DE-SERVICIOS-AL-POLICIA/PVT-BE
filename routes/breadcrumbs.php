<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;


Breadcrumbs::register('affiliate', function ($breadcrumbs) {
  $breadcrumbs->push('Afiliados', URL::to('affiliate'));
});

Breadcrumbs::register('show_affiliate', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('affiliate');
  $breadcrumbs->push(ucwords(strtolower($affiliate->fullName())), URL::to('affiliate/' . $affiliate->id));
});
Breadcrumbs::register('show_aid_affiliate', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('affiliate');
  $breadcrumbs->push(ucwords(strtolower($affiliate->fullName())), URL::to('affiliate/' . $affiliate->id));
});
Breadcrumbs::register('show_affiliate_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate', $affiliate);
  $breadcrumbs->push("Detalle de Aportes", route('show_contribution', $affiliate->id));
});
Breadcrumbs::register('edit_affiliate_contributions', function ($breadcrumbs, $affiliate) {
  // $breadcrumbs->parent('show_affiliate_contributions', $affiliate);
  $breadcrumbs->parent('show_affiliate', $affiliate);
  $breadcrumbs->push("Edición de Aportes", route('edit_contribution', $affiliate->id));
});
Breadcrumbs::register('affiliate_direct_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate', $affiliate);
  $breadcrumbs->push("Pago de aportes directos", route('direct_contribution', $affiliate->id));
});
Breadcrumbs::register('affiliate_direct_aid_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate_aid_contributions', $affiliate);
  $breadcrumbs->push("Pago de aportes directos de Pasivos", route('direct_aid_contribution', $affiliate->id));
});
Breadcrumbs::register('edit_affiliate_aid_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate_aid_contributions', $affiliate);
  $breadcrumbs->push("Edición de Aportes Pasivo", route('edit_aid_contribution', $affiliate->id));
});

Breadcrumbs::register('show_affiliate_aid_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_aid_affiliate', $affiliate);
  $breadcrumbs->push("Aportes Pasivo", route('show_aid_contribution', $affiliate->id));
});

Breadcrumbs::register('ret_fun_report', function ($breadcrumbs) {
  $breadcrumbs->push('Reportes de Fondo de Retiro', URL::to('ret_fun/reports'));
});
Breadcrumbs::register('retirement_fund', function ($breadcrumbs) {
  $breadcrumbs->push('Fondo de Retiro', URL::to('ret_fun'));
});
Breadcrumbs::register('quota_aid', function ($breadcrumbs) {
  $breadcrumbs->push('Cuota Mortuoria y Auxilio Mortuorio', URL::to('quota_aid'));
});
Breadcrumbs::register('show_retirement_fund', function ($breadcrumbs, $retirement_fund) {
  $breadcrumbs->parent('retirement_fund');
  $breadcrumbs->push("Trámite Nro. " . $retirement_fund->code, URL::to('ret_fun/' . $retirement_fund->id));
});
Breadcrumbs::register('show_quota_aid', function ($breadcrumbs, $quota_aid) {
  $breadcrumbs->parent('quota_aid');
  $breadcrumbs->push("Trámite Nro. " . $quota_aid->code, URL::to('quota_aid/' . $quota_aid->id));
});
Breadcrumbs::register('show_qualification_retirement_fund', function ($breadcrumbs, $retirement_fund) {

  $affiliate = $retirement_fund->affiliate;
  $name = 'Calificación';
  // if ($affiliate->globalPayRetFun()) {
  // 	$name =  $name . ' Pago Global por '.$retirement_fund->procedure_modality->name;
  // }else{
  // 	$name =  $name . ' Fondo de Retiro '.$retirement_fund->procedure_modality->name;
  // }

  $breadcrumbs->parent('show_retirement_fund', $retirement_fund);
  $breadcrumbs->push($name, URL::to('ret_fun/' . $retirement_fund->id . '/qualification'));
});
Breadcrumbs::register('show_qualification_certification_retirement_fund', function ($breadcrumbs, $retirement_fund, $number_contributions) {
  $breadcrumbs->parent('show_qualification_retirement_fund', $retirement_fund);
  $breadcrumbs->push("Certificación " . $number_contributions . " Aportes", URL::to('ret_fun/' . $retirement_fund->id . '/qualification_certification'));
});
Breadcrumbs::register('create_retirement_fund', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('retirement_fund');
  $breadcrumbs->push("Nuevo Trámite");
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});
Breadcrumbs::register('ret_fun_qualification_parameters', function ($breadcrumbs) {
  $breadcrumbs->push('Parámetros para la calificación del Fondo de Retiro');
});

Breadcrumbs::register('direct_contribution', function ($breadcrumbs) {
  $breadcrumbs->push('Trámites de Contribuciones', URL::to('direct_contribution'));
});
Breadcrumbs::register('show_direct_contribution', function ($breadcrumbs, $direct_contribution) {
  $breadcrumbs->parent('direct_contribution');
  $breadcrumbs->push("Trámite Nro. " . $direct_contribution->code, URL::to('direct_contribution/' . $direct_contribution->id));
});
Breadcrumbs::register('create_direct_contribution', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('direct_contribution');
  $breadcrumbs->push("Nuevo Trámite");
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});



Breadcrumbs::register('quota_aid_mortuary', function ($breadcrumbs) {
  $breadcrumbs->push('Cuota y Auxilio Mortuorio', URL::to('quota_aid'));
});
Breadcrumbs::register('create_quota_aid', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('quota_aid_mortuary');
  $breadcrumbs->push("Nuevo Trámite");
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});

Breadcrumbs::register('classify_contributions', function ($breadcrumbs, $retirement_fund) {
  $breadcrumbs->parent('show_retirement_fund', $retirement_fund);
  $breadcrumbs->push('Clasificacion de Aportes');
});

Breadcrumbs::register('classify_contributions-quota-aid', function ($breadcrumbs, $quota_aid) {
  $breadcrumbs->parent('show_quota_aid', $quota_aid);
  $breadcrumbs->push('Clasificacion de Aportes');
});
Breadcrumbs::register('document_scanned', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
  $breadcrumbs->push('Escanear Documento');
});

//	PAGO DE CONTRIBUCIONES


Breadcrumbs::register('payment_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->push('Nuevo Aporte');
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});

/* inbox */
Breadcrumbs::register('inbox', function ($breadcrumbs) {
  $breadcrumbs->push('Mi bandeja');
});

/* quota aid */
Breadcrumbs::register('show_qualification_quota_aid', function ($breadcrumbs, $quota_aid) {

  $affiliate = $quota_aid->affiliate;
  $name = 'Calificación';
  $breadcrumbs->parent('show_quota_aid', $quota_aid);
  $breadcrumbs->push($name, URL::to('quota_aid/' . $quota_aid->id . '/qualification'));
});

/**
 * Treasury
 */
Breadcrumbs::register('treasury_select_report', function ($breadcrumbs) {
  $breadcrumbs->push('Tesorería');
  $breadcrumbs->push('Seleccionar Reporte');
});

Breadcrumbs::register('eco_com_qualification_parameters', function ($breadcrumbs) {
  $breadcrumbs->push('Parámetros para la calificación del Complemento Económico');
});
Breadcrumbs::register('eco_com_report', function ($breadcrumbs) {
  $breadcrumbs->push('Reportes de Complemento Económico');
});


/**
 * Economic Complement
 */
Breadcrumbs::register('eco_com', function ($breadcrumbs) {
  $breadcrumbs->push('Complemento Económico', URL::to('eco_com'));
});
Breadcrumbs::register('create_eco_com_first_step', function ($breadcrumbs) {
  $breadcrumbs->parent('eco_com');
  $breadcrumbs->push("Nuevo Trámite", URL::to('economic_complement_first_step'));
});
Breadcrumbs::register('create_eco_com', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('create_eco_com_first_step');
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});
Breadcrumbs::register('show_eco_com', function ($breadcrumbs, $eco_com) {
  $breadcrumbs->parent('eco_com');
  $breadcrumbs->push('Trámite N°: ' . $eco_com->code);
});

Breadcrumbs::register('loans', function ($breadcrumbs) {
  $breadcrumbs->push('Préstamos en Mora', URL::to('overdue_loan'));
});
Breadcrumbs::register('show_loan', function ($breadcrumbs, $loan) {
  $breadcrumbs->parent('loans');
  $breadcrumbs->push('Sincronización del ' . $loan, URL::to('overdue_loan/' . $loan));
});
