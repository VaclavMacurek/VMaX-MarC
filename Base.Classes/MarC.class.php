<?php

namespace MarC;

use UniCAT\InstanceOptions;
use UniCAT\CodeExport;
use UniCAT\CodeMemory;
use UniCAT\UniCAT;
use UniCAT\Comments;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * class for easier access to class constants of interfaces
 */
final class MarC extends UniCAT implements I_MarC_Expressions_Union, I_MarC_Options_ContentUsage, I_MarC_Texts_Exceptions
{
	use CodeExport, CodeMemory, Comments,
	InstanceOptions
	{
		Set_Instance as public;
	}
	
	/**
	 * prepares lists of options
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		self::$Options['element_construction'] = $this -> Get_Options("MarC\I_MarC_Options_ElementConstruction");
		self::$Options['content_usage'] = $this -> Get_Options("MarC\I_MarC_Options_ContentUsage");
	}
	
	/**
	 * sets instance, if it is not ready
	 *
	 * @return resource
	 */
	public static function Set_Instance()
	{
		if(static::Check_IsInstanced() == FALSE)
		{
			static::$Instance = new MarC();
			return static::$Instance;
		}
	}
	
	/**
	 * shows available options for element construction in class CodeGenerator
	 *
	 * @return array
	 *
	 * @throws MarC_Exception if if self::$Options was not set
	 */
	public static function Show_Options_ElementConstruction()
	{
		/*
		 * class instance cannot be set wherever
		 */
		try
		{
			if(!empty(self::$Options['element_construction']))
			{
				return self::$Options['element_construction'];
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_VAR, UniCAT::UNICAT_EXCEPTIONS_SEC_VAR_PRHBSTMT);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_VariableNameAsText(self::$Options), 'empty');
		}
	}
	
	/**
	 * shows available options for content usage in class SimpleAssembler
	 *
	 * @return array
	 *
	 * @throws MarC_Exception if if self::$Options was not set
	 */
	public static function Show_Options_ContentUsage()
	{
		/*
		 * class instance cannot be set wherever
		 */
		try
		{
			if(!empty(self::$Options['content_usage']))
			{
				return self::$Options['content_usage'];
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_VAR, UniCAT::UNICAT_EXCEPTIONS_SEC_VAR_PRHBSTMT);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_VariableNameAsText(self::$Options), 'empty');
		}
	}
}

?>