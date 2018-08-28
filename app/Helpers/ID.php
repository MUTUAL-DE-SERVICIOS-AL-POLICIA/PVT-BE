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
	* @return  3 - 7 una opcion de estos
	*/
	public static function getRetFun()
	{
		$RetFun = [
		'fallecimiento_id'=>3,
		'jubilacion_id'=>4,
		'retiroforzoso_id'=>5,
		'retiroforzoso_invalidezpermanente_id'=>6,
		'retirovoluntario_id'=>7
		];
		return((object)$RetFun);
	}

	/**
	* Retorna el id
	*
	* @return int 1 o 2
	*/
	public static function getGlobalPayContributions()
	{
		$GlobalPay = [
		'fallecimiento_id'=>1,
		'retiroforzoso_id'=>2
		];
		return((object)$GlobalPay);
	}
}
