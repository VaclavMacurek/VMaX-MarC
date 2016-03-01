<?php

namespace MarC;

use UniCAT\UniCAT;
use UniCAT\ClassScope;
use UniCAT\MethodScope;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * generation of line with DTD setting;
 * start setting of markup list;
 * start setting of option of code release
 */
final class DTDLine extends ElementListSetting
{
	/**
	 * reading of file with line of DTD setting;
	 * writing of content of this file
	 *
	 * @param string $Line
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if file was not set
	 *
	 * @example new DTDLine();
	 */
	public function __construct($Line="")
	{
		/*
		 * initial setting of instance of class MarC;
		 * using of function __construct is also available
		 */
		MarC::Set_Instance();
		
		$this -> Set_Line($Line);
	}

	public function Set_Line($Line="")
	{
		try
		{
			if(empty($Line))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, $this -> Get_CallerFunctionName(), MethodScope::Get_Parameters(__CLASS__, __FUNCTION__));
		}

		try
		{
			if(!in_array($Line, ClassScope::Get_ConstantsValues('MarC\I_MarC_Texts_CodeHeading')))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, $this -> Get_CallerFunctionName(), MethodScope::Get_Parameters(__CLASS__, __FUNCTION__), MarC::Show_Options_CodeHeading());
		}

		echo $Line."\n";
	}

}

?>