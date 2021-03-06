<?php

namespace MarC;

use UniCAT\CodeExport;
use UniCAT\Comments;
use UniCAT\UniCAT;
use UniCAT\MethodScope;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015, Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * generation of row of unique elements
 *
 * @method void Set_Element_Style(string $Name, string $Value); sets element style (use name of element instead "Element")
 * @method void Set_Element_Attribute(string $Name, string $Value); sets element attribute (use name of element instead "Element")
 * @method void Set_Elůement_ValuesSeparator(string $Attribute, string $Separator); sets separator of element attribute values (use name of element instead "Element")
 */
class UniqueAssembler extends ElementListSetting
{
	use ConditionalComments, StylesAttributesSetting, CodeExport, Comments
	{
		Add_Comments as private;
		Add_ConditionalComments as private;
	}
	
	/**
	 * list of orders - used to check if all styles and attributes were set correctly
	 *
	 * @var array
	 */
	protected $UsedOrders = array();
	/**
	 * disables wrapping code into top level element
	 */
	protected $Disable_TopLevel = FALSE;
	/**
	 * elements used for creation of columns (and row)
	 *
	 * @static
	 * @var array
	 */
	protected $Elements = array();
	/**
	 * columns content
	 *
	 * @var array
	 */
	protected $Content = array();
	/**
	 * namespace;
	 * for namespaced elements
	 *
	 * @var string
	 */
	protected $ElementsNamespace = FALSE;
	/**
	 * object for class CodeGenerator
	 *
	 * @var object
	 */
	private $UniqueAssembler = FALSE;
	
	/**
	 * sets elements for main (top) and secondary (sub) level;
	 * prevents sharing of content with previous instances
	 *
	 * @param string|array $TopElement
	 * @param string|array $SubElements
	 *
	 * @throws MarC_Exception if top element was not set
	 * @throws MarC_Exception if sub elements was not set
	 * @throws MarC_Exception if top element is the same as one of sub elements
	 * @throws MarC_Exception if sub elements are not unique
	 *
	 * @example new UniqueAssembler('html', array('head', 'body') );
	 */
	public function __construct($TopElement, $SubElements)
	{
		/*
		 * disables multiple new lines and shortens code in that way
		 */
		MarC::Set_DisableMultipleNewLines();

		if(empty($TopElement))
		{
			$this -> Disable_TopLevel = TRUE;
		}
		
		try
		{
			if(empty($SubElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1));
		}
		
		try
		{
			if(!is_string($TopElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRMS, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($TopElement), 'string');
		}
		
		try
		{
			if(!is_array($SubElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRMS, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($SubElements), 'array');
		}
		
		try
		{
			if(!empty($TopElement) && in_array($TopElement, $SubElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRMS, UniCAT::UNICAT_XCPT_SEC_PRMS_PRHBVALEQUAL);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(max(array_values(array_count_values($SubElements))) > 1)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_VAR_DMDUNQARR);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1));
		}


		/*
		 * sets elements used for setting of styles and attributes;
		 * top - top level
		 * sub - sub level
		 * main - really used (what will appear in code)
		 * set - used for styles and attributes (substitution to avoid conflict elements in top and sub level)
		 */
		if($this -> Disable_TopLevel == TRUE)
		{
			if($this -> Check_ElementTreeValidity($SubElements))
			{
				$this -> Elements['sub'] = $SubElements;
			}
		}
		else
		{
			if($this -> Check_ElementTreeValidity($TopElement, $SubElements))
			{
				$this -> Elements['top'] = $TopElement;
				$this -> Elements['sub'] = $SubElements;
			}
		}
	}
	
	/**
	 * prevents sharing of content with following instances;
	 * erases non-static variables
	 */
	public function __destruct()
	{
		$this -> Content = array();
		$this -> UsedOrders = array();
		$this -> Disable_TopLevel = FALSE;
		$this -> Elements = array();
		$this -> ElementsNamespace = FALSE;
		$this -> UniqueAssembler = FALSE;
	}
	
	/**
	 * allows to call functions with names of created elements;
	 * use function with name Element_Style or Element_Attribute where Element means used element and Style or Attribute is instruction how parameters will be processed;
	 * use function with name Element_ValuesSeparator where Element means used element;
	 * for namespaced element use method Set_ElementsNamespace before
	 *
	 * @param string $Function
	 * @param array $Parameters
	 *
	 * @throws MarC_Exception if function was set in wrong way
	 *
	 * @example Body_Style('background-image', 'example.svg');
	 * @example Input_Attribute('type', 'text');
	 * @example Select_ValuesSeparator('class', "\x20");
	 */
	public function __call($Method, $Parameters)
	{
		if(method_exists($this, $Method))
		{
			call_user_func_array(array($this, $Method), $Parameters);
		}
		else
		{
			try
			{
				if(preg_match('/Set_(?<Element>[A-Za-z]+)_(?<Order>Style|Attribute|ValuesSeparator)/i', $Method, $Parts))
				{
					try
					{
						if($this -> Disable_TopLevel == TRUE)
						{
							if(!in_array(strtolower($Parts['Element']), $this -> Elements['sub']))
							{
								throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION, MarC::MARC_XCPT_XPLN_USEDELMT);
							}
						}
						else
						{
							if(strtolower($Parts['Element']) != $this -> Elements['top'] && !in_array(strtolower($Parts['Element']), $this -> Elements['sub']))
							{
								throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION, MarC::MARC_XCPT_XPLN_USEDELMT);
							}
							
						}
					}
					catch(MarC_Exception $Exception)
					{
						array_unshift($this -> Elements['sub'], $this -> Elements['top']);
						$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $this -> Elements['sub']);
					}
					
					if($Parts['Order'] == 'ValuesSeparator')
					{
						$this -> Set_ValuesSeparators($Parts, $Parameters);
					}
					else
					{
						$this -> Set_StylesAttributes($Parts, $Parameters);
					}
				}
				else
				{
					throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGREGEX);
				}
			}
			catch(MarC_Exception $Exception)
			{
				$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $Method, '/(?<Element>[A-Za-z]+)_(?<Order>Style|Attribute|ValuesSeparator)/i');
			}
		}
	}
	
	/**
	 * sets content
	 *
	 * @param string $Item array value
	 *
	 * @throws MarC_Exception
	 *
	 * @example Set_Content('example');
	 */
	public function Set_Content($Item="")
	{
		try
		{
			if(!empty($Item) && !in_array(gettype($Item), MarC::ShowOptions_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Item[0]), MarC::ShowOptions_Scalars());
		}
		
		$this -> Content[] = $Item;
	}
	
	/**
	 * disables generation of top level element;
	 * usage of this function may be accidentally avoided with using of empty argument (using argument as '')
	 */
	public function Set_DisableTopLevel()
	{
		$this -> Disable_TopLevel = TRUE;
	}
	
	/**
	 * allows using of namespaced elements - because __call is not designed to use namespaced elements directly;
	 * it is not allowed to combine namespaces
	 *
	 * @throws MarC_Exception
	 *
	 * @example Set_ElementsNamespace('xsl');
	 */
	public function Set_ElementsNamespace($Namespace=FALSE)
	{
		try
		{
			if(empty($Namespace) || $Namespace == FALSE)
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
			if(preg_match('/[a-zA-Z]/', $Namespace))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $Namespace, '[a-zA-Z]');
		}
		
		$this -> ElementsNamespace = $Namespace;
	}
	
	/**
	 * sets styles and attributes
	 */
	private function Set_StylesAttributes($Parts, $Parameters)
	{
		$Order = $Parts['Order'];
		$Options = array('Style', 'Attribute');
		$Element = $this -> ElementsNamespace == FALSE ? strtolower($Parts['Element']) : $this -> ElementsNamespace.':'.strtolower($Parts['Element']);
			
		if($Order == $Options[0])
		{
			if($this -> Check_StyleName($Parameters[0]))
			{
				array_unshift($Parameters, $Element);
				call_user_func_array(array($this, 'Set_AllElementsStyles'), $Parameters);
			}
		}
		else
		{
			if($this -> Check_AttributeName($Parameters[0]))
			{
				array_unshift($Parameters, $Element);
				call_user_func_array(array($this, 'Set_AllElementsAttributes'), $Parameters);
			}
		}
	}
	
	/**
	 *
	 */
	private function Set_ValuesSeparators($Parts, $Parameters)
	{
		$Order = $Parts['Order'];
		$Element = $this -> ElementsNamespace == FALSE ? strtolower($Parts['Element']) : $this -> ElementsNamespace.':'.strtolower($Parts['Element']);
			
		if(in_array($Parameters[1], MarC::ShowOptions_ValuesSeparation()))
		{
			array_unshift($Parameters, $Element);
			call_user_func_array(array($this, 'Set_SelectedValuesSeparators'), $Parameters);
		}
	}
	
	/**
	 * assembling of one block of code of the same elements in row
	 *
	 * @return string|void
	 */
	public function Execute()
	{
		/*
		 * generation of sub-level
		 */
		for($Order = 0; $Order < count($this -> Content); $Order++)
		{
			/*
			 * part 1;
			 * sets name of element
			 */
			$this -> UniqueAssembler = new CodeGenerator($this -> Elements['sub'][$Order]);
			
			/*
			 * part 2;
			 * sets styles
			 */
			if(isset($this -> ElementStyles_Global[$this -> Elements['sub'][$Order]]))
			{
				foreach($this -> ElementStyles_Global[$this -> Elements['sub'][$Order]] AS $Name => $Value)
				{
					$this -> UniqueAssembler -> Set_Style($Name, $Value);
				}
			}
			
			/*
			 * part 3;
			 * sets attributes
			 */
			if(isset($this -> ElementAttributes_Global[$this -> Elements['sub'][$Order]]))
			{
				foreach($this -> ElementAttributes_Global[$this -> Elements['sub'][$Order]] AS $Name => $Value)
				{
					$this -> UniqueAssembler -> Set_Attribute($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets text wrapped by element of sub-level;
			 * automatically detects empty elements
			 */
			if(self::$AvailableElements[$this -> Elements['sub'][$Order]]['Siblings'] != 'EMPTY')
			{
				$this -> UniqueAssembler -> Set_Text((empty($this -> Content[$Order]) ? '' : $this -> Content[$Order] ));
			}
			
			/*
			 * part 5 - if top-level element will not be used;
			 * sets way how code will be exported;
			 * sets styles for element of sub-level;
			 */
			if($this -> Disable_TopLevel == TRUE)
			{
				if($Order < count($this -> Content)-1 )
				{
					$this -> UniqueAssembler -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
					$this -> UniqueAssembler -> Execute();
				}
				else
				{
					$this -> UniqueAssembler -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
					$this -> LocalCode = $this -> UniqueAssembler -> Execute();

					/*
					 * sets way how code will be exported;
					 * exports code
					 */
					MarC::Set_ExportWay(static::$ExportWay);
					MarC::Add_Comments($this -> LocalCode, static::$Comments);
					return MarC::Convert_Code($this -> LocalCode, __CLASS__);
				}
			}
			/*
			 * part 5 - if top-level element will not used;
			 * sets way how code will be exported;
			 * sets styles for element of sub-level;
			 */
			else
			{
				if($Order < count($this -> Content)-1 )
				{
					$this -> UniqueAssembler -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
					$this -> UniqueAssembler -> Execute();
				}
				else
				{
					$this -> UniqueAssembler -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
					$this -> LocalCode = $this -> UniqueAssembler -> Execute();
				}
			}
		}
		
		/*
		 * generation of top level
		 */
		if($this -> Disable_TopLevel == FALSE)
		{
			/*
			 * part 1;
			 * sets name of element;
			 * set way how code will be exported
			 */
			$this -> UniqueAssembler = new CodeGenerator($this -> Elements['top']);
			$this -> UniqueAssembler -> Set_ExportWay(UniCAT::UNICAT_OPTION_SKIP);
			
			/*
			 * part 2;
			 * sets styles
			 */
			if(isset($this -> ElementStyles_Global[$this -> Elements['top']]))
			{
				foreach($this -> ElementStyles_Global[$this -> Elements['top']] AS $Name => $Value)
				{
					$this -> UniqueAssembler -> Set_Style($Name, $Value);
				}
			}
			
			/*
			 * part 3;
			 * sets attributes
			 */
			if(isset($this -> ElementAttributes_Global[$this -> Elements['top']][0]))
			{
				foreach($this -> ElementAttributes_Global[$this -> Elements['top']][0] AS $Name => $Value)
				{
					$this -> UniqueAssembler -> Set_Attribute($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets text of sub-level code to being wrapped by element of top-level;
			 * stores generated code into help variable - to it could be exported
			 */
			$this -> UniqueAssembler -> Set_Text($this -> LocalCode);
			$this -> LocalCode = $this -> UniqueAssembler -> Execute();
			
			/*
			 * sets way how code will be exported;
			 * exports code
			 */
			MarC::Set_ExportWay(static::$ExportWay);
			MarC::Add_Comments($this -> LocalCode, static::$Comments);
			return MarC::Convert_Code($this -> LocalCode, __CLASS__);
		}
	}
}

?>