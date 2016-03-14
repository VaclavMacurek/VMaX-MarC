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
 * generation of empty elements with fluent interface;
 * provides easy creation of element as in-line, between other text
 *
 * @method void Set_Attribute(string $Name, string $Value) sets attribute
 * @method void Set_Style(string $Name, string $Value) sets style
 * @method void Set_ValuesSeparator() sets separator for multi-values
 * @method void Set_EnableInLineElement() enables creation of element as in-line
 * @method void Set_DisableIndention(string $Elements) adds element to list of elements that will not be indented
 * @method void Set_Text(string $Text) sets text wrapped by element
 * @method void Execute() executes code generation
 */
class SingleElement extends ElementListSetting
{
	/**
	 * object for class CodeGenerator
	 *
	 * @var object
	 */
	protected $SingleElement;
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
	 * @param string $Element
	 *
	 * @throws MarC_Exception if element name was not set
	 * @throws MarC_Exception if element cannot be used (because only empty elements are invited)
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
			$this -> SingleElement = new CodeGenerator($Element);
			$this -> SingleElement -> Set_ExportWay(UniCAT::UNICAT_OPTION_SKIP);
		}
	}

	/**
	 * allows to call public functions of class CodeGenerator defined in this class;
	 * functions from traits are excluded;
	 * using of function Set_DisableIndention is deprecated (or rather useless) for purpose of class to provide creation of in-line elements between text
	 *
	 * @param string $Element
	 *
	 * @throws MarC_Exception if element name was not set
	 *
	 * @example Set_Style('width', '100%') to set style width 100%
	 * @example Set_Attribute('colspan', 2) to set attribute colspan for mergin two table cells
	 * @example Set_ValuesSeparator('class', "\x20") to set space as separator of values in attribute class
	 * @example Set_EnableInLineElement() to set that text will be to the left of element (in the front of, in left-to-right writing common)
	 * @example Set_DisableIndention() to erase indention (by tabelators) in the front of chosen element
	 */
	public function __call($Method, $Parameters)
	{
		try
		{
			if(in_array($Method, $this -> Methods))
			{
				if($Method == 'Execute')
				{
					return $this -> SingleElement -> Execute();
				}
				else
				{
					call_user_func_array(array($this -> SingleElement, $Method), $Parameters);
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
	 * @param string $Element
	 *
	 * @return \MarC\SingleElement
	 */
	public static function Element($Element)
	{
		return new SingleElement($Element);
	}

	/**
	 * creates list of functions usable for object of CodeGenerator;
	 * list of usable functions is given by deleting of functions defined in linked trait for comments from all available functions
	 */
	private function Get_AvailableMethods()
	{
		/*
		 * the top two lines may be merged into one;
		 * this is for easy reading
		 */
		$this -> Methods = ClassScope::Get_PublicMethods('MarC\CodeGenerator');
		$this -> Methods = array_diff($this -> Methods, ClassScope::Get_PublicMethods('UniCAT\Comments'));
		$this -> Methods = array_diff($this -> Methods, ClassScope::Get_PublicMethods('UniCAT\CodeExport'));
	}
}