<?php

namespace Muserpol\Helpers;
use DateTime;
use Session;
use Auth;
use DB;
use User;
use Carbon\Carbon;
use Log;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Models\Workflow\WorkflowState;
use Muserpol\Models\Role;
use Muserpol\Models\DiscountType;
use Muserpol\Models\RetirementFund\RetirementFund;
use Muserpol\Models\Affiliate;
use Muserpol\Models\Spouse;
use Muserpol\QuotaAidCorrelative;
class Util
{
    //cambia el formato de la fecha a cadena
    //input $string=YYYY/mm/dd
    public static function getStringDate($string = "1800/01/01", $month_year=false){        
        setlocale(LC_TIME, 'es_ES.utf8');        
        $date = DateTime::createFromFormat("Y-m-d", $string);
        if($date){
            if($month_year)
            return strftime("%B/%Y",$date->getTimestamp());
            else
            return strftime("%d de %B de %Y",$date->getTimestamp());
        }
        else 
            return "sin fecha";
    }

    public static function formatMoney($value, $prefix = false)
    {
        if ($value) {
            $value = number_format($value, 2, '.', ',');
            if ($prefix) {
                return 'Bs'.$value;
            }
            return $value;
        }
        return null;
    }
    public static function parseMoney($value)
    {
        $value = str_replace("Bs", "", $value);
        $value = str_replace(",", "", $value);
        return floatval(self::removeSpaces($value));
    }
    public static function parseMonthYearDate($value)
    {
        if (self::verifyMonthYearDate($value) ) {
            return Carbon::createFromFormat('d/m/Y', '01/'.$value)->toDateString();
        }
        return 'invalid Month year';
    }
    public static function parseBarDate($value)
    {
        if (self::verifyBarDate($value) ) {
            return Carbon::createFromFormat('d/m/Y', $value)->toDateString();
        }
        return 'invalid Month year';
    }
    public static function verifyBarDate($value)
    {
        $re = $re = '/^\d{1,2}\/\d{1,2}\/\d{4}$/m';
        preg_match_all($re, $value, $matches, PREG_SET_ORDER, 0);
        return (sizeOf($matches) > 0);
    }
    public static function verifyMonthYearDate($value)
    {
        $re = $re = '/^\d{1,2}\/\d{4}$/m';
        preg_match_all($re, $value, $matches, PREG_SET_ORDER, 0);
        return (sizeOf($matches) > 0);
    }
    public static function formatMonthYear($date)
    {
        setlocale(LC_TIME, 'es_ES.utf8');
        if ($date) {
            if (self::verifyMonthYearDate($date)) {
                $date = Carbon::createFromFormat('d/m/Y', '01/' . $date)->toDateString();
            }
            return Carbon::parse($date)->formatLocalized('%b. %Y');
        }
        return null;
    }
    public static function ucw($string)
	{
		if ($string) {
			return ucwords(mb_strtolower($string,'UTF-8'));
		}
    }
    public static function removeSpaces($text)
    {
        $re = '/\s+/';
        $subst = ' ';
        $result = preg_replace($re, $subst, $text);
        return $result ? trim($result) : null;
    }
    public static function fullName($object, $style="uppercase")
    {
        $name = null;
        switch ($style) {
            case 'uppercase':
                $name = mb_strtoupper($object->first_name ?? '') . ' ' . mb_strtoupper($object->second_name ?? '') . ' ' . mb_strtoupper($object->last_name ?? '') . ' ' . mb_strtoupper($object->mothers_last_name ?? '') . ' ' . mb_strtoupper($object->surname_husband ?? '');
                break;
            case 'lowercase':
                $name = mb_strtolower($object->first_name ?? '') . ' ' . mb_strtolower($object->second_name ?? '') . ' ' . mb_strtolower($object->last_name ?? '') . ' ' . mb_strtolower($object->mothers_last_name ?? '') . ' ' . mb_strtolower($object->surname_husband ?? '');
                break;
            case 'capitalize':
                $name = ucfirst(mb_strtolower($object->first_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->second_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->last_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->mothers_last_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->surname_husband ?? ''));
                break;
        }
        $name = self::removeSpaces($name);
        return $name;
    }

    public static function getPDFName($title,$affi){
        $date =  Util::getStringDate(date('Y-m-d'));
        return ($title." - ".$affi->fullName()." - ".$date.".pdf");
    }

    public static function getNextCode($actual, $default = '675' ){
        $year =  date('Y');
        if($actual == "")
            return $default."/".$year;  
        $data = explode('/', $actual);
        if(!isset($data[1]))
            return $default."/".$year;                
        return ($year!=$data[1]?"1":($data[0]+1))."/".$year;
    }
    public static function getNextAreaCode($retirement_fund_id){
        
        $wf_state = WorkflowState::where('module_id',3)->where('role_id', Session::get('rol_id'))->first();        
        $reprint = RetFunCorrelative::where('retirement_fund_id',$retirement_fund_id)->where('wf_state_id',$wf_state->id)->first();
        if(isset($reprint->id))
            return $reprint;
        $year =  date('Y');
        $role = Role::find($wf_state->role_id);
        if($role->correlative == ""){
            $role->correlative = "1/".$year;
        }
        else{
            $data = explode('/', $role->correlative);
            if(!isset($data[1]))
                $role->correlative = "1/".$year;
            else
                $role->correlative = ($year!=$data[1]?"1":($data[0]+1))."/".$year;
        }
        $role->save();

        //Correlative 
        $correlative = new RetFunCorrelative();
        $correlative->wf_state_id = $wf_state->id;
        $correlative->retirement_fund_id = $retirement_fund_id;
        $correlative->code = $role->correlative;
        $correlative->date = Carbon::now();
        $correlative->user_id = self::getAuthUser()->id;
        $correlative->save();

        return $correlative;
    }
    public static function getNextAreaCodeQuotaAid($quota_aid_mortuary_id){
        $wf_state = WorkflowState::where('module_id',4)->where('role_id', Session::get('rol_id'))->first();        
        $reprint = QuotaAidCorrelative::where('quota_aid_mortuary_id',$quota_aid_mortuary_id)->where('wf_state_id',$wf_state->id)->first();
        if(isset($reprint->id))
            return $reprint;
        $year =  date('Y');
        $role = Role::find($wf_state->role_id);
        if($role->correlative == ""){
            $role->correlative = "1/".$year;
        }
        else{
            $data = explode('/', $role->correlative);
            if(!isset($data[1]))
                $role->correlative = "1/".$year;
            else
                $role->correlative = ($year!=$data[1]?"1":($data[0]+1))."/".$year;
        }
        $role->save();

        //Correlative 
        $correlative = new QuotaAidCorrelative();
        $correlative->wf_state_id = $wf_state->id;
        $correlative->quota_aid_mortuary_id = $quota_aid_mortuary_id;
        $correlative->code = $role->correlative;
        $correlative->date = Carbon::now();
        $correlative->user_id = self::getAuthUser()->id;
        $correlative->save();

        return $correlative;
    }
    private static $UNIDADES = [
        '',
        'UN ',
        'DOS ',
        'TRES ',
        'CUATRO ',
        'CINCO ',
        'SEIS ',
        'SIETE ',
        'OCHO ',
        'NUEVE ',
        'DIEZ ',
        'ONCE ',
        'DOCE ',
        'TRECE ',
        'CATORCE ',
        'QUINCE ',
        'DIECISEIS ',
        'DIECISIETE ',
        'DIECIOCHO ',
        'DIECINUEVE ',
        'VEINTE '
    ];
    private static $DECENAS = [
        'VEINTI',
        'TREINTA ',
        'CUARENTA ',
        'CINCUENTA ',
        'SESENTA ',
        'SETENTA ',
        'OCHENTA ',
        'NOVENTA ',
        'CIEN '
    ];
    private static $CENTENAS = [
        'CIENTO ',
        'DOSCIENTOS ',
        'TRESCIENTOS ',
        'CUATROCIENTOS ',
        'QUINIENTOS ',
        'SEISCIENTOS ',
        'SETECIENTOS ',
        'OCHOCIENTOS ',
        'NOVECIENTOS '
    ];
    public static function convertir($number, $moneda = '', $centimos = '')
    {
        $converted = '';
        $decimales = '';
        if (($number < 0) || ($number > 999999999)) {
            return 'No es posible convertir el numero a letras';
        }
        $div_decimales = explode('.',$number);
        if(count($div_decimales) > 1){
            $number = $div_decimales[0];
            $decNumberStr = (string) $div_decimales[1];
            if(strlen($decNumberStr) == 2){
                $decNumberStrFill = str_pad($decNumberStr, 9, '0', STR_PAD_LEFT);
                $decCientos = substr($decNumberStrFill, 6);
                $decimales = self::convertGroup($decCientos);
            }
        }
        $numberStr = (string) $number;
        $numberStrFill = str_pad($numberStr, 9, '0', STR_PAD_LEFT);
        $millones = substr($numberStrFill, 0, 3);
        $miles = substr($numberStrFill, 3, 3);
        $cientos = substr($numberStrFill, 6);
        if (intval($millones) > 0) {
            if ($millones == '001') {
                $converted .= 'UN MILLON ';
            } else if (intval($millones) > 0) {
                $converted .= sprintf('%sMILLONES ', self::convertGroup($millones));
            }
        }
        if (intval($miles) > 0) {
            if ($miles == '001') {
                $converted .= 'MIL ';
            } else if (intval($miles) > 0) {
                $converted .= sprintf('%sMIL ', self::convertGroup($miles));
            }
        }
        if (intval($cientos) > 0) {
            if ($cientos == '001') {
                $converted .= 'UN ';
            } else if (intval($cientos) > 0) {
                $converted .= sprintf('%s ', self::convertGroup($cientos));
            }
        }
        if(empty($decimales)){
            // $valor_convertido = $converted . strtoupper($moneda);
            $valor_convertido = $converted . '00/100';
        } else {
            $valor_convertido = $converted . strtoupper($moneda)  . ($div_decimales[1]) . '/100 ';
            // $valor_convertido = $converted . strtoupper($moneda) . ' CON ' . $decimales . ' ' . strtoupper($centimos);
        }
        return $valor_convertido;
    }
    private static function convertGroup($n)
    {
        $output = '';
        if ($n == '100') {
            $output = "CIEN ";
        } else if ($n[0] !== '0') {
            $output = self::$CENTENAS[$n[0] - 1];
        }
        $k = intval(substr($n,1));
        if ($k <= 20) {
            $output .= self::$UNIDADES[$k];
        } else {
            if(($k > 30) && ($n[2] !== '0')) {
                $output .= sprintf('%sY %s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            } else {
                $output .= sprintf('%s%s', self::$DECENAS[intval($n[1]) - 2], self::$UNIDADES[intval($n[2])]);
            }
        }
        return $output;
    }
    
    public static function getAuthUser()
    {
        return Auth::user();
    }

    public static function getRol()
    {
        // return "hola ";
        $roles = Auth::user()->roles;

        $rol = Session::get('rol_id');

        $rol_object =null;
        foreach ($roles as $r) {
            # code...
            if($rol== $r->id)
            {
                $rol_object =$r;
            }
        }

        return $rol_object;

    }
    public static function CheckPermission($className,$action)
    {
        $rol = self::getRol();
        $permission = DB::table('role_permissions')
                    ->join('permissions','permissions.id','=','role_permissions.permission_id')
                    ->join('operations','operations.id','=','permissions.operation_id')
                    ->join('actions','actions.id','=','permissions.action_id')
                    ->where('operations.name','=',$className)
                    ->where('operations.module_id','=',$rol->module_id)
                    ->where('actions.name','=',$action)
                    ->where('role_permissions.role_id',$rol->id)
                    ->select('role_permissions.id')
                    ->first();
        return $permission;
    }
    public static function IconModule($module_id)
    {
        $class_icon = 'fa fa-square';
        switch ($module_id) {
            case 1:
                $class_icon = 'glyphicon glyphicon-hdd';
                break;
            
            case 2:
                $class_icon = 'fa fa-fw fa-puzzle-piece';
                break;
            case 3:
                $class_icon = 'glyphicon glyphicon-piggy-bank';
                break;
            case 4:
                $class_icon = 'fa fa-fw fa-heartbeat';
                break;
            case 6:
                $class_icon = 'fa fa-fw fa-money';
                break;
            case 7:
                $class_icon = 'fa fa-balance-scale';
                break;
            case 10:
                $class_icon = 'fa fa-map';
                break;
        }
        return $class_icon;
    }
    public static function calculateAge($birth_date, $death_date)
    {
        $birth_date =  Carbon::createFromFormat('d/m/Y',$birth_date );
        if ($death_date) {
            $death_date = Carbon::parse($death_date);
            $age = $birth_date->diff($death_date)->format('%y años %m meses');
        }else {
            $age = $birth_date->diff(Carbon::now())->format('%y años %m meses');
        }
        return $age;
    }
    public static function calculateAgeYears($birth_date, $death_date)
    {
        $birth_date = Carbon::createFromFormat('d/m/Y',$birth_date);
        if ($death_date) {
            $death_date = Carbon::parse($death_date);
            $age = $birth_date->diffInYears($death_date);
        }else {
            $age = $birth_date->diffInYears(Carbon::now());
        }
        return $age;
    }
    public static function getCivilStatus($est, $gender)
    {
        switch ($est) {
            case 'S':
                return $gender ? ($gender == 'M' ? 'SOLTERO' : 'SOLTERA') : 'SOLTERO(A)' ;
                break;
            case 'D':
                return $gender ? ($gender == 'M' ? 'DIVORCIADO' : 'DIVORCIADA') : 'DIVORCIADO(A)' ;
                break;
            case 'C':
                return $gender ? ($gender == 'M' ? 'CASADO' : 'CASADA') : 'CASADO(A)' ;
                break;
            case 'V':
                return $gender ? ($gender == 'M' ? 'VIUDO' : 'VIUDA') : 'VIUDO(A)' ;
                break;
            case 'S':
                return $gender ? ($gender == 'M' ? 'SOLTERO' : 'SOLTERA') : 'SOLTERO(A)' ;
                break;
            default:
                return '';
                break;
        }
    }
    public static function extractMonth($date, $short=true)
    {
        if($date){
            if($short){

                return Carbon::parse($date)->formatLocalized("%b");
            }
            return Carbon::parse($date)->formatLocalized("%B");
        }
    }
    public static function extractYear($date)
    {
        if($date){
            return Carbon::parse($date)->formatLocalized("%Y");
        }
    }
    public static function getDateFormat($date, $size='short')
    {
        setlocale(LC_TIME, 'es_ES.utf8');
        if ($date) {
            if (self::verifyMonthYearDate($date) ) {
                $date = Carbon::createFromFormat('d/m/Y', '01/'.$date)->toDateString();
            }
            if ($size == 'short') {
                // return 05 MAY. 1983 // change %d-> %e for 5 MAY. 1983
                return Carbon::parse($date)->formatLocalized('%d %b. %Y'); //
            }elseif ($size == 'large') {
                return Carbon::parse($date)->formatLocalized('%d %B. %Y'); //
            }
        }
        return 'sin fecha';
    }
    public static function getRetFunCurrentProcedure()
    {
        $procedure_active = RetFunProcedure::where('is_enabled', 'true')->first();
        if (!$procedure_active) {
            Log::info("No existe ret fun procedure activo");
            abort(500);
        }
        return $procedure_active;
    }
    public static function sumTotalContributions($array, $fromView = false)
    {
        $total = 0;
        if (sizeof($array) > 0) {
            foreach ($array as $key => $value) {
                if ($fromView) {
                    // $value = json_encode($value);
                    $diff = Carbon::parse($value['start'])->diffInMonths(Carbon::parse($value['end'])) + 1;
                } else {
                    $diff = (Carbon::parse($value->end)->diffInMonths(Carbon::parse($value->start)) + 1);
                }
                if ($diff < 0) {
                    dd("error");
                }
                $total = $total + $diff;
            }
        }
        return $total;
    }
    public static function getGenderLabel($gender)
    {
        $label= 'genero no valido';
        switch($gender)
        {
            case 'F':
                $label = 'Femenino';
                break;
            case 'M':
                $label = 'Masculino';
                break;
        }
        return $label;
    }
    /*for legal opinion print*/
    public static function getDiscountCombinations($ret_fun_id)
    {
        $retirement_fund = RetirementFund::find($ret_fun_id);
        $array_discounts = array();
        $array_discounts_text = array();

        $array = DiscountType::all()->pluck('id');
        $results = array(array());
        foreach ($array as $element) {
            foreach ($results as $combination) {
                array_push($results, array_merge(array($element), $combination));
            }
        }
        foreach ($results as $value) {
            $sw = true;
            foreach ($value as $id) {
                if (!$retirement_fund->discount_types()->find($id)) {
                    $sw = false;
                }
            }
            if ($sw) {
                $temp_total_discount = 0;
                $array_discounts_text = array();
                foreach ($value as $id) {
                    $amount = $retirement_fund->discount_types()->find($id)->pivot->amount;
                    $temp_total_discount = $temp_total_discount + $amount;
                    array_push($array_discounts_text, "que descontado el monto ".self::formatMoney($amount,true).' ('.self::convertir($amount). " BOLIVIANOS) por concepto de ". $retirement_fund->discount_types()->find($id)->name);
                }
                $name = join(' - ', DiscountType::whereIn('id', $value)->orderBy('id', 'asc')->get()->pluck('name')->toArray());
                array_push($array_discounts, array('name' => $name, 'amount' => $temp_total_discount));
            }
        }
        $name = join(' - ', $array_discounts_text);
        $pos = strrpos($name, ' - que ');
        if ($pos !== false) {
            $name = substr_replace($name, ' y ', $pos, strlen(' - que '));
        }
        $name = str_replace(" -", ",", $name);
        if (sizeOf($array_discounts_text) > 0) {
            $name = ', '. $name.", queda un saldo de ".self::formatMoney($array_discounts[sizeOf($array_discounts)-1]['amount'], true).' ('.self::convertir($array_discounts[sizeOf($array_discounts) - 1]['amount']) .' BOLIVIANOS).';
        }
        return $name;
    }
    public static function updateAffiliatePersonalInfo($affiliate_id, $object)
    {
        $affiliate = Affiliate::find($affiliate_id);
        $affiliate->identity_card = $object->identity_card;
        $affiliate->first_name = $object->first_name;
        $affiliate->second_name = $object->second_name;
        $affiliate->last_name = $object->last_name;
        $affiliate->mothers_last_name = $object->mothers_last_name;
        $affiliate->surname_husband = $object->surname_husband;
        $affiliate->gender = $object->gender;
        $affiliate->birth_date = Util::verifyBarDate($object->birth_date) ? Util::parseBarDate($object->birth_date) : $object->birth_date;
        $affiliate->phone_number = $object->phone_number;
        $affiliate->cell_phone_number = $object->cell_phone_number;
        $affiliate->city_birth_id = $object->city_birth_id;
        $affiliate->city_identity_card_id = $object->city_identity_card_id;
        $affiliate->save();
    }
    public static function updateCreateSpousePersonalInfo($affiliate_id, $object)
    {
        $spouse = Spouse::where('affiliate_id',$affiliate_id)->first();
        if (!$spouse) {
            $spouse = new Spouse();
            $spouse->affiliate_id = $affiliate_id;
            $spouse->user_id = self::getAuthUser()->id;
            $spouse->registration = 0;
        }
        $spouse->identity_card = $object->identity_card;
        $spouse->first_name = $object->first_name;
        $spouse->second_name = $object->second_name;
        $spouse->last_name = $object->last_name;
        $spouse->mothers_last_name = $object->mothers_last_name;
        $spouse->surname_husband = $object->surname_husband;
        $spouse->civil_status = $object->civil_status;
        $spouse->birth_date = Util::verifyBarDate($object->birth_date) ? Util::parseBarDate($object->birth_date) : $object->birth_date;
        $spouse->city_birth_id = $object->city_birth_id;
        $spouse->city_identity_card_id = $object->city_identity_card_id;
        $spouse->save();
    }
    public static function classificationContribution($contribution_type_id, $breakdown_id, $total)
    {
        if($contribution_type_id){
            return $contribution_type_id;
        }
        switch ($breakdown_id) {
            case 1:
                return 10;
                break;
            case 3:
                return $total == 0 || ! isset($total) ? 3 : 2;
            case 5:
                return $total == 0 || ! isset($total) ? 5 : 4;
            case 10:
                return 1;
                break;
            default:
                return null;
            break;
        }
    }

    public static function getRegistration($birth_date = null, $last_name, $mothers_last_name, $first_name, $gender)
	{        
		if ($birth_date) {
			$birth_date_exploded = explode("-", $birth_date);
			$day = $birth_date_exploded[2];
			$month = $birth_date_exploded[1];
			$year = substr($birth_date_exploded[0], -2);
			$month_first_digit = substr($month, 0, 1);
            $month_second_digit = substr($month, 1, 1);
            
			if($last_name  != '') {
				$last_name_code = mb_substr($last_name, 0, 1);
			} else {
				$last_name_code = '';
            }
            
			if($mothers_last_name != '') {
				$mothers_last_name_code = mb_substr($mothers_last_name, 0, 1);
			} else {				
				$mothers_last_name_code = mb_substr($last_name, 1, 1);
            }
            
			if($first_name != '') {
				$first_name_code = mb_substr($first_name, 0, 1);
			} else {
				$first_name_code = '';
            }
            
			if($gender == "M") {
				return $year . $month . $day . $last_name_code . $mothers_last_name_code . $first_name_code;
            } elseif ($gender == "F") {
				if($month_first_digit == 0) {
					$month_second_digit = "5" .$month_second_digit;
				} elseif ($month_first_digit == 1) {
					$month_second_digit = "6" . $month_second_digit;
				}
				return $year . $month_second_digit . $day . $last_name_code . $mothers_last_name_code . $first_name_code;
			}
        }
        else {
            return null;
        }
    }


}