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
	* Retorna una opcion id
	*
	* @return  un objeto retfun
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
	* Retorna el id
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
	* Retorna el id
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
}
