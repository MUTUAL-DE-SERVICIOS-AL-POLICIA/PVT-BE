<?php

namespace Muserpol\Helpers;

class Ids
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
	* Retorna el id de fallecmiento en Pago Global
	*
	* @return int 1
	*/
	public static function getGlobalPay()
	{
		return 1;
	}

	/**
	* Retorna el id de fallecimiento en Fondo de Retiro
	*
	* @return int 4
	*/
	public static function getRetFun()
	{
		return 4;
	}

}
