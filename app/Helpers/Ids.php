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
	* Retorna el is_editable true
	*
	* @return int 0
	*/
	public static function getEditableId()
	{
		return 1;
	}

	/**
	* Retorna el is_editable false
	*
	* @return int 1
	*/
	public static function getNunEditableId()
	{
		return 0;
	}
}
