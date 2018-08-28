<?php

namespace Muserpol\Helpers;

class ID
{
	/**
	* Retorna el id del beneficiario
	*
	* @return int 1
	*/
	public static function getBeneficiaryId()
	{
		return 1;
	}

	/**
	* Retorna el id del Tutor
	*
	* @return int 2
	*/
	public static function getAdvisorId()
	{
		return 2;
	}

	/**
	* Retorna el id del Apoderado
	*
	* @return int 3
	*/
	public static function getLegalGuardianId()
	{
		return 3;
	}

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
	public static function getRetFun()
	{
		$Ret_Fun = [
			'fallecimiento_id'=>3,
			'jubilacion_id'=>4,
			'retiroforzoso_id'=>5,
			'invalidezpermanente_id'=>6,
			'retirovoluntario_id'=>7
		];
		return((object)$Ret_Fun);
	}

	/**
	* Retorna el id
	*
	* @return un objeto Global_pay
	*/
	public static function getGlobalPay()
	{
		$Global_Pay = [
			'fallecimiento_id'=>1,
			'retiroforzoso_id'=>2
		];
		return((object)$Global_Pay);
	}
}
