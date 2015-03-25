<?php

namespace MarC;

use UniCAT\MethodScope;
use UniCAT\CodeExport;
use UniCAT\UniCAT;
use UniCAT\Comments;

/**
 * @package VMaX-MarC
 *
 * @author V�clav Mac�rek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015, V�clav Mac�rek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * generation of row of unique elements
 */
class UniqueAssembler extends ElementListSetting
{
	use StylesAttributesSetting, CodeExport, Comments;
	
	/**
	 * list of orders - used to check if all styles and attributes were set correctly
	 *
	 * @var array
	 */
	protected $List_UsedOrders = array();
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
	 * sets elements for main (top) and secondary (sub) level;
	 * prevents sharing of content with previous instances
	 *
	 * @param string|array $TopElement
	 * @param string|array $SubElements
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if top element was not set
	 * @throws MarC_Exception if sub elements was not set
	 * @throws MarC_Exception if top element is the same as one of sub elements
	 * @throws MarC_Exception if sub elements are not unique
	 */
	public function __construct($TopElement="", $SubElements="")
	{
		try
		{
			if(empty($TopElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0]);
		}
		
		try
		{
			if(empty($SubElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1]);
		}
		
		try
		{
			if(!is_string($TopElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRMS, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0], gettype($TopElement), 'string');
		}
		
		try
		{
			if(!is_array($SubElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRMS, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0], gettype($SubElements), 'array');
		}
		
		try
		{
			if(in_array($TopElement, $SubElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRMS, UniCAT::UNICAT_EXCEPTIONS_SEC_PRMS_PRHBVALEQUAL);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0]);
		}
		
		try
		{
			if(max(array_values(array_count_values($SubElements))) > 1)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_VAR_DMDUNQARR);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1]);
		}
		
		$this -> Elements['top'] = $TopElement;
		$this -> Elements['sub'] = $SubElements;
	}
	
	/**
	 * prevents sharing of content with following instances;
	 * erases non-static variables
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this -> Content = array();
		$this -> List_UsedOrders = array();
		$this -> Disable_TopLevel = FALSE;
	}
	
	/**
	 * allows to call functions with names of created elements;
	 * use function with name Element_Style or Element_Attribute where Element means used element and Style or Attribute is instruction how parameters will be processed;
	 * for namespaced element use method Set_ElementsNamespace before
	 *
	 * @param string $Function
	 * @param array $Parameters
	 *
	 * @throws MarC_Exception if function was set in wrong way
	 */
	public function __call($Function, array $Parameters)
	{
		$Options = array('Style', 'Attribute');
		$this -> ElementsNamespace = ($this -> ElementsNamespace == FALSE ? "" : $this -> ElementsNamespace);
		
		if(method_exists($this, $Function))
		{
			call_user_func_array(array($this, $Function), $Parameters);
		}
		else
		{
			try
			{
				if(!preg_match('/(?<Element>[A-Za-z]+)_(?<Order>Style|Attribute)/i', $Function, $Parts))
				{
					throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGREGEX);
				}
			}
			catch(MarC_Exception $Exception)
			{
				$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0], $Function, '/(?<Element>[A-Za-z]+)_(?<Order>Style|Attribute)/i');
			}
			
			$Element = $this -> ElementsNamespace.':'.strtolower($Parts['Element']);
			$Order = $Parts['Order'];
			
			try
			{
				if($Element != $this -> Elements['top'] && !in_array($Element, $this -> Elements['sub']))
				{
					throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_DMDOPTION, MarC::MARC_EXCEPTIONS_XPLN_USEDELMT);
				}
			}
			catch(MarC_Exception $Exception)
			{
				array_unshift($this -> Elements['sub'], $this -> Elements['top']);
				$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0], $this -> Elements['sub']);
			}
			
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
	}
	
	/**
	 * sets content
	 *
	 * @param string $Item
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if content item was set wrong
	 * @throws MarC_Exception if content item was not as string, integer or double
	 */
	public function Set_Content($Item="")
	{
		try
		{
			if(!empty($Item) && !in_array(gettype($Item), MarC::Show_Options_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__), gettype($Item[0]), MarC::Show_Options_Scalars());
		}
		
		/*
		 * sets content to needed form of associative array
		 */
		$this -> Content[] = $Item;
	}
	
	/**
	 * disables generation of top level element;
	 * useful for generation of page's <head>, where more elements (meta, title, script, link ...) are present
	 *
	 * @return void
	 */
	public function Set_DisableTopLevel()
	{
		$this -> Disable_TopLevel = TRUE;
	}
	
	/**
	 * allows using of namespaced elements
	 *
	 * @return void
	 */
	public function Set_ElementsNamespace($Namespace=FALSE)
	{
		try
		{
			if(empty($Namespace))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(preg_match('/[a-zA-Z]/', $Namespace))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__), $Namespace, '[a-zA-Z]');
		}
		
		$this -> ElementsNamespace = $Namespace;
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
			$VMaX = new CodeGenerator($this -> Elements['sub'][$Order]);
			
			/*
			 * part 2;
			 * sets styles
			 */
			if(isset($this -> ElementStyles_Global[$this -> Elements['sub'][$Order]]))
			{
				foreach($this -> ElementStyles_Global[$this -> Elements['sub'][$Order]] AS $Name => $Value)
				{
					$VMaX -> Set_Style($Name, $Value);
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
					$VMaX -> Set_Attribute($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets text wrapped by element of sub-level;
			 * automatically detects empty elements
			 */
			if(self::$List_AvailableElements[$this -> Elements['sub'][$Order]] != $this -> Elements['sub'][$Order])
			{
				$VMaX -> Set_Text((empty($this -> Content[$Order]) ? '' : $this -> Content[$Order] ));
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
					$VMaX -> Set_ExportType(UniCAT::UNICAT_OPTION_GOON);
					$VMaX -> Execute();
				}
				else
				{
					$VMaX -> Set_ExportType(static::$ExportWay);
					return $VMaX -> Execute();
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
					$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
					$VMaX -> Execute();
				}
				else
				{
					$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
					$this -> LocalCode = $VMaX -> Execute();
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
			$VMaX = new CodeGenerator($this -> Elements['top']);
			$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_SKIP);
			
			/*
			 * part 2;
			 * sets styles
			 */
			if(isset($this -> ElementStyles_Global[$this -> Elements['top']]))
			{
				foreach($this -> ElementStyles_Global[$this -> Elements['top']] AS $Name => $Value)
				{
					$VMaX -> Set_Style($Name, $Value);
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
					$VMaX -> Set_Attribute($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets text of sub-level code to being wrapped by element of top-level;
			 * stores generated code into help variable - to it could be exported
			 */
			$VMaX -> Set_Text($this -> LocalCode);
			$this -> LocalCode = $VMaX -> Execute();
			
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