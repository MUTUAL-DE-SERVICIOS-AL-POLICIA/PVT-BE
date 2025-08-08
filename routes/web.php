<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Muserpol\DataTables\AffiliateContributionsDataTable;
use Muserpol\Models\Template;
use Illuminate\Support\Facades\Blade;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Workflow\WorkflowState;
use Carbon\Carbon;
use Muserpol\Models\RetirementFund\RetFunTemplate;
use Muserpol\Helpers\Util;
use Muserpol\Models\QuotaAidMortuary\QuotaAidMortuary;
use Muserpol\Models\Contribution\ContributionProcess;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Models\EconomicComplement\EconomicComplement;
use Maatwebsite\Excel\Facades\Excel;
use Muserpol\Exports\EcoComBankExport;
use Muserpol\Models\EconomicComplement\EcoComProcedure;

Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/minor', 'HomeController@minor')->name("minor");
Auth::routes();

//afiliates
Route::group(['middleware' => ['auth']], function () {
  App::setLocale("es");

  Route::get('/changerol', 'UserController@changerol')->name('changerol');
  Route::post('postchangerol', 'UserController@postchangerol');

  Route::group(['middleware' => ['session']], function () {

    Route::get('/', 'HomeController@index')->name("main");

    //Roles y permisos
    Route::resource('permission', 'PermissionController');

    Route::resource('user', 'UserController');
    Route::get('user/create', 'UserController@create');
    Route::post('registrar', ['as' => 'registrar', 'uses' => 'UserController@store']);
    Route::get('/{id}/edit', 'UserController@edit');
    Route::post('/update/{id}', 'UserController@store');
    Route::get('user/inactive/{user}', 'UserController@inactive');
    Route::get('user/active/{user}', 'UserController@active');
    //Route::get('users/index','UserController@index');
    Route::get('usersGetData', 'UserController@getUserDatatable')->name('user_list');
    Route::get('usersLdapUpdate', 'UserController@getUsersLdapUpdate')->name('user_ldap_sync');

    //ROUTES TO E SYSTEM PARAMENTERS
    Route::get('ret_fun_settings', 'HomeController@retFunSettings');
    Route::resource('ret_fun_procedure', 'RetFunProcedureController');

    Route::resource('affiliate', 'AffiliateController');
    Route::resource('overdue_loan', 'LoanController');

    Route::patch('/update_affiliate/{affiliate}', 'AffiliateController@update')->name('update_affiliate');
    Route::patch('/update_affiliate_police/{affiliate}', 'AffiliateController@update_affiliate_police')->name('update_affiliate_police');

    Route::patch('/update_beneficiaries/{retirement_fund}', 'RetirementFundController@updateBeneficiaries')->name('update_beneficiaries');
    Route::patch('/update_beneficiary_testimony_ret_fun/{retirement_fund}', 'RetirementFundController@updateBeneficiaryTestimony')->name('update_beneficiary_testimony');
    Route::get('/ret_fun_beneficiaries_testimonies/{ret_fun_id}', 'RetirementFundController@getTestimonies')->name('ret_fun_beneficiaries_testimonies');

    Route::get('/deleteDevice_affiliate/{affiliate}', 'AffiliateController@deleteDevice')->name('deleteDevice_affiliate');
    Route::get('/deleteEnrolled_affiliate/{affiliate}', 'AffiliateController@deleteEnrolled')->name('deleteEnrolled_affiliate');
    Route::get('/CIDevice/{affiliate}/{valor}', 'AffiliateController@CIDevice')->name('CIDevice');
    //SpouseControler
    Route::post('spouse/{affiliate_id}', 'SpouseController@store')->name('spouse_store');
    Route::patch('/update_spouse/{affiliate_id}', 'SpouseController@update')->name('update_spouse');
    Route::get('/person-data/{identityCard}', 'SpouseController@findSpouseOrAffiliateData');
    Route::get('get_all_affiliates', 'AffiliateController@getAllAffiliates');

    //Scanned Documents
    Route::resource('scanned_documents', 'ScannedDocumentController');
    Route::get('document_scanned/{affiliate_id}', 'ScannedDocumentController@create_document')->name('document_scanned');
    Route::get('document_scanned_upload/{affiliate_id}', 'ScannedDocumentController@upload')->name('document_scanned_upload');


    //retirement fund
    //RetirementFundRequirements
    //Route::resource('ret_fun', 'RetirementFundRequirementController@retFun');
    Route::get('affiliate/{affiliate}/ret_fun', 'RetirementFundRequirementController@retFun');
    // Route::get('/home', 'HomeController@index')->name('home');
    Route::get('get_all_ret_fun', 'RetirementFundController@getAllRetFun');
    Route::get('ret_fun/reports', 'RetFunReportController@index');
    Route::resource('ret_fun', 'RetirementFundController');
    Route::get('ret_fun/{ret_fun_id}/qualification', 'RetirementFundController@qualification')->name('ret_fun_qualification');
    Route::get('ret_fun/{ret_fun_id}/get_average_quotable', 'RetirementFundController@getAverageQuotable');
    Route::get('ret_fun/{ret_fun_id}/qualification/certification', 'RetirementFundController@qualificationCertification')->name('qualification_certification');
    Route::get('ret_fun/{ret_fun_id}/save_average_quotable', 'RetirementFundController@saveAverageQuotable')->name('save_average_quotable');
    Route::patch('ret_fun/{ret_fun_id}/save_total_ret_fun', 'RetirementFundController@saveTotalRetFun')->name('save_total_ret_fun');
    Route::patch('ret_fun/{ret_fun_id}/save_percentages', 'RetirementFundController@savePercentages')->name('save_percentages');
    Route::patch('ret_fun/{ret_fun_id}/save_percentages_availability', 'RetirementFundController@savePercentagesAvailability')->name('save_percentages_availability');
    Route::patch('ret_fun/{ret_fun_id}/save_total_ret_fun_availability', 'RetirementFundController@saveTotalRetFunAvailability')->name('save_total_ret_fun_availability');
    Route::get('get_data_certification/{ret_fun_id}', 'RetirementFundController@getDataQualificationCertification')->name('get_data_certification');
    Route::get('get_data_availability/{ret_fun_id}', 'RetirementFundController@getDataQualificationAvailability')->name('get_data_availability');
    Route::get('affiliate/{affiliate}/procedure_create', 'RetirementFundRequirementController@generateProcedure');
    Route::resource('ret_fun_observation', 'RetirementFundObservationController');
    Route::post('retFuneditObservation', 'RetirementFundObservationController@editObservation')->name('retFuneditObservation');
    Route::post('retFundeleteObservation', 'RetirementFundObservationController@destroy')->name('retFundeleteObservation');
    Route::post('ret_fun/{ret_fun_id}/edit_requirements', 'RetirementFundController@editRequirements')->name('edit_requirements');
    Route::get('ret_fun/{ret_fun_id}/correlative/{wf_state_id}', 'RetirementFundController@getCorrelative')->name('ret_fun_get_correlative');
    Route::get('ret_fun/{ret_fun_id}/info', 'RetirementFundController@info')->name('ret_fun_info');

    //Retirement Fund Certification
    Route::get('ret_fun/{retirement_fund}/print/liquidation', 'RetirementFundCertificationController@printLiquidation')->name('ret_fun_print_liquidation');
    Route::get('ret_fun/{retirement_fund}/print/reception', 'RetirementFundCertificationController@printReception')->name('ret_fun_print_reception');
    Route::get('affiliate/{affiliate}/print/file', 'RetirementFundCertificationController@printFile')->name('ret_fun_print_file');
    Route::get('ret_fun/{retirement_fund}/print/legal_review', 'RetirementFundCertificationController@printLegalReview')->name('ret_fun_print_legal_review');
    Route::get('ret_fun/{retirement_fund}/print/beneficiaries_qualification', 'RetirementFundCertificationController@printBeneficiariesQualification')->name('ret_fun_print_beneficiaries_qualification');
    Route::get('ret_fun/{retirement_fund}/print/qualification_average_salary_quotable', 'RetirementFundCertificationController@printQualificationAverageSalaryQuotable')->name('ret_fun_print_qualification_average_salary_quotable');
    Route::get('ret_fun/{retirement_fund}/print/data_qualification', 'RetirementFundCertificationController@printDataQualification')->name('ret_fun_print_data_qualification');
    Route::get('ret_fun/{retirement_fund}/print/data_qualification_availability', 'RetirementFundCertificationController@printDataQualificationAvailability')->name('ret_fun_print_data_qualification_availability');
    Route::get('ret_fun/{retirement_fund}/print/data_qualification_ret_fun_availability', 'RetirementFundCertificationController@printDataQualificationRetFunAvailability')->name('ret_fun_print_data_qualification_ret_fun_availability');
    Route::get('ret_fun/{retirement_fund}/print/all_qualification', 'RetirementFundCertificationController@printAllQualification')->name('ret_fun_print_all_qualification');
    Route::get('ret_fun/{affiliate}/print/ret_fun_commitment_letter', 'RetirementFundCertificationController@printRetFunCommitmentLetter')->name('print_ret_fun_commitment_letter');
    Route::get('ret_fun/{affiliate}/print/voucher/{voucher}', 'RetirementFundCertificationController@printVoucher')->name('ret_fun_print_voucher');
    Route::get('print_contributions_quote', 'RetirementFundCertificationController@printDirectContributionQuote');
    Route::get('ret_fun/{retirement_fund}/print/legal_dictum', 'RetirementFundCertificationController@printLegalDictum')->name('ret_fun_print_legal_dictum');
    Route::get('ret_fun/{retirement_fund}/print/headship_review', 'RetirementFundCertificationController@printHeadshipReview')->name('ret_fun_print_headship_review');
    Route::get('ret_fun/{retirement_fund}/print/legal_resolution', 'RetirementFundCertificationController@printLegalResolution')->name('ret_fun_print_legal_resolution');
    Route::post('ret_fun/{retirement_fund}/save_message', 'RetirementFundController@saveMessageContributionType')->name('save_message_contribution_type');
    Route::post('ret_fun/{retirement_fund}/save_certification_note', 'RetirementFundController@saveCertificationNote')->name('save_certification_note');
    Route::post('procedure/print/send', 'InboxController@printSend')->name('inbox_send');
    Route::post('procedure/print/send_eco_com', 'InboxController@printSendEcoCom')->name('inbox_send_eco_com');
    Route::post('ret_fun/{ret_fun}/save_judicial_retention', 'RetirementFundController@createJudicialRetention');
    Route::get('ret_fun/{ret_fun}/obtain_judicial_retention', 'RetirementFundController@obtainJudicialRetention');
    Route::patch('ret_fun/{ret_fun}/modify_judicial_retention', 'RetirementFundController@modifyJudicialRetention');
    Route::delete('ret_fun/{ret_fun}/cancel_judicial_retention', 'RetirementFundController@cancelJudicialRetention');

    //Quota Aid Certification
    Route::get('quota_aid/{affiliate}/print/quota_aid_commitment_letter', 'QuotaAidCertificationController@printQuotaAidCommitmentLetter')->name('print_quota_aid_commitment_letter');
    Route::get('quota_aid/{affiliate}/print/quota_aid_voucher/{voucher}', 'QuotaAidCertificationController@printVoucherQuoteAid')->name('quota_aid_print_voucher');
    Route::get('print_contributions_quote_aid', 'QuotaAidCertificationController@printDirectContributionQuoteAid');
    Route::get('quota_aid/{quota_aid}/print/qualification', 'QuotaAidCertificationController@printAllQualification')->name('quota_aid_print_all_qualification');

    Route::get('quota_aid/{quota_aid}/print/reception', 'QuotaAidCertificationController@printReception')->name('quota_aid_print_reception');
    Route::post('quota_aid/{quota_aid}/save_certification_note', 'QuotaAidCertificationController@saveCertificationNote')->name('save_certification_note');
    Route::get('quota_aid/{quota_aid_id}/correlative/{wf_state_id}', 'QuotaAidCertificationController@getCorrelative')->name('quota_aid_get_correlative');
    Route::patch('/update_beneficiaries_quota_aid/{quota_aid_id}', 'QuotaAidMortuaryController@updateBeneficiaries')->name('update_beneficiaries_quota_aid');
    Route::patch('/update_beneficiary_testimony_quota_aid/{quota_aid_id}', 'QuotaAidMortuaryController@updateBeneficiaryTestimony')->name('update_beneficiary_testimony_quota_aid');
    Route::get('/quota_aid_beneficiaries_testimonies/{ret_fun_id}', 'QuotaAidMortuaryController@getTestimonies')->name('ret_fun_beneficiaries_testimonies_quota_aid');
    Route::get('quota_aid/{quota_aid_id}/qualification', 'QuotaAidMortuaryController@qualification')->name('quota_aid_qualification');
    // Route::get('quota_aid/{quota_aid_id}/save_subtotal', 'QuotaAidMortuaryController@saveSubtotal')->name('quota_aid_save_subtotal');
    Route::patch('quota_aid/{quota_aid_id}/calculate_total', 'QuotaAidMortuaryController@calculateTotal')->name('quota_aid_calculate_total');
    Route::patch('quota_aid/{quota_aid_id}/save_discounts', 'QuotaAidMortuaryController@saveDiscounts')->name('quota_aid_save_discounts');
    Route::patch('quota_aid/{quota_aid_id}/save_percentages', 'QuotaAidMortuaryController@savePercentages')->name('quota_aid_save_percentages');
    Route::patch('/update_information_quota_aid', 'QuotaAidMortuaryController@updateInformation')->name('update_information_quota_aid');
    Route::resource('quota_aid_observation', 'QuotaAidObservationController');
    Route::post('quotaAideditObservation', 'QuotaAidObservationController@editObservation')->name('quotaAideditObservation');
    Route::post('quotaAiddeleteObservation', 'QuotaAidObservationController@destroy')->name('quotaAiddeleteObservation');
    Route::get('affiliate/{affiliate}/ret_fun/create', 'RetirementFundController@generateProcedure')->middleware('affiliate_has_ret_fun')->name('create_ret_fun');
    Route::post('ret_fun/{retirement_fund}/legal_review/create', 'RetirementFundController@storeLegalReview')->name('store_ret_fun_legal_review_create');

    Route::patch('/update_information_rf', 'RetirementFundController@updateInformation')->name('update_information_rf');
    Route::post('quota_aid/{quota_aid}/legal_review/create', 'QuotaAidMortuaryController@storeLegalReview')->name('store_quota_aid_legal_review_create');
    Route::get('quota_aid/{quota_aid}/print/legal_review', 'QuotaAidCertificationController@printLegalReview')->name('quota_aid_print_legal_review');
    Route::get('quota_aid/{quota_aid}/print/liquidation', 'QuotaAidCertificationController@printLiquidation')->name('quota_aid_print_liquidation');
    Route::get('quota_aid/{quota_aid}/print/file', 'QuotaAidCertificationController@printFile')->name('quota_aid_print_file');
    Route::get('quota_aid/{quota_aid}/print/certification', 'QuotaAidCertificationController@printCertification2')->name('quota_aid_print_certification');
    Route::get('quota_aid/{quota_aid}/print/legal_dictum', 'QuotaAidCertificationController@printLegalDictum')->name('quota_aid_print_legal_dictum');
    Route::get('quota_aid/{quota_aid}/print/headship_review', 'QuotaAidCertificationController@printHeadshipReview')->name('quota_aid_print_headship_review');
    Route::get('quota_aid/{quota_aid}/print/legal_resolution', 'QuotaAidCertificationController@printLegalResolution')->name('quota_aid_print_legal_resolution');
    Route::post('quota_aid/{quota_aid}/save_judicial_retention', 'QuotaAidMortuaryController@createJudicialRetention');
    Route::get('quota_aid/{quota_aid}/obtain_judicial_retention', 'QuotaAidMortuaryController@obtainJudicialRetention');
    Route::patch('quota_aid/{quota_aid}/modify_judicial_retention', 'QuotaAidMortuaryController@modifyJudicialRetention');
    Route::delete('quota_aid/{quota_aid}/cancel_judicial_retention', 'QuotaAidMortuaryController@cancelJudicialRetention');

    // tags
    Route::resource('/tag', "TagController");
    Route::get('/tag_module', "TagController@module")->name('tag_module');
    Route::get('/tag_wf_state', "TagController@wfState")->name('tag_wf_state');
    Route::get('/tag_ret_fun/{ret_fun_id}', "TagController@retFun")->name('tag_ret_fun');
    Route::post('/update_tag_ret_fun/{ret_fun_id}', "TagController@updateRetFun")->name('update_tag_ret_fun');
    Route::get('get_tags', 'TagController@getTags')->name('tag_list');
    Route::get('/get_tag/{tag_id}', 'TagController@getTag');
    Route::get('/tag_wf_state_list', 'TagController@tagWfState');
    Route::get('/get_tag_wf_state', 'TagController@getTagWfState');
    Route::post('/update_tag_wf_state', 'TagController@updateTagWfState');
    Route::get('/tag_quota_aid/{quota_aid_id}', "TagController@quotaAid")->name('tag_quota_aid');
    Route::post('/update_tag_quota_aid/{quota_aid_id}', "TagController@updateQuotaAid")->name('update_tag_quota_aid');
    Route::get('/tag_eco_com/{eco_com_id}', "TagController@ecoCom")->name('tag_eco_com');
    Route::post('/update_tag_eco_com/{eco_com_id}', "TagController@updateEcoCom")->name('update_tag_eco_com');
    Route::get('/tag_affiliate/{affiliate_id}', "TagController@affiliate")->name('tag_affiliate');
    Route::post('/update_tag_affiliate/{affiliate_id}', "TagController@updateAffiliate")->name('update_tag_affiliate');
    //QuotaAidMortuory
    Route::get('affiliate/{affiliate}/quota_aid/create', 'QuotaAidMortuaryController@generateProcedure')->name('create_quota_aid');
    Route::get('get_all_quota_aid', 'QuotaAidMortuaryController@getAllQuotaAid');
    Route::resource('quota_aid', 'QuotaAidMortuaryController');
    Route::post('quota_aid/{quota_aid_id}/edit_requirements', 'QuotaAidMortuaryController@editRequirements')->name('edit_quota_aid_requirements');

    Route::resource('affiliate_folder', 'AffiliateFolderController');
    Route::post('editFolder', 'AffiliateFolderController@editFolder')->name('editFolder');
    Route::post('deleteFolder', 'AffiliateFolderController@destroy')->name('deleteFolder');

    //searcherController
    Route::get('search/{ci}', 'SearcherController@search');
    Route::get('search_ajax', 'SearcherController@searchAjax');
    Route::post('search_ajax_only_affiliate', 'SearcherController@searchAjaxOnlyAffiliate');

    //Contributions
    Route::resource('contribution', 'ContributionController');
    Route::get('affiliate/{affiliate}/contribution/edit', 'ContributionController@getAffiliateContributions')->name('edit_contribution');
    Route::get('affiliate/{affiliate}/contribution/detail', 'ContributionController@getContributionsByMonth')->name('detail_contribution');
    Route::get('affiliate/{affiliate}/contribution/direct', 'ContributionController@directContributions')->name('direct_contribution');
    Route::post('store_contributions', 'ContributionController@storeContributions');

    Route::resource('reimbursement', 'ReimbursementController');
    Route::resource('aid_reimbursement', 'AidReimbursementController');

    //selectContributions
    Route::get('ret_fun/{ret_fun_id}/selectcontributions', 'ContributionController@selectContributions')->name('select_contributions');
    Route::post('ret_fun/savecontributions', 'ContributionController@saveContributions')->name('save_contributions');
    //selectContributionsQuotaAuxilio
    Route::get('quota_aid/{quota_aid_id}/selectcontributions', 'ContributionController@selectContributionsQuotaAid')->name('select_contributions_quota_aid');
    Route::post('quota_aid/savecontributions', 'ContributionController@saveContributionsQuotaAid')->name('save_contributions');

    //contributions certification
    //contributions certification 60, disponibilidad, item 0
    Route::get('ret_fun/{retirement_fund}/print/certification', 'RetirementFundCertificationController@printCertification')->name('ret_fun_print_certification');
    Route::get('ret_fun/{retirement_fund}/print/cer_availability', 'RetirementFundCertificationController@printCertificationAvailability')->name('ret_fun_print_certification_availability');
    Route::get('ret_fun/{retirement_fund}/print/cer_itemcero', 'RetirementFundCertificationController@printCertificationItem0')->name('ret_fun_print_certification_item0');
    Route::get('ret_fun/{retirement_fund}/print/security_certification', 'RetirementFundCertificationController@printCertificationSecurity')->name('ret_fun_print_security_certification');
    Route::get('ret_fun/{retirement_fund}/print/contributions_certification', 'RetirementFundCertificationController@printCertificationContributions')->name('ret_fun_print_contributions_certification');
    Route::get('ret_fun/{retirement_fund}/print/cer_availability_new', 'RetirementFundCertificationController@printCertificationAvailabilityNew')->name('ret_fun_print_certification_availability_new');
    Route::get('ret_fun/{retirement_fund}/print/cer_devolution', 'RetirementFundCertificationController@printCertificationDevolution')->name('ret_fun_print_certification_devolution');
    //AidContributions
    Route::resource('aid_contribution', 'AidContributionController');
    Route::get('affiliate/{affiliate}/aid_contribution/edit', 'AidContributionController@getAffiliateContributions')->name('edit_aid_contribution');
    Route::get('affiliate/{affiliate}/aid_contribution/direct', 'AidContributionController@directContributions')->name('direct_aid_contribution');
    Route::post('store_aid_contributions', 'AidContributionController@storeContributions');
    Route::get('affiliate/{affiliate}/aid_contribution', 'AidContributionController@show')->name('show_aid_contribution');
    Route::get('affiliate/{affiliate}/get_contribution_debt/{number}/{date}', 'AidContributionController@getContributionDebt')->name('get_contribution_debt');

    //Route::get('get_affiliate_aid_contributions/{affiliate}', 'AidContributionController@getAffiliateAidContributionsDatatables')->name('affiliate_aid_contributions');


    //Contributions
    Route::resource('contribution', 'ContributionController');
    Route::get('affiliate/{affiliate}/contribution/create', 'ContributionController@generateContribution')->name('create_contribution');
    Route::get('affiliate/{affiliate}/contribution', 'ContributionController@show')->name('show_contribution');
    Route::get('get_affiliate_contributions/{affiliate}', 'ContributionController@getAffiliateContributionsDatatables')->name('affiliate_contributions');
    Route::get('affiliate/{affiliate_id}/aid/contributions', 'AidContributionController@aidContributions');
    Route::get('get_aid_contributions/{affiliate}', 'AidContributionController@getAllContributionsAid')->name('affiliate_aid_contributions');
    Route::get('affiliate/{affiliate_id}/get_month_contributions/{date}', 'ContributionController@getMonthContributions')->name('get_month_contributions');
    Route::get('get_contribution_rate/{date}', 'ContributionController@getContributionRate')->name('get_contribution_rate');

    // Route::get('AidContribution', function(){
    // 	return view('aid_contribution');
    // });
    // Route::get('get_affiliate_contributions/{affiliate_id}', function (AffiliateContributionsDataTable $dataTable, $affiliate_id) {
    // 	return $dataTable->with('affiliate_id', $affiliate_id)
    // 					 ->render('contribution.show');
    // });
    // Route::get('get_affiliate_contributions/{affiliate}', 'ContributionController@getAffiliateContributions')->name('affiliate_contributions');

    Route::post('get-interest', 'ContributionController@getInterest');
    Route::post('get-interest-aid', 'AidContributionController@getInterest');
    Route::get('calculate_reimbursement/{affiliate}/{amount}/{month}', 'ReimbursementController@caculateContribution');
    Route::post('contribution_save', 'ContributionController@storeDirectContribution');
    Route::post('aid_contribution_save', 'AidContributionController@storeDirectContribution');
    Route::post('print_contributions_quote', 'RetirementFundCertificationController@printDirectContributionQuote');

    Route::get('print_contributions_quote', 'RetirementFundCertificationController@printDirectContributionQuote');
    Route::get('print_contributions_quote_aid', 'QuotaAidCertificationController@printDirectContributionQuoteAid');
    //Commitments
    Route::resource('commitment', 'ContributionCommitmentController');
    Route::resource('aid_commitment', 'AidCommitmentController');
    Route::get('calculate_aid_reimbursement/{affiliate}/{amount}/{month}', 'AidReimbursementController@caculateContribution');

    //Direct Contributions
    Route::resource('direct_contribution', 'DirectContributionController');
    Route::get('direct_contribution/{direct_contribution_id}/print/commitment_letter', 'DirectContributionCertificationController@printCommitmentLetter')->name('print_commitment_letter');
    Route::patch('/update_information_direct_contribution', 'DirectContributionController@updateInformation')->name('update_information_direct_contribution');

    Route::post('direct_contribution/{direct_contribution_id}/edit_requirements', 'DirectContributionController@editRequirements');
    Route::get('affiliate/{affiliate}/direct_contribution/create', 'DirectContributionController@create')->name('create_direct_contribution');
    Route::get('get_all_direct_contribution', 'DirectContributionController@getAllDirectContribution');
    Route::post('contribution_process/{contribution_process_id}/contribution_pay', 'ContributionProcessController@contributionPay');

    // Route::post('affiliate/{affiliate}/contribution_process/save_commitment', 'ContributionProcessController@saveCommitment')->name('save_commitment');

    // Contribution process
    Route::resource('contribution_process', 'ContributionProcessController');
    Route::post('contribution_process/aid_contribution_save', 'ContributionProcessController@aidContributionSave')->name('aid_contribution_save');
    Route::post('contribution_process/contribution_save', 'ContributionProcessController@contributionSave')->name('contribution_save');
    Route::get('contribution_process/{contribution_process_id}/correlative/{wf_state_id}', 'ContributionProcessController@getCorrelative')->name('contribution_process_get_correlative');
    Route::get('direct_contribution/{direct_contribution_id}/contribution_process/{contribution_process_id}/print/quotation', 'ContributionProcessCertificationController@printQuotation')->name('contribution_process_print_quotation');
    Route::get('direct_contribution/{direct_contribution_id}/contribution_process/{contribution_process_id}/print/voucher', 'ContributionProcessCertificationController@printVoucher')->name('contribution_process_print_voucher');

    //inbox
    Route::get('inbox', function () {
      return redirect('inbox/received');
    });
    Route::get('inbox/received', 'InboxController@received')->name('inbox_received');
    Route::get('inbox/edited', 'InboxController@edited')->name('inbox_edited');
    Route::post('inbox_send_forward', 'InboxController@sendForward')->name('inbox_send_forward');
    Route::post('inbox_send_backward', 'InboxController@sendBackward')->name('inbox_send_backward');
    Route::patch('inbox_validate_doc/{doc_id}', 'InboxController@validateDoc')->name('inbox_validate_doc');
    Route::patch('inbox_invalidate_doc/{doc_id}', 'InboxController@invalidateDoc')->name('inbox_validate_doc');

    Route::post('inbox_send_reception', 'InboxController@sendReception')->name('inbox_send_reception');

    //charges
    Route::get('affiliate/{affiliate}/voucher/create', 'VoucherController@generateVoucher')->name('create_voucher');
    Route::resource('voucher', 'VoucherController');
    Route::get('affiliate/{affiliate}/voucher/{voucher}/print', 'VoucherController@printVoucher')->name('print_voucher');
    //Route::post('affiliate/{affiliate}/voucher', 'VoucherController@storeVoucher')->name('store_voucher');

    Route::get('print/resolution_notification', function () {
      $data = [];
      return \PDF::loadView('ret_fun.print.resolution_notification', $data)
        ->setOption('encoding', 'utf-8')
        ->stream("resolutionNotification.pdf");
    })->name('resolution_notification');
    //dictamen legal routes
    Route::get('ret_fun/{retirement_fund}/dictamen_legal', 'RetirementFundController@dictamenLegal')->name('ret_fun_dictamen_legal');

    //helpers route
    Route::get('correlative', function () {
      $wf_state_id = 19;
      $model = RetFunCorrelative::where('wf_state_id', $wf_state_id)
        ->where('code', 'NOT LIKE', '%A')
        ->where(DB::raw("split_part(code, '/',2)::integer"), '2019')
        ->orderBy(DB::raw("split_part(code, '/',2)::integer"))
        ->orderBy(DB::raw("split_part(code, '/',1)::integer"))
        ->select('code')
        ->get()
        ->toArray();
      for ($i = 1; $i < sizeof($model); $i++) {
        $code = explode('/', $model[$i]['code']);
        $last_code = explode('/', $model[$i - 1]['code']);
        if ($last_code[0] + 1 != $code[0] && $last_code[0] != $code[0] && $last_code[1] == $code[1]) {
          return $last_code[0] . '/' . $last_code[1];
        }
      }
      return $code[0];
    });
    Route::get('legal_opinion', function (Request $request) {
      $ret_fun = RetirementFund::find(4);
      $affiliate = $ret_fun->affiliate;
      $args = array(
        'ret_fun' => $ret_fun,
        'affiliate' => $affiliate,
        'has_poder' => true,
        'poder_number' => '151/2017',
        'poder_date' => Util::getStringDate('2014-11-06'),
        'poder_full_name' => "uihsakdas",
        'poder_ci_ext' => "65284 UI",
        'file_code' => "15/2018",
        'file_date' => Util::getStringDate('2018-10-10'),
        'has_file' => false,
        'admin_fin_cite' => '5151/21212',
        'admin_fin_date' => Util::getStringDate('2018-1-10'),
        'has_admin_file' => true,
        'admin_fin_amount' => '5,152.58',
        'legal_code' => '125/505',
        'legal_date' => Util::getStringDate('2018-1-10'),
        'aportes_code' => '5121/1055',
        'aportes_date' => Util::getStringDate('2018-1-10'),
        'number_contributions' => RetFunProcedure::current()->number_contributions,
        'availability_code' => '215/5018',
        'availability_date' => Util::getStringDate('2018-1-10'),
        'availability_number_contributions' => 15,
        'qualification_code' => '5121/5018',
        'qualification_date' => Util::getStringDate('2018-1-10'),
        'qualification_years' => 34,
        'qualification_months' => 2,
        'qualification_amount' => '515.45',
        'reserva_date' => Util::getStringDate('2018-1-10'),
        'annual_yield' => RetFunProcedure::current()->annual_yield,
        'reserva_amount' => 84136.45,
      );
      return \PDF::loadView('ret_fun.legal_opinion.ret_fun_jubilacion', $args)
        ->setPaper('letter')
        ->setOption('encoding', 'utf-8')
        ->stream("dictamenLegal.pdf");


      foreach (RetFunTemplate::all() as $value) {
        $generated = \Blade::compileString($value->template);

        ob_start() and extract($args, EXTR_SKIP);
        try {
          eval('?>' . $generated);
        } catch (\Exception $e) {
          ob_get_clean();
          throw $e;
        }
        $content = ob_get_clean();

        $view = View::make('ret_fun.legal_opinion.header', ['title' => 'Arjun']);
        $header = $view->render();
        $view = View::make('ret_fun.legal_opinion.footer', ['title' => 'Arjun']);
        $footer = $view->render();
        $content = $header . ' ' . $content . ' ' . $footer;

        $pdf = \App::make('snappy.pdf.wrapper');
        $pdf->loadHTML($content);
        return $pdf->setPaper('letter')
          ->setOption('encoding', 'utf-8')
          ->stream($value->id . ".pdf");
      }
    });
    Route::get('print/pre-qualification', function () {
      $re = RetirementFund::where('wf_state_current_id', 23)->get();
      $filter = $re->filter(function ($value, $key) {
        return $value->tags->contains(1) && $value->tags->contains(4);
      });

      $size = sizeof($filter);

      $area = WorkflowState::find(23)->first_shortened;
      $user = Auth::user();
      $date = Util::getDateFormat(Carbon::now());

      $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
      $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
      $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
      $title = "Cálculo preliminar de fondo de retiro";


      $data = [
        'area' => $area,
        'user' => $user,
        'date' => $date,
        'filter' => $filter,
        'size' => $size,

        'title' => $title,
        'institution' => $institution,
        'direction' => $direction,
        'unit' => $unit,
      ];
      $pages[] = \View::make('print_global.report', $data)->render();
      $pdf = \App::make('snappy.pdf.wrapper');
      $pdf->loadHTML($pages);
      return $pdf->setOption('encoding', 'utf-8')
        ->setOption('margin-bottom', '15mm')
        ->setOrientation('landscape')
        ->setOption('footer-center', 'Pagina [page] de [toPage]')
        ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
        ->stream("pre-calificados.pdf");

      return view('print_global.report', $data);
      return $filter->all();
    })->name('print_pre_qualification');
    Route::get('print/send-daa', function () {


      $area = WorkflowState::find(26)->first_shortened;
      $user = Auth::user();
      $date = Util::getDateFormat(Carbon::now());
      $year = Carbon::now()->year;
      $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
      $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
      $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
      $title = "TRÁMITES BENEFICIOS ECONÓMICOS - FONDO DE RETIRO POLICIAL SOLIDARIO";
      $retirement_funds = RetirementFund::leftJoin('ret_fun_correlatives', 'retirement_funds.id', '=', 'ret_fun_correlatives.retirement_fund_id')
        ->where('retirement_funds.wf_state_current_id', 26)
        ->where('retirement_funds.inbox_state', true)
        ->where('ret_fun_correlatives.wf_state_id', 26)
        ->where('retirement_funds.code', 'not like', '%A')
        ->select('retirement_funds.id', 'ret_fun_correlatives.code')
        ->groupBy('retirement_funds.id', 'ret_fun_correlatives.code')
        ->orderBy(DB::raw("split_part(ret_fun_correlatives.code, '/',1)::integer"))
        ->pluck('ret_fun_correlatives.code', 'retirement_funds.id')->toArray();
      // dd($retirement_funds);
      // $retirement_funds = RetirementFund::whereIn('id', array_keys($retirement_funds))->get();


      //$retirement_funds = RetirementFund::whereIn('id',$retirement_funds)->get();

      // =======
      //$retirement_funds = RetirementFund::where('wf_state_current_id',26)->where('inbox_state', true)->get();
      // $retirement_funds_i = RetirementFund::where('wf_state_current_id', 26)->where('inbox_state', true)
      //     ->leftJoin('ret_fun_correlatives', 'retirement_funds.id', '=', 'ret_fun_correlatives.retirement_fund_id')
      //     ->where('ret_fun_correlatives.wf_state_id', 25)
      //     ->orderByDesc('ret_fun_correlatives.created_at')
      //     ->select('retirement_funds.id')
      //     ->get()
      //     ->pluck('id')
      //     ;
      $retirement_funds1 = [];
      // RetirementFund::whereIn('id', array_keys($retirement_funds))->orderBy('updated_at')->get();
      foreach (array_keys($retirement_funds) as $value) {
        array_push($retirement_funds1, RetirementFund::find($value));
      }

      // >>>>>>> origin/master
      $data = [
        'area' => $area,
        'user' => $user,
        'date' => $date,
        'retirement_funds' => $retirement_funds1,
        'year' => $year,

        'title' => $title,
        'institution' => $institution,
        'direction' => $direction,
        'unit' => $unit,
      ];
      $pages[] = \View::make('print_global.send_daa', $data)->render();
      $pdf = \App::make('snappy.pdf.wrapper');
      $pdf->loadHTML($pages);
      return $pdf->setOption('encoding', 'utf-8')
        ->setOption('margin-bottom', '15mm')
        ->setOrientation('landscape')
        ->setOption('footer-center', 'Pagina [page] de [toPage]')
        ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
        ->stream("pre-calificados.pdf");

      return view('print_global.report', $data);
      return $filter->all();
    })->name('print_send_daa');

    Route::get('print/be', function () {
      $area = WorkflowState::find(47)->first_shortened;
      $user = Auth::user();
      $date = Util::getDateFormat(Carbon::now());
      $year = Carbon::now()->year;
      $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
      $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
      $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
      $title = "TRÁMITES BENEFICIOS ECONÓMICOS - FONDO DE RETIRO POLICIAL SOLIDARIO";
      $retirement_funds = RetirementFund::leftJoin('ret_fun_correlatives', 'retirement_funds.id', '=', 'ret_fun_correlatives.retirement_fund_id')
        ->where('retirement_funds.wf_state_current_id', 47)
        ->where('retirement_funds.inbox_state', true)
        ->where('retirement_funds.code', 'not like', '%A')
        ->select('retirement_funds.id', 'ret_fun_correlatives.code')
        ->groupBy('retirement_funds.id', 'ret_fun_correlatives.code')
        ->orderBy(DB::raw("split_part(ret_fun_correlatives.code, '/',1)::integer"))
        ->pluck('ret_fun_correlatives.code', 'retirement_funds.id')->toArray();
      $retirement_funds1 = [];
      foreach (array_keys($retirement_funds) as $value) {
        array_push($retirement_funds1, RetirementFund::find($value));
      }
      $data = [
        'area' => $area,
        'user' => $user,
        'date' => $date,
        'retirement_funds' => $retirement_funds1,
        'year' => $year,
        'title' => $title,
        'institution' => $institution,
        'direction' => $direction,
        'unit' => $unit,
      ];
      $pages[] = \View::make('print_global.send_daa', $data)->render();
      $pdf = \App::make('snappy.pdf.wrapper');
      $pdf->loadHTML($pages);
      return $pdf->setOption('encoding', 'utf-8')
        ->setOption('margin-bottom', '15mm')
        ->setOrientation('landscape')
        ->setOption('footer-center', 'Pagina [page] de [toPage]')
        ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
        ->stream("pre-calificados.pdf");
    })->name('print_be');

    Route::get('quota_aid/print/send-daa', function () {
      $area = WorkflowState::find(40)->first_shortened;
      $user = Auth::user();
      $date = Util::getDateFormat(Carbon::now());
      $year = Carbon::now()->year;
      $institution = 'MUTUAL DE SERVICIOS AL POLICÍA "MUSERPOL"';
      $direction = "DIRECCIÓN DE BENEFICIOS ECONÓMICOS";
      $unit = "UNIDAD DE OTORGACIÓN DE FONDO DE RETIRO POLICIAL, CUOTA MORTUORIA Y AUXILIO MORTUORIO";
      $title = "TRÁMITES BENEFICIOS ECONÓMICOS - AUXILIO MORTUORIO";
      $quota_aids = QuotaAidMortuary::leftJoin('quota_aid_correlatives', 'quota_aid_mortuaries.id', '=', 'quota_aid_correlatives.quota_aid_mortuary_id')
        ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
        ->leftJoin('procedure_types', 'procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
        ->where('procedure_types.id', '=', 4)
        ->where('quota_aid_mortuaries.wf_state_current_id', 40)
        ->where('quota_aid_mortuaries.inbox_state', true)
        ->where('quota_aid_correlatives.wf_state_id', 40)
        ->where('quota_aid_mortuaries.code', 'not like', '%A')
        ->select('quota_aid_mortuaries.id', 'quota_aid_correlatives.code')
        ->groupBy('quota_aid_mortuaries.id', 'quota_aid_correlatives.code')
        ->orderBy(DB::raw("split_part(quota_aid_correlatives.code, '/',1)::integer"))
        ->pluck('quota_aid_correlatives.code', 'quota_aid_mortuaries.id')->toArray();

      $quota_aids1 = [];

      foreach (array_keys($quota_aids) as $value) {
        array_push($quota_aids1, QuotaAidMortuary::find($value));
      }

      $data = [
        'area' => $area,
        'user' => $user,
        'date' => $date,
        'docs' => $quota_aids1,
        'year' => $year,

        'title' => $title,
        'institution' => $institution,
        'direction' => $direction,
        'unit' => $unit,
      ];
      if (count($quota_aids1)) {
        $pages[] = \View::make('print_global.send_daa_quota_aid', $data)->render();
      }
      $title = "TRÁMITES BENEFICIOS ECONÓMICOS - CUOTA MORTUORIA";
      $quota_aids = QuotaAidMortuary::leftJoin('quota_aid_correlatives', 'quota_aid_mortuaries.id', '=', 'quota_aid_correlatives.quota_aid_mortuary_id')
        ->leftJoin('procedure_modalities', 'quota_aid_mortuaries.procedure_modality_id', '=', 'procedure_modalities.id')
        ->leftJoin('procedure_types', 'procedure_modalities.procedure_type_id', '=', 'procedure_types.id')
        ->where('procedure_types.id', '=', 3)
        ->where('quota_aid_mortuaries.wf_state_current_id', 40)
        ->where('quota_aid_mortuaries.inbox_state', true)
        ->where('quota_aid_correlatives.wf_state_id', 40)
        ->where('quota_aid_mortuaries.code', 'not like', '%A')
        ->select('quota_aid_mortuaries.id', 'quota_aid_correlatives.code')
        ->groupBy('quota_aid_mortuaries.id', 'quota_aid_correlatives.code')
        ->orderBy(DB::raw("split_part(quota_aid_correlatives.code, '/',1)::integer"))
        ->pluck('quota_aid_correlatives.code', 'quota_aid_mortuaries.id')->toArray();

      $quota_aids1 = [];

      foreach (array_keys($quota_aids) as $value) {
        array_push($quota_aids1, QuotaAidMortuary::find($value));
      }

      $data = [
        'area' => $area,
        'user' => $user,
        'date' => $date,
        'docs' => $quota_aids1,
        'year' => $year,

        'title' => $title,
        'institution' => $institution,
        'direction' => $direction,
        'unit' => $unit,
      ];
      if (count($quota_aids1)) {
        $pages[] = \View::make('print_global.send_daa_quota_aid', $data)->render();
      }
      $pdf = \App::make('snappy.pdf.wrapper');
      $pdf->loadHTML($pages);
      return $pdf->setOption('encoding', 'utf-8')
        ->setOption('margin-bottom', '15mm')
        ->setOrientation('landscape')
        // ->setOption('footer-center', 'Pagina [page] de [toPage]')
        ->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - '.Carbon::now()->year)
        ->stream("pre-calificados.pdf");

      return view('print_global.report', $data);
      return $filter->all();
    })->name('print_send_daa_quota_aid');
    Route::get('test', function () {
      return Util::getNextAreaCode(102);
    });
    Route::get('/treasury/select_report', 'TreasuryController@selectReport');
    Route::get('/treasury/report', 'TreasuryController@report');

    // Economic Complement
    Route::post('economic_complement_store', 'EconomicComplementController@store')->name('economic_complement_store');
    Route::patch('economic_complement_update_information', 'EconomicComplementController@updateInformation');
    Route::patch('/update_affiliate_police_eco_com', 'EconomicComplementController@updateAffiliatePoliceEcoCom');

    Route::get('eco_com/{eco_com_id}', 'EconomicComplementController@show')->name('eco_com_show');
    Route::get('eco_com', 'EconomicComplementController@index')->name('eco_com');
    Route::get('get_all_eco_com', 'EconomicComplementController@getAllEcoCom');
    Route::post('eco_com/{eco_com}/edit_requirements', 'EconomicComplementController@editRequirements')->name('eco_com_edit_requirements');
    Route::get('economic_complement_first_step', 'EconomicComplementController@firstStep')->name('economic_complement_first_step');
    Route::get('get_eco_com_procedures_active', 'EcoComProcedureController@getProcedureActives')->name('get_eco_com_procedures_active');
    Route::get('get_eco_com_reception_type', 'EconomicComplementController@getReceptionType');
    Route::get('get_eco_com_type_beneficiary', 'EconomicComplementController@getTypeBeneficiary');
    Route::get('get_eco_com_rents_first_semester', 'EconomicComplementController@getRentsFirstSemester');
    Route::delete('eco_com/{eco_com_id}', 'EconomicComplementController@destroy');
    Route::patch('eco_com_update_rents', 'EconomicComplementController@updateRents');
    Route::patch('eco_com_change_rent_type', 'EconomicComplementController@changeRentType');
    Route::get('get_eco_com/{id}', 'EconomicComplementController@getEcoCom');
    Route::patch('eco_com_save_amortization', 'EconomicComplementController@saveAmortization');
    Route::patch('eco_com_save_deposito', 'EconomicComplementController@saveDeposito');
    Route::get('eco_com_record/{id}', 'EconomicComplementController@getRecord');
    Route::post('eco_com_import_rents', 'EcoComImportExportController@importSenasir');
    Route::post('eco_com_import_rents_aps', 'EcoComImportExportController@importAPS');
    Route::post('eco_com_update_paid_bank', 'EcoComImportExportController@updatePaidBank');
    Route::post('eco_com_import_pago_futuro', 'EcoComImportExportController@importPagoFuturo');
    Route::post('eco_com_import_planilla', 'EconomicComplementController@importPlanilla');
    Route::post('eco_com_cambiar_estado', 'EconomicComplementController@cambioEstado');
    Route::delete('eco_com_cambiar_estado_individual/{eco_com_id}', 'EconomicComplementController@cambioEstadoIndividual');
    Route::get('eco_com_cambiar_habilitado/{eco_com_id}', 'EconomicComplementController@cambioEstadoObservados');
    Route::delete('delete_discount_type_aid', 'EconomicComplementController@delete_discount_type_aid');
    
    Route::get('/affiliate/{affiliate_id}/eco_com/create/{eco_com_procedure_id}', 'EconomicComplementController@create');

    Route::get('eco_com/{eco_com_id}/print/paid_cetificate', 'EconomicComplementController@paidCertificate');
    Route::patch('eco_com_recalificacion', 'EconomicComplementController@recalificacion');

    //fixed
    Route::patch('/eco_com_fixed_pensions/{id}', 'EcoComFixedPensionController@updateFixed');

    // Eco com Beneficiary
    Route::get('get_eco_com_beneficiary/{eco_com_id}', 'EcoComBeneficiaryController@getEcoComBeneficiary');
    Route::patch('/eco_com_beneficiary', 'EcoComBeneficiaryController@update');

    // Eco com legal guardian
    Route::get('get_eco_com_legal_guardian/{eco_com_id}', 'EcoComLegalGuardianController@getEcoComLegalGuardian');
    Route::patch('/eco_com_legal_guardian', 'EcoComLegalGuardianController@update');
    Route::delete('/eco_com_legal_guardian', 'EcoComLegalGuardianController@delete');

    // eco com Certification
    Route::get('eco_com/{eco_com_id}/print/reception', 'EcoComCertificationController@printReception')->name('eco_com_print_reception');
    Route::get('eco_com/{eco_com_id}/print/sworn_declaration', 'EcoComCertificationController@printSwornDeclaration')->name('eco_com_print_sworn_declaration');
    Route::get('eco_com/{eco_com_id}/print/qualification', 'EcoComCertificationController@printQualification')->name('eco_com_print_qualification');
    Route::post('eco_com/{eco_com_id}/save_certification_note', 'EcoComCertificationController@saveCertificationNote')->name('save_certification_note');
    Route::get('eco_com/print/certification_all_eco_coms/{affiliate_id}', 'EcoComCertificationController@certificationAllEcoComs')->name('eco_com_print_certification_all_eco_coms');
    Route::get('eco_com/{eco_com_id}/print/lagging', 'EcoComCertificationController@printLagging')->name('eco_com_print_lagging');


    // eco com qualification parameters
    Route::get('eco_com_qualification_parameters', 'EconomicComplementController@qualificationParameters')->name('eco_com_qualification_parameters');
    Route::post('eco_com_automatic_qualification', 'EconomicComplementController@automatiQualification');
    // eco com reports
    Route::get('eco_com_report', 'EcoComReportController@index')->name('eco_com_report');
    Route::post('eco_com_report_excel', 'EcoComReportController@generate');
    Route::post('eco_com_estado', 'EconomicComplementController@cambiarEstado');
    Route::get('update_overpayments', 'EconomicComplementController@update_overpayments');// actualizacion de reposicion de fondos

    // base wage
    Route::resource('base_wage', 'BaseWageController');
    Route::get('get_first_level_base_wage', 'BaseWageController@FirstLevelData')->name('get_first_level_base_wage');
    Route::get('get_second_level_base_wage', 'BaseWageController@SecondLevelData')->name('get_second_level_base_wage');
    Route::get('get_third_level_base_wage', 'BaseWageController@ThirdLevelData')->name('get_third_level_base_wage');
    Route::get('get_fourth_level_base_wage', 'BaseWageController@FourthLevelData')->name('get_fourth_level_base_wage');
    Route::post('base_wage_create', 'BaseWageController@base_wage_create');

    // Complementary Factor
    Route::resource('complementary_factor', 'ComplementaryFactorController');
    Route::get('get_complementary_factor_old_age', 'ComplementaryFactorController@old_ageData')->name('get_complementary_factor_old_age');
    Route::get('get_complementary_factor_widowhood', 'ComplementaryFactorController@widowhoodData')->name('get_complementary_factor_widowhood');

    // average eco com
    Route::get('averages', 'EconomicComplementController@averages')->name('averages');
    Route::get('get_averages', 'EconomicComplementController@getAverageData')->name('get_averages');
    Route::get('print_average', 'EconomicComplementController@printAverage')->name('print_average');
    // Route::get('export_average/{year}/{semester}', 'EconomicComplementReportController@export_average')->name('export_average');

    // observations
    Route::get('eco_com_get_observations/{eco_com_id}', 'EcoComObservationController@getObservations');
    Route::get('eco_com_get_delete_observations/{eco_com_id}', 'EcoComObservationController@getDeleteObservations');
    Route::post('eco_com_observation_create', 'EcoComObservationController@create');
    Route::patch('eco_com_observation_update', 'EcoComObservationController@update');
    Route::delete('eco_com_observation_delete', 'EcoComObservationController@delete');

    // note eco_com
    Route::post('eco_com_note_create', 'EcoComNoteController@create');
    Route::patch('eco_com_note_update', 'EcoComNoteController@update');
    Route::delete('eco_com_note_delete', 'EcoComNoteController@delete');

    // eco com procedures
    Route::get('eco_com_get_procedures', 'EcoComProcedureController@getProcedures');
    Route::post('eco_com_procedure_create', 'EcoComProcedureController@create');
    Route::patch('eco_com_procedure_update', 'EcoComProcedureController@update');
    Route::delete('eco_com_procedure_delete', 'EcoComProcedureController@delete');

    // Affiliate observations
    Route::get('affiliate_get_observations/{affiliate_id}', 'AffiliateObservationController@getObservations');
    Route::get('affiliate_get_delete_observations/{affiliate_id}', 'AffiliateObservationController@getDeleteObservations');
    Route::post('affiliate_observation_create', 'AffiliateObservationController@create');
    Route::patch('affiliate_observation_update', 'AffiliateObservationController@update');
    Route::delete('affiliate_observation_delete', 'AffiliateObservationController@delete');
    Route::get('affiliate_get_devolutions/{affiliate_id}', 'AffiliateDevolutionController@getDevolutions');
    Route::post('affiliate_devolution_payment_commitment', 'AffiliateDevolutionController@store');
    Route::get('affiliate/{affiliate_id}/print/certification_devolutions', 'AffiliateDevolutionController@printCertificationDevolutions');
    Route::post('affiliate/{affiliate_id}/print/devolution_payment_commitment', 'AffiliateDevolutionController@printDevolutionPaymentCommitment');

    // Affiliate Devoluciones
    Route::patch('affiliate_devolucion_total_deuda', 'AffiliateDevolutionController@actualizarTotalDeuda');
    Route::patch('affiliate_devolucion_total_deuda_pendiente', 'AffiliateDevolutionController@actualizarTotalDeudaPendiente');
    
    // affiliate records
    Route::get('affiliate_record/{id}', 'AffiliateController@getRecord');
    Route::get('affiliate_notes/{id}', 'AffiliateController@getNote');
    Route::get('affiliate_record_print/{affiliate_id}', 'AffiliateReportController@printRecordAffiliate')->name('affiliate_print_record');
    // Spouse records
    Route::get('spouse_record/{id}', 'SpouseController@getRecord');
    
    // affiliate notes
    Route::post('affiliate_note_create', 'AffiliateNoteController@create');
    Route::patch('affiliate_note_update', 'AffiliateNoteController@update');
    Route::delete('affiliate_note_delete', 'AffiliateNoteController@delete');

    //eco com dashboard
    Route::get('chart_eco_com_modalities_general', 'EcoComDashboardController@modalitiesGeneral');
    Route::get('chart_eco_com_modalities', 'EcoComDashboardController@modalities');
    Route::get('chart_eco_com_cities', 'EcoComDashboardController@cities');
    Route::get('chart_eco_com_reception_type', 'EcoComDashboardController@receptionType');
    Route::get('chart_eco_com_pension_entity', 'EcoComDashboardController@pensionEntity');
    Route::get('chart_eco_com_states', 'EcoComDashboardController@states');
    //Route::get('chart_eco_com_state_types', 'EcoComDashboardController@stateTypes');
    Route::get('chart_eco_com_creation', 'EcoComDashboardController@creationEcoCom');
    Route::get('chart_eco_com_wf_states', 'EcoComDashboardController@wfStates');
    Route::get('chart_eco_com_last_eco_com', 'EcoComDashboardController@lastEcoCom');
    Route::get('chart_eco_com_total_amount_last_eco_com', 'EcoComDashboardController@totalAmountLastEcoCom');
    Route::get('chart_eco_com_reception_months', 'EcoComDashboardController@receptionMonths');

    // affiliate submitted documents
    Route::get('get_procedure_requirements', 'AffiliateSubmittedDocumentsController@getRequirements');

    // Cargar promedios
    Route::post('eco_com_load_promedio', 'EconomicComplementController@loadPromedio');
    // eco_com_regulations
    // Cargar promedio segun la regulación actual
    Route::post('eco_com_load_average_with_regulation','EconomicComplementController@loadAverageWithRegulation');
    Route::get('eco_com_procedures_regulation','EconomicComplementController@getProceduresRegulation');

    // certificado de revision
    Route::get('review_show/{eco_com_id}', 'EcoComReviewProcedureController@show')->name('show');
    Route::post('eco_com/review_edit', 'EconomicComplementController@editReviewProcedures')->name('eco_com_review_edit');
    Route::get('eco_com/{eco_com_id}/print/revision_certificate', 'EcoComCertificationController@printRevisionCertificate')->name('eco_com_print_revision_certificate');
    Route::post('eco_com_replicate', 'EconomicComplementReplicationController@prepareReplication')->name('eco_com_replicate');
    Route::post('eco_com_replicate/execute', 'EconomicComplementReplicationController@executeReplication')->name('eco_com_replicate.execute');
    Route::get('eco_com_replicate/history', 'EconomicComplementReplicationController@getReplicationHistory')->name('eco_com_replicate.history');
    Route::get('eco_com_replicate/status', 'EconomicComplementReplicationController@canCreateReplication')->name('eco_com_replicate.status');
  });
});
