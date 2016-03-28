<?php

namespace MarC;

use UniCAT\ClassScope;
use UniCAT\MethodScope;

/**
 * @package VMaX-UniCAT
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * trait of functions for getting of various options
 */
trait ConditionalComments
{
	/**
	 * text of comment defined by chosen conditional comment
	 * 
	 * @static
	 * @var array
	 */
	protected static $ConditionalComments = FALSE;
	
	/**
	 * sets comment's text and position
	 *
	 * @param string $Comment full text of conditional comment or rather constant that represents it
	 *
	 * @throws MarC_Exception
	 *
	 * @example Set_ConditionalComment(MarC\MarC::MARC_CODE_CONDCOMMENT_IE); to use basic IE conditional comment
	 */
	public function Set_ConditionalComment($Comment)
	{
		try
		{
			if(empty($Comment))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(!in_array($Comment, ClassScope::Get_ConstantsValues('MarC\I_MarC_Texts_ConditionalComments')))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), ClassScope::Get_ConstantsNames('MarC\I_MarC_Texts_ConditionalComments'));
		}
	
		static::$ConditionalComments = $Comment;
	}
	
	/**
	 * adds conditional comments into final code
	 * 
	 * @param string $Code code that is inserted into conditional comment
	 * @param string $Comments conditional comment
	 * 
	 * @throws MarC_Exception
	 */
	public static function Add_ConditionalComments(&$Code, $Comments="")
	{
		try
		{
			if(empty($Code))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}

		if(!empty($Comments))
		{
			$Code = sprintf($Comments, $Code);
		}
		else
		{
			$Code = $Code;
		}
	}
}

?>