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
	 * reading of file with line of DTD setting/XML head of XML-based files;
	 * writing of content of this file
	 *
	 * @param string $Line
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if file was not set
	 *
	 * @example new DTDLine(MarC::MARC_CODE_XHTML1STRICT); for strict DTD of XHTML1
	 */
	public function __construct($Line)
	{
		/*
		 * initial setting of instance of classes MarC and UniCAT
		 */
		UniCAT::Set_Instance();
		MarC::Set_Instance();
		
		$this -> Set_Line($Line);
	}

	/**
	 * reading of file with line of DTD setting/XML head of XML-based files;
	 * writing of content of this file
	 *
	 * @param string $Line
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if file was not set
	 *
	 * @example new DTDLine(MarC::MARC_CODE_XHTML1STRICT); for strict DTD of XHTML1
	 */
	public function Set_Line($Line)
	{
		try
		{
			if(!in_array($Line, ClassScope::Get_ConstantsValues('MarC\I_MarC_Texts_CodeHeading')))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), MarC::Show_Options_CodeHeading());
		}

		echo $Line."\n";
	}

}

?>