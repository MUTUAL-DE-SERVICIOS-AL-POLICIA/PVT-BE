<?php

namespace Muserpol\Helpers;

class ID
{
	/**
	* Retorna el id cuando es editable
	*
	* @return int 1
	*/
	public static function getEditableId()
	{
		return 1;
	}

	/**
	* Retorna el id cuando no es editable
	*
	* @return int 0
	*/
	public static function getNonEditableId()
	{
		return 0;
	}

	/**
	* Retorna una modalidad para fondo de retiro
	*
	* @return un objeto retfun
	*/
	public static function retFun()
	{
		$Ret_Fun = [
			'beneficiary_id'=>1,
			'advisor_id'=>2,
			'legal_guardian_id'=>3,
			'jubilacion_id'=>3,
			'fallecimiento_id'=>4,
			'retiro_forzoso_id'=>5,
			'invalidez_permanente_id'=>6,
			'retiro_voluntario_id'=>7
		];
		return((object)$Ret_Fun);
	}

	/**
	* Retorna una modalidad en pago global  de fondo de retiro
	*
	* @return un objeto ret_fun Global_pay
	*/
	public static function retFunGlobalPay()
	{
		$Global_Pay = [
			'fallecimiento_id'=>1,
			'retiro_forzoso_id'=>2
		];
		return((object)$Global_Pay);
	}

	/**
	* Retorna algun parentesco con el afiliado
	*
	* @return un objeto kinship
	*/
	public static function kinship()
	{
		$Ids = [
			'titular'=>1,
			'conyuge'=>2,
			'hijo'=>3,
			'padre'=>4,
			'madre'=>5,
			'hermano'=>6,
			'otro'=>7
		];
		return((object)$Ids);
	}

	/**
	* Retorna el id de algun departamento
	*
	* @return un objeto cityId
	*/
	public static function cityId()
	{
		$Ids = [
			'BN' => 1,
			'CH' => 2,
			'CB' => 3,
			'LP' => 4,
			'OR' => 5,
			'PD' => 6,
			'PT' => 7,
			'SC' => 8,
			'TJ' => 9,
			'BO' => 10
		];
		return((object)$Ids);
	}

	/**
	* Retorna el id
	*
	* @return un objeto affiliateState
	*/
	public static function affiliateState()
	{
		$Ids = [
			'servicio'=>1,
			'comision'=>2,
			'disponib'=>3,
			'fallecido'=>4,
			'jubilado'=>5,
			'invalidez'=>6,
			'baja_forz'=>7,
			'baja_Volu'=>8,
			'baja_Temp'=>9
		];
		return((object)$Ids);
	}

	/**
	* Retorna el estado en que se encuentra
	*
	* @return un objeto retFunState
	*/
	public static function state()
	{
		$Ids = [
			'enproceso'=>1,
			'pendiente'=>2,
			'eliminado'=>3
			];
		return((object)$Ids);
	}

}
