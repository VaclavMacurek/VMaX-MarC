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
	 * @param string $Line text of line added above main code
	 *
	 * @throws MarC_Exception
	 *
	 * @example new DTDLine(MarC::MARC_CODE_XHTML1STRICT); for strict DTD of XHTML1
	 */
	public function __construct($Line)
	{
		/*
		 * initial setting of instance of classes MarC and UniCAT
		 */
		MarC::Set_Instance();

		try
		{
			if(empty($Line))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
			else
			{
				$this -> Set_Line($Line);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
	}

	/**
	 * reading of file with line of DTD setting/XML head of XML-based files;
	 * writing of content of this file
	 *
	 * @param string $Line text of line added above main code
	 *
	 * @throws MarC_Exception
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
			$Exception -> ExceptionWarning(__CLASS__, $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), MarC::ShowOptions_CodeHeading());
		}

		echo $Line."\n";
	}

}

?>