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
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * generation of advanced code block (like drop-down menu - with or without optgroup)
 */
class DualAssembler extends ElementListSetting implements I_MarC_Options_ContentUsage, I_MarC_Placeholders
{
	use StylesAttributesSetting, CodeExport, Comments;
	
	/**
	 * value for setting of default option;
	 *
	 * @var bool|string
	 */
	private $DefaultOption = FALSE;
	/**
	 * enables using of elements in order set in constructor in all code;
	 * FALSE should be DEFAULT because class is designed for creation of select-optgroup-option drop-down menu
	 *
	 * @var bool
	 */
	private $Enable_SimpleOrder = FALSE;
	/**
	 * elements used for creation of code
	 *
	 * @var array
	 */
	protected $Elements = array();
	/**
	 * attributes used for purposes of accidental usage for additional content
	 *
	 * @var array
	 */
	protected $SpecialAttributes = array();
	
	/**
	 * content of form
	 *
	 * @var bool|array
	 */
	private $Content = FALSE;
	
	/**
	 * sets elements for main (top), secondary (mid) level and tertiary (sub) level;
	 * prevents sharing of content with previous instances
	 *
	 * @param string $TopElement
	 * @param string $MidElement
	 * @param string $SubElement
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if all elements were set the same
	 *
	 * @example new DualAssembler('select', 'optgroup', 'option')
	 */
	public function __construct($TopElement, $MidElement, $SubElement)
	{
		/*
		 * disables multiple new lines and shortens code in that way
		 */
		MarC::Set_DisableMultipleNewLines();
		
		try
		{
			if(!is_string($TopElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($TopElement), 'string');
		}
		
		try
		{
			if(!is_string($MidElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($TopElement), 'string');
		}
		
		try
		{
			if(!is_string($SubElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($TopElement), 'string');
		}

		if($this -> Check_ElementTreeValidity($TopElement, $MidElement, $SubElement))
		{
			$this -> Elements['top']['main'] = $TopElement;
			$this -> Elements['top']['set'] = self::MARC_PLACEHOLDER_MIDELMT;
			$this -> Elements['mid']['main'] = $MidElement;
			$this -> Elements['mid']['set'] = self::MARC_PLACEHOLDER_MIDELMT;
			$this -> Elements['sub']['main'] = $SubElement;
			$this -> Elements['sub']['set'] = self::MARC_PLACEHOLDER_SUBELMT;
		}
	}
	
	/**
	 * erases non-static variables
	 *
	 * @return void
	 */
	public function __destruct()
	{
		$this -> DefaultOption = FALSE;
		$this -> Content = FALSE;
	}

	/**
	 * sets default option
	 *
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if default value was not set
	 * @throws MarC_Exception if default value was not set as string, integer or double
	 *
	 * @example Set_DefaultOption('example'); to set that default value (marked with different style or so) will be "example"
	 */
	public function Set_DefaultOption($Value)
	{
		try
		{
			if(empty($Value))
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
			if(!in_array(gettype($Value), MarC::Show_Options_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Value), MarC::Show_Options_Scalars());
		}
		
		$this -> DefaultOption = $Value;
	}
	
	/**
	 * enables using of elements in order set in constructor in all code;
	 * unusable for elements selecrt, optgroup, option - because it may lead to very unexpected results
	 */
	public function Set_EnableSimpleOrder()
	{
		$this -> $Enable_SimpleOrder = TRUE;
	}
	
	/**
	 * sets content of drop down menu
	 *
	 * @param array $Content
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if content was not set as array
	 *
	 * @example Set_Content( array('a', 'b', 'c') );
	 * @example Set_Content( array('a' => '1', 'b' => 2, 'c' => 3) );
	 */
	public function Set_Content($Content)
	{
		try
		{
			if(!empty($Content) && !is_array($Content))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Content), 'array');
		}
		
		/*
		 * checks if content array has only two dimensions
		 */
		if(is_array($Content))
		{
			$this -> Check_Content($Content, 1);
		}
		
		$this -> Content = (!empty($Content)) ? $Content : array();
	}
	
	/**
	 * sets new main option (option that is displayed in the front of rest of content)
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if content was not prepared (at least empty)
	 * @throws MarC_Exception if name or value was not set as string, integer or double
	 *
	 * @example Set_NewMainOption('example', 'code'); to set that the first option in menu will be "code" with value "example"
	 */
	public function Set_NewMainOption($Name, $Value)
	{
		try
		{
			if(!is_array($this -> Content))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_SEC_FNC_DMDORDER);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, "Set_Content");
		}
		
		try
		{
			if(empty($Name) && $Name != 0)
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
			if(empty($Value) && $Value != 0)
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
			if(!in_array(gettype($Name), MarC::Show_Options_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Name), MarC::Show_Options_Scalars());
		}
		
		try
		{
			if(!in_array(gettype($Value), MarC::Show_Options_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), gettype($Value), MarC::Show_Options_Scalars());
		}
		
		/*
		 * adds new main option into empty array;
		 * adds new main option into filled array
		 */
		if(empty($this -> Content))
		{
			$Names = array($Name);
			$Values = array($Value);
		}
		else
		{
			$Names = array_merge( (array) $Name, array_keys($this -> Content));
			$Values = array_merge( (array) $Value, array_values($this -> Content));
		}
			
		$this -> Content = array_combine($Names, $Values);
	}
	
	/**
	 * sets styles for the lowest level element
	 *
	 * @param integer $Order
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order was not set
	 * @throws MarC_Exception if order was not set as integer
	 * @throws MarC_Exception if order was not set greater than or equal to zero
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_BottomLevelStyles(1, 'font-size', '2em'); the second option (without regard where it is) will get style "font-style" with value "2em", but in select dropdown it probably will not happen
	 */
	public function Set_SubLevelStyles($Order, $Name, $Value="")
	{
		try
		{
			if(empty($Order) && $Order != 0)
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
			if(!is_integer($Order))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Order), 'integer');
		}
		
		try
		{
			if($Order < 0)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_LOWNUMBER1);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 0);
		}
		
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1));
		}
		
		$this -> Set_SelectedElementStyles($Order, $this -> Elements['sub']['set'], $Name, $Value);
	}
	
	/**
	 * sets attributes for the lowest level element
	 *
	 * @param integer $Order
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order was not set
	 * @throws MarC_Exception if order was not set as integer
	 * @throws MarC_Exception if order was not set greater than or equal to zero
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_BottomLevelAttributes(2, 'id', 'example');
	 */
	public function Set_SubLevelAttributes($Order, $Name, $Value="")
	{
		try
		{
			if(empty($Order) && $Order != 0)
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
			if(!is_integer($Order))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Order), 'integer');
		}
	
		try
		{
			if($Order < 0)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_LOWNUMBER1);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 0);
		}
	
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1));
		}
	
		$this -> Set_SelectedElementAttributes($Order, $this -> Elements['sub']['set'], $Name, $Value);
	}
	
	/**
	 * sets styles for middle level element
	 *
	 * @param integer $Order
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order was not set
	 * @throws MarC_Exception if order was not set as integer
	 * @throws MarC_Exception if order was not set greater than or equal to zero
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_MiddleLevelStyles(3, 'color', '#ABCDEF');
	 */
	public function Set_MiddleLevelStyles($Order, $Name, $Value="")
	{
		try
		{
			if(empty($Order) && $Order != 0)
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
			if(!is_integer($Order))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Order), 'integer');
		}
	
		try
		{
			if($Order < 0)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_LOWNUMBER1);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 0);
		}
	
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1));
		}
	
		$this -> Set_SelectedElementStyles($Order, $this -> Elements['mid']['set'], $Name, $Value);
	}
	
	/**
	 * sets attributes for middle level element
	 *
	 * @param integer $Order
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order was not set
	 * @throws MarC_Exception if order was not set as integer
	 * @throws MarC_Exception if order was not set greater than or equal to zero
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_MiddleLevelAttributes(0, 'name', 'example');
	 */
	public function Set_MiddleLevelAttributes($Order, $Name, $Value="")
	{
		try
		{
			if(empty($Order) && $Order != 0)
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
			if(!is_integer($Order))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Order), 'integer');
		}
	
		try
		{
			if($Order < 0)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_LOWNUMBER1);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 0);
		}
	
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1));
		}
	
		$this -> Set_SelectedElementAttributes($Order, $this -> Elements['mid']['set'], $Name, $Value);
	}
	
	/**
	 * sets styles for top level element
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_TopLevelStyles('background-color', '#FEDCBA');
	 */
	public function Set_TopLevelStyles($Name, $Value="")
	{
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
	
		$this -> Set_AllElementsStyles($this -> Elements['top']['set'], $Name, $Value);
	}
	
	/**
	 * sets styles for top level element
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_TopLevelAttributes('id', 'example');
	 */
	public function Set_TopLevelAttributes($Name, $Value="")
	{
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
	
		$this -> Set_AllElementsAttributes($this -> Elements['top']['set'], $Name, $Value);
	}
	
	/**
	 * sets attribute that will be used like value in option
	 *
	 * @param string $Attribute name of attribute
	 *
	 * @example Set_SubLevelContentAttribute('value');
	 */
	public function Set_SubLevelContentAttribute($Attribute)
	{
		try
		{
			if(empty($Attribute))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}

		$this -> SpecialAttributes['sub'] = $Attribute;
	}
	
	/**
	 * sets attribute that will be used like label in optgroup
	 *
	 * @param string $Attribute name of attribute
	 *
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_MiddleLevelContentAttribute('label');
	 */
	public function Set_MiddleLevelContentAttribute($Attribute)
	{
		try
		{
			if(empty($Attribute))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
		
		$this -> SpecialAttributes['mid'] = $Attribute;
	}
	
	/**
	 * sets attribute that will be used like selected in option or checked in input
	 *
	 * @param string $Attribute
	 *
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_ChoiceAttribute('selected');
	 */
	public function Set_ChoiceAttribute($Attribute)
	{
		try
		{
			if(empty($Attribute))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}

		$this -> SpecialAttributes['chc'] = $Attribute;
	}
	
	/**
	 * sets separator of values of attributes of top level
	 *
	 * @param string $Attribute
	 * @param string $Separator
	 *
	 * @throws MarC_Exception if attribute name was not set
	 * @throws MarC_Exception if separator was not set
	 *
	 * @example Set_TopLevelValuesSeparator('class', ',');
	 */
	public function Set_TopLevelValuesSeparator($Attribute, $Separator=MarC::MARC_OPTION_SPC)
	{
		try
		{
			if(empty($Attribute))
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
			if(in_array($Separator, MarC::Show_Options_ValuesSeparation()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::Show_Options_ValuesSeparation());
		}
		
		$this -> Set_SelectedValuesSeparators($this -> Elements['top']['set'], $Attribute, $Separator);
	}
	
	/**
	 * sets separator of values of attributes of middle level
	 *
	 * @param string $Attribute
	 * @param string $Separator
	 *
	 * @throws MarC_Exception if attribute name was not set
	 * @throws MarC_Exception if separator was not set
	 *
	 * @example Set_MiddleLevelValuesSeparator('class', ',');
	 */
	public function Set_MiddleLevelValuesSeparator($Attribute, $Separator=MarC::MARC_OPTION_SPC)
	{
		try
		{
			if(empty($Attribute))
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
			if(in_array($Separator, MarC::Show_Options_ValuesSeparation()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::Show_Options_ValuesSeparation());
		}
	
		$this -> Set_SelectedValuesSeparators($this -> Elements['mid']['set'], $Attribute, $Separator);
	}
	
	/**
	 * sets separator of values of attributes of bottom level
	 *
	 * @param string $Attribute
	 * @param string $Separator
	 *
	 * @throws MarC_Exception if attribute name was not set
	 * @throws MarC_Exception if separator was not set
	 *
	 * @example Set_MiddleLevelValuesSeparator('class', ',');
	 */
	public function Set_SubLevelValuesSeparator($Attribute, $Separator=MarC::MARC_OPTION_SPC)
	{
		try
		{
			if(empty($Attribute))
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
			if(in_array($Separator, MarC::Show_Options_ValuesSeparation()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::Show_Options_ValuesSeparation());
		}
	
		$this -> Set_SelectedValuesSeparators($this -> Elements['sub']['set'], $Attribute, $Separator);
	}

	/**
	 * checks correct setting of content
	 *
	 * @param array $Content
	 * @param integer $Level
	 * @param mixed $Item
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if content was not set
	 * @throws MarC_Exception if level was not set
	 * @throws MarC_Exception if array has more than two dimensions
	 */
	private function Check_Content($Content, $Level, $Item="")
	{
		/*
		 * higher number is not neccessary;
		 * 1 = basic dimension (only select > option)
		 * 2 = advanced dimension (select > option, select > optgroup > option)
		 */
		$MaxLevel = 2;
		
		try
		{
			if(empty($Content) && $Content != 0)
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
			if(empty($Level) && $Level != 0)
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
			if(!is_array($Content) && $Level < $MaxLevel)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Content), 'array');
		}
		
		/*
		 * content has to be empty or array;
		 * empty content is not checked;
		 */
		if(is_array($Content))
		{
			foreach($Content as $Key => $Value)
			{
				try
				{
					/*
					 * array may have only two dimensions;
					 * the first dimension is used for options and optgroups;
					 * the second dimension is used for options inside optgroups
					 */
					if(is_array($Value) && $Level == $MaxLevel)
					{
						throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_WRONGARRDMN);
					}
					else
					{
						$this -> Check_Content($Value, $Level+1, $Key);
					}
				}
				catch(MarC_Exception $Exception)
				{
					$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_VariableNameAsText($Content), 2);
				}
			}
		}
	}
	
	/**
	 * prepares set content for final conversion into demanded code
	 *
	 * @return void
	 */
	private function Convert_PrepareContent()
	{
		$New = array();
		$Temp = array();
		$NoValue = MarC::MARC_OPTION_NOTUSE.'_';
		
		foreach($this -> Content as $MidLevel => $SubLevel)
		{
			if(is_array($SubLevel))
			{
				$New[$MidLevel] = $SubLevel;
			}
			else
			{
				/*
				 * support string
				 */
				$NoValue .= empty($MidLevel) ? strlen($SubLevel) : strlen($MidLevel);
				
				if(next($this -> Content) === FALSE || is_array(next($this -> Content)))
				{
					$Temp[$MidLevel] = $SubLevel;
				}
				else
				{
					$Temp[$MidLevel] = $SubLevel;
					$New[$NoValue] = $Temp;
					$Temp = array();
				}
			}
		}
		
		$this -> Content = $New;
	}
	
	/**
	 * assembles without default option
	 *
	 * @return void
	 */
	private function Get_AssembledCode_WithoutDefaultOption()
	{
		$this -> LocalCode = array();

		/*
		 * creation of empty select menu
		 */
		if(empty($this -> Content))
		{
			/*
			 * part 1
			 */
			$VMaX = new SimpleAssembler($this -> Elements['top']['main'], $this -> Elements['mid']['main']);
			
			/*
			 * part 2;
			 * sets separator of attribute values
			 */
			if(isset($this -> ValuesSeparators_Selected[$this -> Elements['top']['set']]))
			{
				foreach($this -> ValuesSeparators_Selected[$this -> Elements['top']['set']] as $Attribute => $Separator)
				{
					$VMaX -> Set_ValuesSeparator($Attribute, $Separator);
				}
			}
			
			/*
			 * part 3;
			 * sets styles for top level
			 */
			if(isset($this -> ElementStyles_Global[$this -> Elements['top']['set']]))
			{
				foreach($this -> ElementStyles_Global[$this -> Elements['top']['set']] as $Name => $Value)
				{
					$VMaX -> Set_TopLevelStyles($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets attributes for top level
			 */
			if(isset($this -> ElementAttributes_Global[$this -> Elements['top']['set']]))
			{
				foreach($this -> ElementAttributes_Global[$this -> Elements['top']['set']] as $Name => $Value)
				{
					$VMaX -> Set_TopLevelAttributes($Name, $Value);
				}
			}
			
			/*
			 * part 5;
			 * creates only options;
			 * sets empty content;
			 * sets way how result will be exported;
			 * code export
			 */
			$VMaX -> Set_DisableTopLevel();
			$VMaX -> Set_Content();
			$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
			$this -> LocalCode[] = $VMaX -> Execute();
		}
		/*
		 * creation of filled select menu
		 */
		else
		{
			/*
			 * help variables;
			 * used for correct attachment of styles and attributes to options and optgroups;
			 * options and optgroups are counted as they go in code (the first option has 0, the second 1 ... and so on without regard if it is inside optgroup or not)
			 */
			$OrderSubLevel = 0;
			$OrderMidLevel = 0;
			
			foreach($this -> Content as $TopLevel => $MidLevel)
			{
				/*
				 * creation of optgroup part of select menu
				 */
				if(!preg_match('/^'.MarC::MARC_OPTION_NOTUSE.'_/', $TopLevel) )
				{
					/*
					 * part 1;
					 * sets how content will be handled (key will be value of attribute value, value will be text wrapped in option);
					 * setting has not effect on optgroup's attribute label
					 */
					$VMaX = new SimpleAssembler($this -> Elements['mid']['main'], $this -> Elements['sub']['main']);
					$VMaX -> Set_Comment($this -> Elements['mid']['main'].' start', UniCAT::UNICAT_OPTION_ABOVE);
					$VMaX -> Set_Comment($this -> Elements['mid']['main'].' end', UniCAT::UNICAT_OPTION_BELOW);
					
					if(isset($this -> SpecialAttributes['sub']))
					{
						$VMaX -> Set_ContentUsage(MarC::MARC_OPTION_ATTRVAL, MarC::MARC_OPTION_ELMTTEXT, $this -> SpecialAttributes['sub']);
					}
					
					/*
					 * part 2;
					 * sets content
					 */
					foreach($MidLevel as $Key => $Value)
					{
						$VMaX -> Set_Content($Key, $Value);
					}
					
					/*
					 * part 3;
					 * sets separator of values of middle level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_TopLevelValuesSeparator($Attribute, $Separator);
						}
					}
					
					/*
					 * part 4;
					 * sets separator of values of sub level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_SubLevelValuesSeparator($Attribute, $Separator);
						}
					}

					/*
					 * part 5;
					 * sets
					 */
					if(isset($this -> SpecialAttributes['mid']))
					{
						$VMaX -> Set_TopLevelAttributes($this -> SpecialAttributes['mid'], $TopLevel);
					}

					/*
					 * part 6;
					 * sets styles for optgroup
					 */
					if(isset($this -> ElementStyles_Selected[$this -> Elements['mid']['set']][$OrderMidLevel]))
					{
						foreach($this -> ElementStyles_Selected[$this -> Elements['mid']['set']][$OrderMidLevel] as $Name => $Value)
						{
							$VMaX -> Set_TopLevelStyles($Name, $Value);
						}
					}

					/*
					 * part 7;
					 * sets attributes for optgroup
					 */
					if(isset($this -> ElementAttributes_Selected[$this -> Elements['mid']['set']][$OrderMidLevel]))
					{
						foreach($this -> ElementAttributes_Selected[$this -> Elements['mid']['set']][$OrderMidLevel] as $Name => $Value)
						{
							$VMaX -> Set_TopLevelAttributes($Name, $Value);
						}
					}
					
					/*
					 * part 8;
					 * sets styles for options;
					 * sets attributes for options;
					 */
					foreach($MidLevel as $Key => $Value)
					{
						if(isset($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelStyles($OrderOptions, $Name, $Value);
							}
						}
						
						if(isset($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelAttributes($OrderOptions, $Name, $Value);
							}
						}
						
						$OrderSubLevel++;
					}

					/*
					 * part 9;
					 * sets way how result will be exported;
					 * code export
					 */
					if($TopLevel == array_keys($this -> Content)[count(array_keys($this -> Content))-1])
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
						$this -> LocalCode[] = $VMaX -> Execute();
					}
					else
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
						$VMaX -> Execute();
					}
				}
				/*
				 * creation of options part of select menu
				 */
				else
				{
					/*
					 * part 1;
					 * sets how content will be handled (key will be value of attribute value, value will be text wrapped in option);
					 * setting has not effect on optgroup's attribute label
					 */
					$VMaX = new SimpleAssembler('', ($this -> Enable_SimpleOrder == FALSE ? $this -> Elements['sub']['main'] : $this -> Elements['mid']['main'] ) );
					$VMaX -> Set_Comment(($this -> Enable_SimpleOrder == FALSE ? $this -> Elements['sub']['main'] : $this -> Elements['mid']['main'] ).' start', UniCAT::UNICAT_OPTION_ABOVE);
					$VMaX -> Set_Comment(($this -> Enable_SimpleOrder == FALSE ? $this -> Elements['sub']['main'] : $this -> Elements['mid']['main'] ).' end', UniCAT::UNICAT_OPTION_BELOW);
					
					if(isset($this -> SpecialAttributes['sub']))
					{
						$VMaX -> Set_ContentUsage(MarC::MARC_OPTION_ATTRVAL, MarC::MARC_OPTION_ELMTTEXT, $this -> SpecialAttributes['sub']);
					}
					
					/*
					 * part 2;
					 * sets content
					 */
					foreach($MidLevel as $Key => $Value)
					{
						$VMaX -> Set_Content($Key, $Value);
					}
					
					/*
					 * part 3;
					 * sets separator of values of sub level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_TopLevelValuesSeparator($Attribute, $Separator);
						}
					}
					
					/*
					 * part 4;
					 * sets separator of values of sub level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_SubLevelValuesSeparator($Attribute, $Separator);
						}
					}
					
					/*
					 * part 5;
					 * sets styles for options;
					 * sets attributes for options;
					 */
					foreach($MidLevel as $Key => $Value)
					{
						if(isset($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelStyles($OrderOptions, $Name, $Value);
							}
						}
					
						if(isset($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelAttributes($OrderOptions, $Name, $Value);
							}
						}
					
						$OrderSubLevel++;
					}
					
					/*
					 * part 5;
					 * sets how code will be exported;
					 * set text wrapped in option element;
					 * code export
					 */
					if($TopLevel == array_keys($this -> Content)[count(array_keys($this -> Content))-1])
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
						$this -> LocalCode[] = $VMaX -> Execute();
					}
					else
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
						$VMaX -> Execute();
					}
					
					$OrderSubLevel++;
				}
			}
		}
	}
	
	/**
	 * assembles with default option
	 *
	 * @return void
	 *
	 * @throws nothing
	 */
	private function Get_AssembledCode_WithDefaultOption()
	{
		$this -> LocalCode = array();

		/*
		 * creation of empty select menu
		 */
		if(empty($this -> Content))
		{
			/*
			 * part 1
			 */
			$VMaX = new SimpleAssembler('', $this -> Elements['mid']['main']);
				
			/*
			 * part 2;
			 * sets separator of attribute values
			*/
			if(isset($this -> ValuesSeparators_Selected[$this -> Elements['top']['set']]))
			{
				foreach($this -> ValuesSeparators_Selected[$this -> Elements['top']['set']] as $Attribute => $Separator)
				{
					$VMaX -> Set_ValuesSeparator($Attribute, $Separator);
				}
			}
				
			/*
			 * part 3;
			 * sets styles for top level
			 */
			if(isset($this -> ElementStyles_Global[$this -> Elements['top']['set']]))
			{
				foreach($this -> ElementStyles_Global[$this -> Elements['top']['set']] as $Name => $Value)
				{
					$VMaX -> Set_TopLevelStyles($Name, $Value);
				}
			}
				
			/*
			 * part 4;
			 * sets attributes for top level
			 */
			if(isset($this -> ElementAttributes_Global[$this -> Elements['top']['set']]))
			{
				foreach($this -> ElementAttributes_Global[$this -> Elements['top']['set']] as $Name => $Value)
				{
					$VMaX -> Set_TopLevelAttributes($Name, $Value);
				}
			}
				
			/*
			 * part 5;
			 * creates only options;
			 * sets empty content;
			 * sets way how result will be exported;
			 * code export
			 */
			$VMaX -> Set_Content();
			$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
			$this -> LocalCode = $VMaX -> Execute();
		}
		/*
		 * creation of filled select menu
		 */
		else
		{
			/*
			 * help variables;
			 * used for correct attachment of styles and attributes to options and optgroups;
			 * options and optgroups are counted as they go in code (the first option has 0, the second 1 ... and so on without regard if it is inside optgroup or not)
			 */
			$OrderSubLevel = 0;
			$OrderMidLevel = 0;
				
			foreach($this -> Content as $TopLevel => $MidLevel)
			{
				/*
				 * creation of optgroup part of select menu
				 */
				if(!preg_match('/^'.MarC::MARC_OPTION_NOTUSE.'_/', $TopLevel) )
				{
					/*
					 * part 1;
					 * sets how content will be handled (key will be value of attribute value, value will be text wrapped in option);
					 * setting has not effect on optgroup's attribute label
					 */
					$VMaX = new SimpleAssembler($this -> Elements['mid']['main'], $this -> Elements['sub']['main']);
					$VMaX -> Set_Comment($this -> Elements['mid']['main'].' start', UniCAT::UNICAT_OPTION_ABOVE);
					$VMaX -> Set_Comment($this -> Elements['mid']['main'].' end', UniCAT::UNICAT_OPTION_BELOW);
						
					if(isset($this -> SpecialAttributes['sub']))
					{
						$VMaX -> Set_ContentUsage(MarC::MARC_OPTION_ATTRVAL, MarC::MARC_OPTION_ELMTTEXT, $this -> SpecialAttributes['sub']);
					}
						
					/*
					 * part 2;
					 * sets content
					 */
					foreach($MidLevel as $Key => $Value)
					{
						$VMaX -> Set_Content($Key, $Value);
					}
						
					/*
					 * part 3;
					 * searching for selected value in optgroup part of select menu
					 */
					$Coords = array_search($this -> DefaultOption, array_values($MidLevel));
						
					/*
					 * part 4;
					 * sets separator of values of middle level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_TopLevelValuesSeparator($Attribute, $Separator);
						}
					}
						
					/*
					 * part 5;
					 * sets separator of values of sub level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_SubLevelValuesSeparator($Attribute, $Separator);
						}
					}
	
					/*
					 * part 6;
					 * sets
					 */
					if(isset($this -> SpecialAttributes['mid']))
					{
						$VMaX -> Set_TopLevelAttributes($this -> SpecialAttributes['mid'], $TopLevel);
					}
	
					/*
					 * part 7;
					 * sets styles for optgroup
					 */
					if(isset($this -> ElementStyles_Selected[$this -> Elements['mid']['set']][$OrderMidLevel]))
					{
						foreach($this -> ElementStyles_Selected[$this -> Elements['mid']['set']][$OrderMidLevel] as $Name => $Value)
						{
							$VMaX -> Set_TopLevelStyles($Name, $Value);
						}
					}
	
					/*
					 * part 8;
					 * sets attributes for optgroup
					 */
					if(isset($this -> ElementAttributes_Selected[$this -> Elements['mid']['set']][$OrderMidLevel]))
					{
						foreach($this -> ElementAttributes_Selected[$this -> Elements['mid']['set']][$OrderMidLevel] as $Name => $Value)
						{
							$VMaX -> Set_TopLevelAttributes($Name, $Value);
						}
					}
						
					/*
					 * part 9;
					 * sets styles for options;
					 * sets attributes for options;
					 */
					foreach($MidLevel as $Key => $Value)
					{
						if(isset($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelStyles($OrderOptions, $Name, $Value);
							}
						}
	
						if(isset($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelAttributes($OrderOptions, $Name, $Value);
							}
						}
	
						$OrderSubLevel++;
					}
					
					/*
					 * part 10;
					 * sets attribute selected to option of default value
					 */
					if($Coords !== FALSE)
					{
						$VMaX -> Set_SubLevelAttributes(array_search($this -> DefaultOption, array_values($MidLevel)), $this -> SpecialAttributes['chc'], $this -> SpecialAttributes['chc']);
					}
	
					/*
					 * part 11;
					 * sets way how result will be exported;
					 * code export
					 */
					if($TopLevel == array_keys($this -> Content)[count(array_keys($this -> Content))-1])
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
						$this -> LocalCode = $VMaX -> Execute();
					}
					else
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
						$VMaX -> Execute();
					}
				}
				/*
				 * creation of options part of select menu
				 */
				else
				{
					/*
					 * part 1;
					 * sets how content will be handled (key will be value of attribute value, value will be text wrapped in option);
					 * setting has not effect on optgroup's attribute label
					 */
					$VMaX = new SimpleAssembler('', ($this -> Enable_SimpleOrder == FALSE ? $this -> Elements['sub']['main'] : $this -> Elements['mid']['main'] ) );
					$VMaX -> Set_Comment(($this -> Enable_SimpleOrder == FALSE ? $this -> Elements['sub']['main'] : $this -> Elements['mid']['main'] ).' start', UniCAT::UNICAT_OPTION_ABOVE);
					$VMaX -> Set_Comment(($this -> Enable_SimpleOrder == FALSE ? $this -> Elements['sub']['main'] : $this -> Elements['mid']['main'] ).' end', UniCAT::UNICAT_OPTION_BELOW);
						
					if(isset($this -> SpecialAttributes['sub']))
					{
						$VMaX -> Set_ContentUsage(MarC::MARC_OPTION_ATTRIBUTEVALUE, MarC::MARC_OPTION_ELEMENTTEXT, $this -> SpecialAttributes['sub']);
					}
						
					/*
					 * part 2;
					 * sets content
					*/
					foreach($MidLevel as $Key => $Value)
					{
						$VMaX -> Set_Content($Key, $Value);
					}
					
					/*
					 * part 3;
					 * searching for selected value in optgroup part of select menu
					 */
					$Coords = array_search($this -> DefaultOption, array_values($MidLevel));
						
					/*
					 * part 4;
					 * sets separator of values of sub level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['mid']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_TopLevelValuesSeparator($Attribute, $Separator);
						}
					}
						
					/*
					 * part 5;
					 * sets separator of values of sub level attributes
					 */
					if(isset($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']]))
					{
						foreach($this -> ValuesSeparators_Selected[$this -> Elements['sub']['set']] as $Attribute => $Separator)
						{
							$VMaX -> Set_SubLevelValuesSeparator($Attribute, $Separator);
						}
					}
						
					/*
					 * part 6;
					 * sets styles for options;
					 * sets attributes for options;
					 */
					foreach($MidLevel as $Key => $Value)
					{
						if(isset($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelStyles($OrderSubLevel, $Name, $Value);
							}
						}
							
						if(isset($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel]))
						{
							foreach($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$OrderSubLevel] as $Name => $Value)
							{
								$VMaX -> Set_SubLevelAttributes($OrderSubLevel, $Name, $Value);
							}
						}
							
						$OrderSubLevel++;
					}
					
					/*
					 * part 7;
					 * sets attribute selected to option of default value
					 */
					if($Coords !== FALSE)
					{
						$VMaX -> Set_SubLevelAttributes(array_search($this -> DefaultOption, array_values($MidLevel)), $this -> SpecialAttributes['chc'], $this -> SpecialAttributes['chc']);
					}
						
					/*
					 * part 8;
					 * sets how code will be exported;
					 * set text wrapped in option element;
					 * code export
					 */
					if($TopLevel == array_keys($this -> Content)[count(array_keys($this -> Content))-1])
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_STEP);
						$this -> LocalCode[] = $VMaX -> Execute();
					}
					else
					{
						$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_GOON);
						$VMaX -> Execute();
					}
						
					$OrderSubLevel++;
				}
			}
		}
	}
	
	/**
	 * assembling of code
	 *
	 * @return string|void
	 *
	 * @throws MarC_Exception if mid-level content attribute was not set
	 * @throws MarC_Exception if sub-level content attribute was not set
	 */
	public function Execute()
	{
		/*
		 * prepares content array into needed shape
		 */
		$this -> Convert_PrepareContent();
		
		/*
		 * decision if option with default value will be marked with choice attribute or not
		 */
		if($this -> DefaultOption == FALSE)
		{
			$this -> Get_AssembledCode_WithoutDefaultOption();
		}
		else
		{
			try
			{
				if(!isset($this -> SpecialAttributes['chc']))
				{
					throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_ARRKDMDFUNCTION2);
				}
			}
			catch(MarC_Exception $Exception)
			{
				$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_VariableNameAsText($this -> SpecialAttributes), 'chc', 'Set_ChoiceAttribute');
			}
			
			$this -> Get_AssembledCode_WithDefaultOption();
		}
		
		/*
		 * part 1
		 */
		$VMaX = new CodeGenerator($this -> Elements['top']['main']);
		$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_SKIP);
		
		/*
		 * part 2;
		 * sets separator of attribute values
		 */
		if(isset($this -> ValuesSeparators_Selected[$this -> Elements['top']['set']]))
		{
			foreach($this -> ValuesSeparators_Selected[$this -> Elements['top']['set']] as $Attribute => $Separator)
			{
				$VMaX -> Set_ValuesSeparator($Attribute, $Separator);
			}
		}
		
		/*
		 * part 3;
		 * sets styles
		 */
		if(isset($this -> ElementStyles_Global[$this -> Elements['top']['set']]))
		{
			foreach($this -> ElementStyles_Global[$this -> Elements['top']['set']] as $Name => $Value)
			{
				$VMaX -> Set_Style($Name, $Value);
			}
		}
		
		/*
		 * part 4;
		 * sets attributes
		 */
		if(isset($this -> ElementAttributes_Global[$this -> Elements['top']['set']]))
		{
			foreach($this -> ElementAttributes_Global[$this -> Elements['top']['set']] as $Name => $Value)
			{
				$VMaX -> Set_Attribute($Name, $Value);
			}
		}
		
		/*
		 * part 5;
		 * sets text wrapped in select element
		 */
		if(!empty($this -> LocalCode))
		{
			foreach($this -> LocalCode as $Part)
			{
				$VMaX -> Set_Text($Part);
			}
		}
		else
		{
			$VMaX -> Set_Text();
		}
		
		/*
		 * part 6;
		 * sets how code will be exported
		 */
		$this -> LocalCode = $VMaX -> Execute();

		/*
		 * sets how code will be exported;
		 * final exporting of generated code
		 */
		MarC::Set_ExportWay(static::$ExportWay);
		MarC::Add_Comments($this -> LocalCode, static::$Comments);
		return MarC::Convert_Code($this -> LocalCode, __CLASS__);
	}
}

?>