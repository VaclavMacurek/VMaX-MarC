<?php

namespace MarC;

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
 * setting of styles and attributes for elements
 */
trait StylesAttributesSetting
{
	/**
	 * styles for all elements
	 *
	 * @static
	 * @var array
	 */
	protected $ElementStyles_Global = array();
	/**
	 * attributes for all elements
	 *
	 * @static
	 * @var array
	 */
	protected $ElementAttributes_Global = array();
	/**
	 * styles for selected element
	 *
	 * @static
	 * @var array
	 */
	protected $ElementStyles_Selected = array();
	/**
	 * attributes for selected element
	 *
	 * @static
	 * @var array
	 */
	protected $ElementAttributes_Selected = array();
	/**
	 * character used for separated of values in case of multi-values attributes without regard of element
	 *
	 * @var array
	 */
	protected $ValuesSeparators_Global = array();
	/**
	 * character used for separated of values in case of multi-values attributes with regard of element - but without regard of position of element
	 *
	 * @var array
	 */
	protected $ValuesSeparators_Selected = array();
	/**
	 * controls usage of attributes without values;
	 * useful for JavaScript features
	 *
	 * @var bool
	 */
	protected static $Enable_NoValueAttributes = FALSE;
	
	/**
	 * checks if name of style is correct
	 *
	 * @param string $Name
	 *
	 * @return boolean
	 *
	 * @throws MarC_Exception if style name was not set
	 * @throws MarC_Exception if stylename does not match pattern of style name
	 *
	 * @example Check_StyleName('font-family');
	 */
	protected function Check_StyleName($Name)
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
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_Parameters(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(preg_match(MarC::MARC_XPSN_STYLENAME, $Name))
			{
				return TRUE;
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_Parameters(__CLASS__, __FUNCTION__), $Name, MarC::MARC_XPSN_STYLENAME);
		}
	}
	
	/**
	 * checks if name of attribute is correct
	 *
	 * @param string $Name
	 *
	 * @return boolean
	 *
	 * @throws MarC_Exception if attribute name was not set
	 * @throws MarC_Exception if attribute name does not match pattern of attribute name
	 *
	 * @example Check_AttributeName('id');
	 */
	protected function Check_AttributeName($Name)
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
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_Parameters(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(preg_match(MarC::MARC_XPSN_ATTRIBUTENAME, $Name))
			{
				return TRUE;
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_Parameters(__CLASS__, __FUNCTION__), $Name, MarC::MARC_XPSN_ATTRIBUTENAME);
		}
	}
	
	/**
	 * checks if order of styled/attributed items was set correctly
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order (number index of appearance of used element in block) was not set
	 */
	protected function Check_Orders()
	{
		try
		{
			if(count($this -> List_UsedOrders) > count($this -> Content))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_PRHBGTRARRSIZE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_VariableNameAsText($this -> List_UsedOrders), count($this -> Content));
		}
	
		try
		{
			if(!empty($this -> List_UsedOrders) && max($this -> List_UsedOrders) > count($this -> Content)-1)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_PRHBGTRARRSIZE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), $Exception -> Get_VariableNameAsText($this -> List_UsedOrders), count($this -> Content));
		}
	}
	
	/**
	 * adds number of order to list of used ordwers - for purpose of control which orders were used
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order (number index of appearance of used element in block) was not set
	 * @throws MarC_Exception if order (number index of appearance of used element in block) was not set as integer
	 *
	 * @example Set_OrderToList(1);
	 */
	protected function Set_OrderToList($Order="")
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__));
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__), gettype($Order), 'integer');
		}
	
		/*
		 * avoids unneccessary multiple appearance of the same order;
		 * adds order to list
		 */
		if(!in_array($Order, $this -> List_UsedOrders))
		{
			$this -> List_UsedOrders[] = $Order;
		}
	}
	
	/**
	 * sets style for global setting of styles and attributes
	 *
	 * @param string $Element
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if element name was not set
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_AllElementsStyles('li', 'font-family', 'sans-serif');
	 */
	protected function Set_AllElementsStyles($Element, $Name, $Value="")
	{
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0]);
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1]);
		}
		
		/*
		 * sets styles;
		 * Element - element name;
		 * Name - style name;
		 * Value - style value
		 */
		$this -> ElementStyles_Global[$Element][$Name] = $Value;
	}
	
	/**
	 * sets attribute for global setting of styles and attributes
	 *
	 * @param string $Element
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if element name was not set
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_AllElementsAttributes('ul', 'id', 'example');
	 */
	protected function Set_AllElementsAttributes($Element, $Name, $Value="")
	{
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0]);
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1]);
		}
		
		/*
		 * sets attributes;
		 * Element - element name;
		 * Name - attribute name;
		 * Value - attribute value
		 */
		$this -> ElementAttributes_Global[$Element][$Name] = $Value;
	}
	
	/**
	 * sets style for selected setting of styles and attributes
	 *
	 * @param string $Position
	 * @param string $Element
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order (number index of appearance of used element in block) was not set
	 * @throws MarC_Exception if element name was not set
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_SelectedElementsStyles(0, 'li', 'color', '#FEDCBA');
	 */
	protected function Set_SelectedElementStyles($Order, $Element, $Name, $Value="")
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0]);
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0], gettype($Order), 'integer');
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0], 0);
		}
		
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1]);
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[2]);
		}
		
		/*
		 * sets styles;
		 * Element - element name;
		 * Order - number of position of element that will get style;
		 * Name - style name;
		 * Value - style value
		*/
		$this -> ElementStyles_Selected[$Element][$Order][$Name] = $Value;
	}
	
	/**
	 * sets attribute for selected setting of styles and attributes
	 *
	 * @param string $Position
	 * @param string $Element
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if order (number index of appearance of used element in block) was not set
	 * @throws MarC_Exception if element name was not set
	 * @throws MarC_Exception if attribute name was not set
	 *
	 * @example Set_SelectedElementsAttributes(0, 'div', 'id', 'example');
	 */
	protected function Set_SelectedElementAttributes($Order, $Element, $Name, $Value="")
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0]);
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0], gettype($Order), 'integer');
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0], 0);
		}
		
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1]);
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
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[2]);
		}
		
		/*
		 * sets attributes;
		 * Element - element name;
		 * Order - number of position of element that will get attribute;
		 * Name - attribute name;
		 * Value - attribute value
		 */
		$this -> ElementAttributes_Selected[$Element][$Order][$Name] = $Value;
	}
	
	/**
	 * enables usage of attributes without set values
	 *
	 * @return void
	 */
	public function Set_EnableNoValueAttributes()
	{
		self::$Enable_NoValueAttributes = TRUE;
	}
	
	/**
	 * sets characters used for separation of values for multivalue attributes;
	 * has to be used before function Set_Attribute
	 *
	 * @param string $Attribute
	 * @param string $Separator
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if attribute was not set
	 * @throws MarC_Exception if separator was set wrong
	 *
	 * @example Set_AllValuesSeparators('class', "\x20");
	 */
	protected function Set_AllValuesSeparators($Attribute="", $Separator=NULL)
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
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[0]);
		}
	
		try
		{
			if($Separator !== NULL && !in_array($Separator, MarC::Show_Options_ValuesSeparation()) )
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
			else
			{
				if($Separator !== NULL)
				{
					$this -> ValuesSeparators_Global[$Attribute] = $Separator;
				}
				else
				{
					$this -> ValuesSeparators_Global[$Attribute] = MarC::Show_Options_ValuesSeparation()[0];
				}
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1], MarC::Show_Options_ValuesSeparation());
		}
	}
	
	/**
	 * sets characters used for separation of values for multivalue attributes;
	 * has to be used before function Set_Attribute
	 *
	 * @param string $Element
	 * @param string $Attribute
	 * @param string $Separator
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if element was not set
	 * @throws MarC_Exception if attribute was not set
	 * @throws MarC_Exception if separator was set wrong
	 *
	 * @example Set_SelectedValuesSeparators('div', 'class');
	 * @example Set_SelectedValuesSeparators('div', 'class', "\x20");
	 */
	protected function Set_SelectedValuesSeparators($Element="", $Attribute="", $Separator=NULL)
	{
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1]);
		}
		
		try
		{
			if(empty($Attribute))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[1]);
		}
	
		try
		{
			if($Separator !== NULL && !in_array($Separator, MarC::Show_Options_ValuesSeparation()) )
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
			else
			{
				if($Separator !== NULL)
				{
					$this -> ValuesSeparators_Selected[$Element][$Attribute] = $Separator;
				}
				else
				{
					$this -> ValuesSeparators_Selected[$Element][$Attribute] = MarC::Show_Options_ValuesSeparation()[0];
				}
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_Parameters(__CLASS__, __FUNCTION__)[2], MarC::Show_Options_ValuesSeparation());
		}
	}
}

?>