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

			//ROUTES TO E SYSTEM PARAMENTERS
		Route::get('ret_fun_settings', 'HomeController@retFunSettings');
		Route::resource('ret_fun_procedure', 'RetFunProcedureController');

		Route::resource('affiliate', 'AffiliateController');

		Route::patch('/update_affiliate/{affiliate}', 'AffiliateController@update')->name('update_affiliate');
		Route::patch('/update_affiliate_police/{affiliate}', 'AffiliateController@update_affiliate_police')->name('update_affiliate_police');

		Route::patch('/update_beneficiaries/{retirement_fund}', 'RetirementFundController@updateBeneficiaries')->name('update_beneficiaries');
		Route::patch('/update_beneficiary_testimony_ret_fun/{retirement_fund}', 'RetirementFundController@updateBeneficiaryTestimony')->name('update_beneficiary_testimony');
		Route::get('/ret_fun_beneficiaries_testimonies/{ret_fun_id}', 'RetirementFundController@getTestimonies')->name('ret_fun_beneficiaries_testimonies');

		//SpouseControler
		Route::patch('/update_spouse/{affiliate_id}', 'SpouseController@update')->name('update_spouse');

		Route::get('get_all_affiliates', 'AffiliateController@getAllAffiliates');

		//Scanned Documents
		Route::resource('scanned_documents', 'ScannedDocumentController');
		Route::get('document_scanned/{affiliate_id}', 'ScannedDocumentController@create_document')->name('document_scanned');	


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
		Route::post('ret_fun/{ret_fun_id}/edit_requirements', 'RetirementFundController@editRequirements')->name('edit_requirements');
		Route::get('ret_fun/{ret_fun_id}/correlative/{wf_state_id}', 'RetirementFundController@getCorrelative')->name('ret_fun_get_correlative');
		Route::get('ret_fun/{ret_fun_id}/info', 'RetirementFundController@info')->name('ret_fun_info');

		//Retirement Fund Certification
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
		Route::patch('quota_aid/{quota_aid_id}/save_total', 'QuotaAidMortuaryController@saveTotal')->name('quota_aid_save_total');
		Route::patch('quota_aid/{quota_aid_id}/save_percentages', 'QuotaAidMortuaryController@savePercentages')->name('quota_aid_save_percentages');

		Route::get('affiliate/{affiliate}/ret_fun/create', 'RetirementFundController@generateProcedure')->middleware('affiliate_has_ret_fun')->name('create_ret_fun');
		Route::post('ret_fun/{retirement_fund}/legal_review/create', 'RetirementFundController@storeLegalReview')->name('store_ret_fun_legal_review_create');

		Route::patch('/update_information_rf', 'RetirementFundController@updateInformation')->name('update_information_rf');
		// tags
		Route::resource('/tag', "TagController");
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

		//QuotaAidMortuory
		Route::get('affiliate/{affiliate}/quota_aid/create', 'QuotaAidMortuaryController@generateProcedure')->name('create_quota_aid');
		Route::get('get_all_quota_aid', 'QuotaAidMortuaryController@getAllQuotaAid');
		Route::resource('quota_aid', 'QuotaAidMortuaryController');
		Route::post('quota_aid/{quota_aid_id}/edit_requirements', 'QuotaAidMortuaryController@editRequirements')->name('edit_quota_aid_requirements');

		Route::resource('affiliate_folder', 'AffiliateFolderController');
		Route::post('editFolder', 'AffiliateFolderController@editFolder')->name('editFolder');
		Route::post('deleteFolder', 'AffiliateFolderController@destroy')->name('deleteFolder');
		Route::post('updateFileCode', 'AffiliateFolderController@updateFileCode')->name('updateFileCode');

			//searcherController
		Route::get('search/{ci}', 'SearcherController@search');
		Route::get('search_ajax', 'SearcherController@searchAjax');

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

			//contributions certification
			//contributions certification 60, disponibilidad, item 0
		Route::get('ret_fun/{retirement_fund}/print/certification', 'RetirementFundCertificationController@printCertification')->name('ret_fun_print_certification');
		Route::get('ret_fun/{retirement_fund}/print/cer_availability', 'RetirementFundCertificationController@printCertificationAvailability')->name('ret_fun_print_certification_availability');
		Route::get('ret_fun/{retirement_fund}/print/cer_itemcero', 'RetirementFundCertificationController@printCertificationItem0')->name('ret_fun_print_certification_item0');
		Route::get('ret_fun/{retirement_fund}/print/security_certification', 'RetirementFundCertificationController@printCertificationSecurity')->name('ret_fun_print_security_certification');
		Route::get('ret_fun/{retirement_fund}/print/contributions_certification', 'RetirementFundCertificationController@printCertificationContributions')->name('ret_fun_print_contributions_certification');		
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


		Route::get('print/resolution_notification', function ()
		{
			$data = [];
			return \PDF::loadView('ret_fun.print.resolution_notification', $data)
				->setOption('encoding', 'utf-8')
				->stream("resolutionNotification.pdf");
		})->name('resolution_notification');
			//dictamen legal routes
		Route::get('ret_fun/{retirement_fund}/dictamen_legal', 'RetirementFundController@dictamenLegal')->name('ret_fun_dictamen_legal');		

			//helpers route
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
								}
								catch (\Exception $e) {
										ob_get_clean();
										throw $e;
								}
								$content = ob_get_clean();

								$view = View::make('ret_fun.legal_opinion.header', ['title' => 'Arjun']);
								$header = $view->render();
								$view = View::make('ret_fun.legal_opinion.footer', ['title' => 'Arjun']);
								$footer = $view->render();
								$content = $header. ' '. $content .' '.$footer;

								$pdf = \App::make('snappy.pdf.wrapper');
								$pdf->loadHTML($content);
								return $pdf->setPaper('letter')
										->setOption('encoding', 'utf-8')
										->stream($value->id.".pdf");
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
				->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
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

// <<<<<<< HEAD
			$retirement_funds = RetirementFund::leftJoin('ret_fun_correlatives', 'retirement_funds.id','=','ret_fun_correlatives.retirement_fund_id')
			->where('retirement_funds.wf_state_current_id',26)
			->where('retirement_funds.inbox_state', true)
			->where('ret_fun_correlatives.wf_state_id', 26)
			->where('retirement_funds.code', 'not like', '%A')
			->select('retirement_funds.id','ret_fun_correlatives.code')
			->groupBy('retirement_funds.id', 'ret_fun_correlatives.code')
			->orderBy(DB::raw("split_part(ret_fun_correlatives.code, '/',1)::integer"))
			->pluck('ret_fun_correlatives.code','retirement_funds.id' )->toArray()
			;
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
				->setOption('footer-left', 'PLATAFORMA VIRTUAL DE LA MUSERPOL - 2018')
				->stream("pre-calificados.pdf");

			return view('print_global.report', $data);
			return $filter->all();
		})->name('print_send_daa');

		Route::get('test', function ()
		{
			return Util::getNextAreaCode(102);

		});
		Route::get('get_next_area_code_ret_fun/{ret_fun_id}', function ($retirement_fund_id) {
			return Util::getNextAreaCode($retirement_fund_id, false);
		});
		Route::get('get_next_area_code_quota_aid/{quota_aid_id}', function ($quota_aid_id) {
			return Util::getNextAreaCodeQuotaAid($quota_aid_id, false);
		});
	});
});


