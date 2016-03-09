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
 * generation of row of the same elements
 */
class SimpleAssembler extends ElementListSetting implements I_MarC_Options_ContentUsage, I_MarC_Placeholders
{
	use ConditionalComments, StylesAttributesSetting, CodeExport, Comments;
	
	/**
	 * list of orders - used to check if all stylea and attributes were set correctly
	 *
	 * @var array
	 */
	protected $List_UsedOrders = array();
	/**
	 * disables wrapping code into top level element
	 */
	protected $Disable_TopLevel = FALSE;
	/**
	 * how content will be used - if it will be used as value of attribute or it will be enclosed by element
	 *
	 * @var array|boolean
	 */
	protected $ContentUsage = FALSE;
	/**
	 * elements used for creation of code
	 *
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
	 * sets elements for main (top) and secondary (sub) level;
	 * prevents sharing of content with previous instances
	 *
	 * @param string|array $TopElement
	 * @param string|array $SubElement
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if top element was not set
	 * @throws MarC_Exception if sub element was not set
	 * @throws MarC_Exception if both elements were set the same
	 *
	 * @example new SimpleAssembler('tr', 'td');
	 */
	public function __construct($TopElement, $SubElement)
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
			if(empty($SubElement))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
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
			if($this -> Check_ElementTreeValidity($SubElement))
			{
				$this -> Elements['sub']['main'] = $SubElement;
				$this -> Elements['sub']['set'] = self::MARC_PLACEHOLDER_SUBELMT;
			}
		}
		else
		{
			if($this -> Check_ElementTreeValidity($TopElement, $SubElement))
			{
				$this -> Elements['top']['main'] = $TopElement;
				$this -> Elements['top']['set'] = self::MARC_PLACEHOLDER_TOPELMT;
				$this -> Elements['sub']['main'] = $SubElement;
				$this -> Elements['sub']['set'] = self::MARC_PLACEHOLDER_SUBELMT;
			}
		}
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
		$this -> ContentUsage = array();
		$this -> List_UsedOrders = array();
		$this -> Disable_TopLevel = FALSE;
	}

	/**
	 * disables generation of top level element;
	 * useful for generation of page's <head>, where more elements (meta, title, script, link ...) are present;
	 * usage of this function may be accidentally avoided with using of empty argument (using argument as '')
	 */
	public function Set_DisableTopLevel()
	{
		$this -> Disable_TopLevel = TRUE;
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
	 *
	 * @example Set_Content('example');
	 */
	public function Set_Content($Item="")
	{
		$Item = func_get_args();
		
		try
		{
			if(!empty($Item) && count($Item) > 2)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_PRHBGTRARGS);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 2);
		}
		
		try
		{
			if(!empty($Item) && count($Item) == 1 && !in_array(gettype($Item[0]), MarC::Show_Options_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Item[0]), MarC::Show_Options_Scalars());
		}
		
		try
		{
			if(!empty($Item) && count($Item) == 2 && (!in_array(gettype($Item[0]), MarC::Show_Options_Scalars()) || !in_array(gettype($Item[1]), MarC::Show_Options_Scalars())))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), (in_array(gettype($Item[0]), MarC::Show_Options_Scalars()) ? gettype($Item[1]) : gettype($Item[0])), MarC::Show_Options_Scalars());
		}
		
		/*
		 * sets content to needed form of associative array
		 */
		if(empty($Item))
		{
			$this -> Content[] = $Item;
		}
		elseif(count($Item) == 1)
		{
			$this -> Content[] = $Item[0];
		}
		else
		{
			$this -> Content[$Item[0]] = $Item[1];
		}
	}
	
	/**
	 * sets if content will be used as text closed into any element or as value of any attribute
	 *
	 * @param string $Key
	 * @param string $Value
	 * @param string $Attribute
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if key and value were not set
	 * @throws MarC_Exception if key and value should be used as text wrapped by element
	 * @throws MarC_Exception if key or value should be used as value of any attribute and name of attribute was not set
	 *
	 * @example Set_ContentUsage('example', 'code', 'value');
	 */
	public function Set_ContentUsage($Key, $Value, $Attribute=NULL)
	{
		/*
		 * sets empty array;
		 * extracts possible names of attributes
		 */
		$this -> ContentUsage = array();
		$Attribute = array_slice(func_get_args(), 2);
		
		try
		{
			if(empty($Key))
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
			if(empty($Value))
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
			if(!is_string($Key))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, uniT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Key), 'string');
		}
		
		try
		{
			if(!is_string($Value))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, uniT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), gettype($Value), 'string');
		}
		
		try
		{
			if(!in_array($Key, MarC::Show_Options_ContentUsage()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), MarC::Show_Options_SimpleAssembler());
		}
		
		try
		{
			if(!in_array($Value, MarC::Show_Options_ContentUsage()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), MarC::Show_Options_SimpleAssembler());
		}
		
		try
		{
			if((($Key == MarC::MARC_OPTION_ATTRVAL) || ($Value == MarC::MARC_OPTION_ATTRVAL)) && empty($Attribute))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 2));
		}
		
		try
		{
			if((($Key == MarC::MARC_OPTION_ATTRVAL) && ($Value == MarC::MARC_OPTION_ATTRVAL)) && empty($Attribute))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 2));
		}
		
		try
		{
			if(($Key == MarC::MARC_OPTION_ELMTTEXT) && ($Value == MarC::MARC_OPTION_ELMTTEXT))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRMS, UniCAT::UNICAT_XCPT_SEC_PRMS_PRHBVALEQUAL);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
		
		/*
		 * sets basic usage of content
		 */
		$this -> ContentUsage['key'] = $Key;
		$this -> ContentUsage['value'] = $Value;
		
		/*
		 * sets name of attribute
		 */
		if(count($Attribute) == 1)
		{
			$this -> ContentUsage['keyattr'] = ($Key == MarC::MARC_OPTION_ATTRVAL) ? $Attribute[0] : FALSE;
			$this -> ContentUsage['valueattr'] = ($Value == MarC::MARC_OPTION_ATTRVAL) ? $Attribute[0] : FALSE;
		}
		/*
		 * sets names of attributes
		 */
		else
		{
			$this -> ContentUsage['keyattr'] = $Attribute[0];
			$this -> ContentUsage['valueattr'] = $Attribute[1];
		}
	}
	
	/**
	 * sets styles for top level
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_TopLevelStyles('border-style', 'dashed')
	 */
	public function Set_TopLevelStyles($Name, $Value="")
	{
		try
		{
			if(!empty($Name))
			{
				$this -> Set_SelectedElementStyles(0, $this -> Elements['top']['set'], $Name, $Value);
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch (MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
	}
	
	/**
	 * sets attributes for top level
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_TopLevelAttributes('name', 'example')
	 */
	public function Set_TopLevelAttributes($Name, $Value="")
	{
		try
		{
			if(!empty($Name))
			{
				$this -> Set_SelectedElementAttributes(0, $this -> Elements['top']['set'], $Name, $Value);
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch (MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
	}
	
	/**
	 * sets styles for sub level
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
	 * @example Set_SubLevelStyles(1, 'border-style', 'dashed')
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
	
		/*
		 * checks style name;
		 * sets attribute to chosen element;
		 * sets order to list of used orders
		 */
		if($this -> Check_StyleName($Name))
		{
			$this -> Set_SelectedElementStyles($Order, $this -> Elements['sub']['set'], $Name, $Value);
			$this -> Set_OrderToList($Order);
		}
	}
	
	/**
	 * sets attributes for sub level
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
	 * @example Set_SubLevelAttributes(0, 'onClick', 'example()')
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
	
		/*
		 * checks attribute name;
		 * sets attribute to chosen element;
		 * sets order to list of used orders
		 */
		if($this -> Check_AttributeName($Name))
		{
			$this -> Set_SelectedElementAttributes($Order, $this -> Elements['sub']['set'], $Name, $Value);
			$this -> Set_OrderToList($Order);
		}
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
	
		$this -> Set_SelectedValuesSeparators($this -> Elements['top']['main'], $Attribute, $Separator);
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
	 * @example Set_SubLevelValuesSeparator('class', ',');
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
	
		$this -> Set_SelectedValuesSeparators($this -> Elements['sub']['main'], $Attribute, $Separator);
	}
	
	/**
	 * assembling of one block of code of the same elements in row
	 *
	 * @return string|void
	 */
	public function Execute()
	{
		$this -> Check_Orders();
		
		/*
		 * sets value of attribute attached to be used if array keys are used as value of attributes;
		 * this is default statement
		 */
		if($this -> ContentUsage['keyattr'] != FALSE)
		{
			$Order = 0;
			foreach($this -> Content as $Key => $Value)
			{
				$this -> Set_SelectedElementAttributes($Order, $this -> Elements['sub']['set'], $this -> ContentUsage['keyattr'], $Key);
				$Order++;
			}
		}
		
		/*
		 * sets value of attribute attached to be used if array values are used as value of attributes
		*/
		if($this -> ContentUsage['valueattr'] != FALSE)
		{
			$Order = 0;
			foreach($this -> Content as $Key => $Value)
			{
				$this -> Set_SelectedElementAttributes($Order, $this -> Elements['sub']['set'], $this -> ContentUsage['valueattr'], $Value);
				$Order++;
			}
		}
		
		/*
		 * converts content into form that will be used;
		 * extracts array keys for future content
		 */
		if($this -> ContentUsage['key'] == MarC::MARC_OPTION_ELMTTEXT)
		{
			$this -> Content = array_keys($this -> Content);
		}
		
		/*
		 * converts content into form that will be used;
		 * extracts array values for future content
		 */
		if($this -> ContentUsage['value'] == MarC::MARC_OPTION_ELMTTEXT)
		{
			$this -> Content = array_values($this -> Content);
		}
		
		/*
		 * converts content into form that will be used;
		 * extracts array keys for future content - like if it was set manually
		 */
		if($this -> ContentUsage == FALSE)
		{
			$this -> Content = array_values($this -> Content);
		}
		
		/*
		 * generation of sub-level
		 */
		for($Order = 0; $Order < count($this -> Content); $Order++)
		{
			/*
			 * part 1;
			 * sets name of element
			 */
			$VMaX = new CodeGenerator($this -> Elements['sub']['main']);
			
			/*
			 * part 2;
			 * sets separator of attribute values
			 */
			if(isset($this -> ValuesSeparators_Selected[$this -> Elements['sub']['main']]))
			{
				foreach($this -> ValuesSeparators_Selected[$this -> Elements['sub']['main']] as $Attribute => $Separator)
				{
					$VMaX -> Set_ValuesSeparator($Attribute, $Separator);
				}
			}
			
			/*
			 * part 3;
			 * sets styles
			 */
			if(isset($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$Order]))
			{
				foreach($this -> ElementStyles_Selected[$this -> Elements['sub']['set']][$Order] AS $Name => $Value)
				{
					$VMaX -> Set_Style($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets attributes
			 */
			if(isset($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$Order]))
			{
				foreach($this -> ElementAttributes_Selected[$this -> Elements['sub']['set']][$Order] AS $Name => $Value)
				{
					$VMaX -> Set_Attribute($Name, $Value);
				}
			}
			
			/*
			 * part 5;
			 * sets text wrapped by element of sub-level;
			 * automatically detects empty elements
			 */
			if(self::$List_AvailableElements[$this -> Elements['sub']['main']]['Siblings'] != 'EMPTY')
			{
				$VMaX -> Set_Text((empty($this -> Content[$Order]) ? '' : $this -> Content[$Order]));
			}
			
			/*
			 * part 7 - if top-level element will not be used;
			 * sets way how code will be exported;
			 * sets styles for element of sub-level;
			 */
			if($this -> Disable_TopLevel == TRUE)
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

					/*
			 * sets way how code will be exported;
			 * exports code
			 */
					MarC::Set_ExportWay(static::$ExportWay);
					MarC::Add_ConditionalComment($this -> LocalCode, static::$ConditionalComments);
					MarC::Add_Comments($this -> LocalCode, static::$Comments);
					static::$ConditionalComments = FALSE;
					static::$Comments = FALSE;
					return MarC::Convert_Code($this -> LocalCode, __CLASS__);
				}
			}
			/*
			 * part 7 - if top-level element will be used;
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
			$VMaX = new CodeGenerator($this -> Elements['top']['main']);
			$VMaX -> Set_ExportWay(UniCAT::UNICAT_OPTION_SKIP);
			
			/*
			 * part 2;
			 * sets separator of attribute values
			 */
			if(isset($this -> ValuesSeparators_Selected[$this -> Elements['top']['main']]))
			{
				foreach($this -> ValuesSeparators_Selected[$this -> Elements['top']['main']] as $Attribute => $Separator)
				{
					$VMaX -> Set_ValuesSeparator($Attribute, $Separator);
				}
			}
			
			/*
			 * part 3;
			 * sets styles
			 */
			if(isset($this -> ElementStyles_Selected[$this -> Elements['top']['set']][0]))
			{
				foreach($this -> ElementStyles_Selected[$this -> Elements['top']['set']][0] AS $Name => $Value)
				{
					$VMaX -> Set_Style($Name, $Value);
				}
			}
			
			/*
			 * part 4;
			 * sets attributes
			 */
			if(isset($this -> ElementAttributes_Selected[$this -> Elements['top']['set']][0]))
			{
				foreach($this -> ElementAttributes_Selected[$this -> Elements['top']['set']][0] AS $Name => $Value)
				{
					$VMaX -> Set_Attribute($Name, $Value);
				}
			}
			
			/*
			 * part 5;
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
			MarC::Add_ConditionalComment($this -> LocalCode, static::$ConditionalComments);
			MarC::Add_Comments($this -> LocalCode, static::$Comments);
			static::$ConditionalComments = FALSE;
			static::$Comments = FALSE;
			return MarC::Convert_Code($this -> LocalCode, __CLASS__);
		}
	}
}

?>