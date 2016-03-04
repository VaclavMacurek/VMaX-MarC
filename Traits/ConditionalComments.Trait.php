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
	 * @param string $Text
	 * @param string $Position
	 *
	 * @return void
	 *
	 * @throws UniCAT_Exception if comment was not set
	 * @throws UniCAT_Exception if position setting was set wrong
	 *
	 * @example Set_Comment('example comment');
	 * @example Set_Comment('example comment', UniCAT\UniCAT::UNICAT_OPTION_ABOVE);
	 */
	public function Set_ConditionalComment($Comment="")
	{
		try
		{
			if(empty($Comment))
			{
				throw new UniCAT_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(UniCAT_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(!in_array($Comment, ClassScope::Get_ConstantsValues('MarC\I_MarC_Texts_ConditionalComments')))
			{
				throw new UniCAT_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(UniCAT_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1], ClassScope::Get_ConstantsNames('MarC\I_MarC_Texts_ConditionalComments'));
		}
	
		static::$ConditionalComments = $Comment;
	}
	
	/**
	 * adds conditional comments into final code
	 * 
	 * @param string $Code
	 * @param string $Comments name should be rather only "Comment" - but HTML IE's conditional comments are inserted in pair
	 * 
	 * @return void
	 * 
	 * @throws UniCAT_Exception if $Code is empty
	 * @throws UniCAT_Exception if $Code is not string
	 * @throws UniCAT_Exception if $Comments is empty
	 * @throws UniCAT_Exception if $Comments option is not valid (if it is not available)
	 */
	public static function Add_ConditionalComment(&$Code="", $Comments="")
	{	
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