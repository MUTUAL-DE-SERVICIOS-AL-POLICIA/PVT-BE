<?php

use Diglactic\Breadcrumbs\Breadcrumbs;


Breadcrumbs::for('affiliate', function ($breadcrumbs) {
  $breadcrumbs->push('Afiliados', URL::to('affiliate'));
});

Breadcrumbs::for('show_affiliate', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('affiliate');
  $breadcrumbs->push(ucwords(strtolower($affiliate->fullName())), URL::to('affiliate/' . $affiliate->id));
});
Breadcrumbs::for('show_aid_affiliate', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('affiliate');
  $breadcrumbs->push(ucwords(strtolower($affiliate->fullName())), URL::to('affiliate/' . $affiliate->id));
});
Breadcrumbs::for('show_affiliate_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate', $affiliate);
  $breadcrumbs->push("Detalle de Aportes", route('show_contribution', $affiliate->id));
});
Breadcrumbs::for('edit_affiliate_contributions', function ($breadcrumbs, $affiliate) {
  // $breadcrumbs->parent('show_affiliate_contributions', $affiliate);
  $breadcrumbs->parent('show_affiliate', $affiliate);
  $breadcrumbs->push("Edición de Aportes", route('edit_contribution', $affiliate->id));
});
Breadcrumbs::for('affiliate_direct_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate', $affiliate);
  $breadcrumbs->push("Pago de aportes directos", route('direct_contribution', $affiliate->id));
});
Breadcrumbs::for('affiliate_direct_aid_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate_aid_contributions', $affiliate);
  $breadcrumbs->push("Pago de aportes directos de Pasivos", route('direct_aid_contribution', $affiliate->id));
});
Breadcrumbs::for('edit_affiliate_aid_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_affiliate_aid_contributions', $affiliate);
  $breadcrumbs->push("Edición de Aportes Pasivo", route('edit_aid_contribution', $affiliate->id));
});

Breadcrumbs::for('show_affiliate_aid_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('show_aid_affiliate', $affiliate);
  $breadcrumbs->push("Aportes Pasivo", route('show_aid_contribution', $affiliate->id));
});

Breadcrumbs::for('ret_fun_report', function ($breadcrumbs) {
  $breadcrumbs->push('Reportes de Fondo de Retiro', URL::to('ret_fun/reports'));
});
Breadcrumbs::for('retirement_fund', function ($breadcrumbs) {
  $breadcrumbs->push('Fondo de Retiro', URL::to('ret_fun'));
});
Breadcrumbs::for('quota_aid', function ($breadcrumbs) {
  $breadcrumbs->push('Cuota Mortuoria y Auxilio Mortuorio', URL::to('quota_aid'));
});
Breadcrumbs::for('show_retirement_fund', function ($breadcrumbs, $retirement_fund) {
  $breadcrumbs->parent('retirement_fund');
  $breadcrumbs->push("Trámite Nro. " . $retirement_fund->code, URL::to('ret_fun/' . $retirement_fund->id));
});
Breadcrumbs::for('show_quota_aid', function ($breadcrumbs, $quota_aid) {
  $breadcrumbs->parent('quota_aid');
  $breadcrumbs->push("Trámite Nro. " . $quota_aid->code, URL::to('quota_aid/' . $quota_aid->id));
});
Breadcrumbs::for('show_qualification_retirement_fund', function ($breadcrumbs, $retirement_fund) {

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
Breadcrumbs::for('show_qualification_certification_retirement_fund', function ($breadcrumbs, $retirement_fund, $number_contributions) {
  $breadcrumbs->parent('show_qualification_retirement_fund', $retirement_fund);
  $breadcrumbs->push("Certificación " . $number_contributions . " Aportes", URL::to('ret_fun/' . $retirement_fund->id . '/qualification_certification'));
});
Breadcrumbs::for('create_retirement_fund', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('retirement_fund');
  $breadcrumbs->push("Nuevo Trámite");
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});
Breadcrumbs::for('ret_fun_qualification_parameters', function ($breadcrumbs) {
  $breadcrumbs->push('Parámetros para la calificación del Fondo de Retiro');
});

Breadcrumbs::for('direct_contribution', function ($breadcrumbs) {
  $breadcrumbs->push('Trámites de Contribuciones', URL::to('direct_contribution'));
});
Breadcrumbs::for('show_direct_contribution', function ($breadcrumbs, $direct_contribution) {
  $breadcrumbs->parent('direct_contribution');
  $breadcrumbs->push("Trámite Nro. " . $direct_contribution->code, URL::to('direct_contribution/' . $direct_contribution->id));
});
Breadcrumbs::for('create_direct_contribution', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('direct_contribution');
  $breadcrumbs->push("Nuevo Trámite");
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});



Breadcrumbs::for('quota_aid_mortuary', function ($breadcrumbs) {
  $breadcrumbs->push('Cuota y Auxilio Mortuorio', URL::to('quota_aid'));
});
Breadcrumbs::for('create_quota_aid', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('quota_aid_mortuary');
  $breadcrumbs->push("Nuevo Trámite");
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});

Breadcrumbs::for('classify_contributions', function ($breadcrumbs, $retirement_fund) {
  $breadcrumbs->parent('show_retirement_fund', $retirement_fund);
  $breadcrumbs->push('Clasificacion de Aportes');
});

Breadcrumbs::for('classify_contributions-quota-aid', function ($breadcrumbs, $quota_aid) {
  $breadcrumbs->parent('show_quota_aid', $quota_aid);
  $breadcrumbs->push('Clasificacion de Aportes');
});
Breadcrumbs::for('document_scanned', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
  $breadcrumbs->push('Escanear Documento');
});

//	PAGO DE CONTRIBUCIONES


Breadcrumbs::for('payment_contributions', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->push('Nuevo Aporte');
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});

/* inbox */
Breadcrumbs::for('inbox', function ($breadcrumbs) {
  $breadcrumbs->push('Mi bandeja');
});

/* quota aid */
Breadcrumbs::for('show_qualification_quota_aid', function ($breadcrumbs, $quota_aid) {

  $affiliate = $quota_aid->affiliate;
  $name = 'Calificación';
  $breadcrumbs->parent('show_quota_aid', $quota_aid);
  $breadcrumbs->push($name, URL::to('quota_aid/' . $quota_aid->id . '/qualification'));
});

/**
 * Treasury
 */
Breadcrumbs::for('treasury_select_report', function ($breadcrumbs) {
  $breadcrumbs->push('Tesorería');
  $breadcrumbs->push('Seleccionar Reporte');
});

Breadcrumbs::for('eco_com_qualification_parameters', function ($breadcrumbs) {
  $breadcrumbs->push('Parámetros para la calificación del Complemento Económico');
});
Breadcrumbs::for('eco_com_report', function ($breadcrumbs) {
  $breadcrumbs->push('Reportes de Complemento Económico');
});


/**
 * Economic Complement
 */
Breadcrumbs::for('eco_com', function ($breadcrumbs) {
  $breadcrumbs->push('Complemento Económico', URL::to('eco_com'));
});
Breadcrumbs::for('create_eco_com_first_step', function ($breadcrumbs) {
  $breadcrumbs->parent('eco_com');
  $breadcrumbs->push("Nuevo Trámite", URL::to('economic_complement_first_step'));
});
Breadcrumbs::for('create_eco_com', function ($breadcrumbs, $affiliate) {
  $breadcrumbs->parent('create_eco_com_first_step');
  $breadcrumbs->push($affiliate->fullName(), route('affiliate.show', $affiliate->id));
});
Breadcrumbs::for('show_eco_com', function ($breadcrumbs, $eco_com) {
  $breadcrumbs->parent('eco_com');
  $breadcrumbs->push('Trámite N°: ' . $eco_com->code);
});

Breadcrumbs::for('loans', function ($breadcrumbs) {
  $breadcrumbs->push('Préstamos en Mora', URL::to('overdue_loan'));
});
Breadcrumbs::for('show_loan', function ($breadcrumbs, $loan) {
  $breadcrumbs->parent('loans');
  $breadcrumbs->push('Sincronización del ' . $loan, URL::to('overdue_loan/' . $loan));
});
