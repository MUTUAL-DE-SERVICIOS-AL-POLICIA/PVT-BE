<?php

namespace Muserpol\Helpers;
use DateTime;
use Session;
use Auth;
use DB;
use Carbon\Carbon;
use Log;
use Muserpol\Models\RetirementFund\RetFunProcedure;
use Muserpol\Models\RetirementFund\RetFunCorrelative;
use Muserpol\Models\Workflow\WfState;
use Muserpol\Models\Role;
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

    public static function formatMoney($value)
    {
        if ($value) {
            $value = number_format($value, 2, '.', ',');
            return $value;
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
                $name = mb_strtoupper($object->first_name ?? '') . ' ' . mb_strtoupper($object->second_name ?? '') . ' ' . mb_strtoupper($object->last_name ?? '') . ' ' . mb_strtoupper($object->mothers_last_name ?? '') . ' ' . mb_strtoupper($object->applicant_surname_husband ?? '');
                break;
            case 'lowercase':
                $name = mb_strtolower($object->first_name ?? '') . ' ' . mb_strtolower($object->second_name ?? '') . ' ' . mb_strtolower($object->last_name ?? '') . ' ' . mb_strtolower($object->mothers_last_name ?? '') . ' ' . mb_strtolower($object->applicant_surname_husband ?? '');
                break;
            case 'capitalize':
                $name = ucfirst(mb_strtolower($object->first_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->second_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->last_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->mothers_last_name ?? '')) . ' ' . ucfirst(mb_strtolower($object->applicant_surname_husband ?? ''));
                break;
        }
        $name = self::removeSpaces($name);
        return $name;
    }

    public static function getPDFName($title,$affi){
        $date =  Util::getStringDate(date('Y-m-d'));
        return ($title." - ".$affi->fullName()." - ".$date.".pdf");
    }

    public static function getNextCode($actual){
        $year =  date('Y');
        if($actual == "")
            return "675/".$year;  
        $data = explode('/', $actual);
        if(!isset($data[1]))
            return "675/".$year;                
        return ($year!=$data[1]?"1":($data[0]+1))."/".$year;
    }

    public static function getNextAreaCode($retirement_fund_id){
        $wf_state = WfState::where('role_id', Session::get('rol_id'))->first();        
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
        $correlative->save();

        return $role->correlative;
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
        $birth_date =  Carbon::parse($birth_date);
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
        $birth_date = Carbon::parse($birth_date);
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
    public function extractMonth($date)
    {
        if($date){
            return Carbon::parse($date)->formatLocalized("%B");
        }
    }
    public function extractYear($date)
    {
        if($date){
            return Carbon::parse($date)->formatLocalized("%Y");
        }
    }
    public static function getDateFormat($date, $size='short')
    {
        setlocale(LC_TIME, 'es_ES.utf8');
        if ($date) {
            if ($size == 'short') {
                // return 05 MAY. 1983 // change %d-> %e for 5 MAY. 1983
                return Carbon::parse($date)->formatLocalized('%d %b. %Y'); //
            }elseif ($size == 'large') {
                return Carbon::parse($date)->formatLocalized('%d %B. %Y'); //
            }
        }
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
                    $diff = Carbon::parse($value->start)->diffInMonths(Carbon::parse($value->end)) + 1;
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
}