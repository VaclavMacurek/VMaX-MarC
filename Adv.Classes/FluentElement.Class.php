<?php

namespace MarC;

use UniCAT\UniCAT;
use UniCAT\MethodScope;
use UniCAT\ClassScope;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * generation of elements with fluent interface;
 * provides easy creation of element as in-line, between other text
 *
 * @method void Set_Comment(string $Comment) sets comment
 * @method void Set_ConditionalComment(string $Comment) sets conditional comment
 * @method void Set_Attribute(string $Name, string $Value) sets attribute
 * @method void Set_Style(string $Name, string $Value) sets style
 * @method void Set_ValuesSeparator() sets separator for multi-values
 * @method void Set_EnableInLineElement() enables creation of element as in-line
 * @method void Set_DisableIndention(string $Elements) adds element to list of elements that will not be indented
 * @method void Set_Text(string $Text) sets text wrapped by element
 * @method void Execute() executes code generation
 */
class FluentElement extends ElementListSetting
{
	/**
	 * object for class CodeGenerator
	 *
	 * @var object
	 */
	protected $FluentElement;
	/**
	 * list of available functions;
	 * given by private function Get_AvailableMethods();
	 *
	 * @var array
	 */
	private $Methods = array();

	/**
	 * sets used element
	 *
	 * @param string $Element element name
	 *
	 * @throws MarC_Exception
	 *
	 * @example new CodeGenerator('hr');
	 */
	public function __construct($Element)
	{
		$this -> Get_AvailableMethods();

		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__) );
		}

		if($this -> Check_ElementTreeValidity($Element))
		{
			/*
			 * excluding of functions defined in traits from calling causes that this setting of export way is final;
			 * option SKIP causes that code of this object will be only exported, without saving anywhere;
			 * see function Execute of CodeGenerator for details
			 */
			$this -> FluentElement = new CodeGenerator($Element);
			$this -> FluentElement -> Set_ExportWay(UniCAT::UNICAT_OPTION_SKIP);
		}
	}

	/**
	 * allows to call public functions of class CodeGenerator defined in this class;
	 * using of function Set_DisableIndention is deprecated (or rather useless) for purpose of class to provide creation of in-line elements between text
	 *
	 * @param string $Element element name
	 *
	 * @return self
	 *
	 * @throws MarC_Exception
	 */
	public function __call($Method, $Parameters)
	{
		try
		{
			if(in_array($Method, $this -> Methods))
			{
				if($Method == 'Execute')
				{
					return $this -> FluentElement -> Execute();
				}
				else
				{
					call_user_func_array(array($this -> FluentElement, $Method), $Parameters);
					return $this;
				}
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_SEC_FNC_MISSING1);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $Method);
		}
	}

	/**
	 * starts chain of functions in fluent interface
	 *
	 * @param string $Element element name
	 *
	 * @return FluentElement
	 *
	 * @example Element('a') for creation element <a>
	 */
	public static function Element($Element)
	{
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
			else
			{
				return new FluentElement($Element);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__) );
		}
	}

	/**
	 * creates list of functions usable for object of CodeGenerator;
	 * currently mostly useless - because ClassScope::Get_PublicMethods() may be used instead it with the same result
	 */
	private function Get_AvailableMethods()
	{
		$this -> Methods = ClassScope::Get_PublicMethods('MarC\CodeGenerator');
	}
}