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
 * @copyright 2013 - 2015, Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * generation of almost any markup code
 */
final class CodeGenerator extends ElementListSetting implements I_MarC_Texts_CodeGenerator, I_MarC_Options_InLineSetting, I_MarC_Placeholders
{
	use ConditionalComments, StylesAttributesSetting, CodeExport, Comments;
	
	/**
	 * element name
	 *
	 * @var string
	 */
	private $Element = FALSE;
	/**
	 * text inserted into element if possible
	 *
	 * @var array
	 */
	private $Text = array();
	/**
	 * list of used elements;
	 * useful for disabling of indention of chosen elements
	 *
	 * @var array
	 */
	private static $List_UsedElements = array();
	/**
	 * disables insertion of line-break inside element;
	 * useful for usage of textarea
	 *
	 * @var string
	 */
	private $Enable_OneLineElement = FALSE;
	/**
	 * disables insertion of line-break inside element;
	 * allows insertion of text also before or behind element
	 *
	 * @var string
	 */
	private $Enable_InLineElement = FALSE;
	/**
	 * disables text indent;
	 * useful in case of usage of textarea
	 *
	 * @static
	 * @var string|array
	 */
	private static $Disable_Indention;
	
	/**
	 * sets used element
	 *
	 * @param string $Element
	 *
	 * @throws MarC_Exception if element name was not set
	 *
	 * @example new CodeGenerator('a');
	 */
	public function __construct($Element)
	{
		/*
		 * disables multiple new lines and shortens code in that way
		 */
		MarC::Set_DisableMultipleNewLines();
		
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING);
			}
			else
			{
				/*
		 * checks element name - against expression and list of available elements
		 */
				if($this -> Check_ElementTreeValidity($Element))
				{
					self::$List_UsedElements[] = $Element;
					$this -> Element = $Element;
				}
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}

		if(is_array(self::$List_AvailableElements[$this -> Element]['Siblings']) && in_array('#PCDATA', self::$List_AvailableElements[$this -> Element]['Siblings']))
		{
			$this -> Enable_OneLineElement = TRUE;
		}
		elseif(!is_array(self::$List_AvailableElements[$this -> Element]['Siblings']) && self::$List_AvailableElements[$this -> Element]['Siblings'] == '#PCDATA')
		{
			$this -> Enable_OneLineElement = TRUE;
		}
	}
	
	/**
	 * empties member variables that are not static
	 */
	public function __destruct()
	{
		$this -> Element = FALSE;
		$this -> Text = array();
		$this -> Enable_OneLineElement = FALSE;
		$this -> Enable_InLineElement = FALSE;
	}

	/**
	 * enables creation of in-line element;
	 * allows insertion of text also to empty element
	 *
	 * @param string $Position position of text added to element represented by constant
	 *
	 * @throws MarC_Exception if position was set by unsupported wrong
	 */
	public function Set_EnableInLineElement($Position=MarC::MARC_OPTION_LEFT)
	{
		try
		{
			if(!in_array($Position, MarC::Show_Options_InlineSetting()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::Show_Options_InLineSetting());
		}

		$this -> Enable_InLineElement = $Position;
	}
	
	/**
	 * disables indention of code;
	 * only lines of chosen elements lost indention;
	 *
	 * @param string $Elements
	 *
	 * @return void
	 *
	 * @example Set_DisableIndention();
	 * @example Set_DisableIndention('textarea');
	 * @example Set_DisableIndention('textarea', 'form');
	 */
	public function Set_DisableIndention($Elements="")
	{
		$Elements = func_get_args();
		
		switch(count($Elements))
		{
			/*
			 * non-set indented element will add currently used element into list of elements that will have deleted indention;
			 * if this is used on top element (html or else), no line will be indented
			 */
			case 0:
				if(is_array(self::$Disable_Indention))
				{
					self::$Disable_Indention[] = $this -> Element;
				}
				else
				{
					settype(self::$Disable_Indention, "array");
					self::$Disable_Indention[] = $this -> Element;
				}
				break;
			/*
			 * one set element will disable indention for currently chosen element;
			 * empty value has the same effect as non-set value
			 */
			case 1:
				if(empty($Element))
				{
					$this -> Set_DisableIndention();
				}
				else
				{
					if($this -> Check_ElementTreeValidity($Element))
					{
						if(is_array(self::$Disable_Indention) )
						{
							self::$Disable_Indention[] = $Element;
						}
						else
						{
							settype(self::$Disable_Indention, "array");
							self::$Disable_Indention[] = $Element;
						}
					}
				}
				break;
			default:
				foreach($Elements as $Element)
				{
					$this -> Set_DisableIndention($Element);
				}
		}
	}
	
	/**
	 * add style to element
	 *
	 * @param string $Name name of style
	 * @param string $Value value of style
	 *
	 * @throws MarC_Exception if style name was not set
	 *
	 * @example Set_Style('font-family', 'sans-serif');
	 */
	public function Set_Style($Name, $Value="")
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
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}
		
		if($this -> Check_StyleName($Name))
		{
			$this -> Set_AllElementsStyles($this -> Element, $Name, $Value);
		}
	}
	
	/**
	 * add attributes to element
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if attribute name was not set
	 * @throws MarC_Exception if attribute value was not set (if attributes without value were not enabled)
	 * @throws MarC_Exception if attribute value was not set as string, integer or double
	 *
	 * @example Set_Attribute('id', 'example');
	 * @example Set_Attribute('points', array(110, 225, 254, 100) );
	 */
	public function Set_Attribute($Name, $Value="")
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

		/*
		 * attribute value may be empty;
		 * empty values for attributes MUST be enabled by using of separated function (Set_EnableNoValueAttributes)
		 */
		try
		{
			if((empty($Value) && $Value != 0) && self::$Enable_NoValueAttributes == FALSE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_MISSING, MarC::MARC_XCPT_XPLN_EMPTYATTR);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), 'Set_EnableNoValueAttributes');
		}
		
		if($this -> Check_AttributeName($Name))
		{
			if(self::$Enable_NoValueAttributes == TRUE)
			{
				$this -> Set_AllElementsAttributes($this -> Element, $Name, $Value);
			}
			else
			{
				try
				{
					if(!in_array(gettype($Value), MarC::Show_Options_Scalars()))
					{
						throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
					}
					else
					{
						if(in_array(gettype($Value), MarC::Show_Options_Scalars()))
						{
							$this -> Set_AllElementsAttributes($this -> Element, $Name, $Value);
						}
						else
						{
							if(key_exists($Name, $this -> ValuesSeparators_Global))
							{
								/*
								 * selected option for sticking of multiple values;
								 * if character was set for current attribute
								 */
								$this -> Set_AllElementsAttributes($this -> Element, $Name, implode($this -> ValuesSeparators_Global[$Name], $Value));
							}
							else
							{
								/*
								 * default option for sticking of multiple values;
								 * if character was not set for current attribute
								 */
								$this -> Set_AllElementsAttributes($this -> Element, $Name, implode(MarC::Show_Options_ValuesSeparation()[1], $Value));
							}
							
						}
					}
				}
				catch(MarC_Exception $Exception)
				{
					$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), gettype($Value), MarC::Show_Options_Scalars());
				}
			}
		}
	}
	
	/**
	 * sets separator of values of attributes;
	 * this function has to be in the front of functions for setting of attributes
	 *
	 * @param string $Attribute
	 * @param string $Separator
	 *
	 * @throws MarC_Exception if attribute name was not set
	 * @throws MarC_Exception if separator was not set
	 *
	 * @example Set_ValuesSeparator('class', ',');
	 */
	public function Set_ValuesSeparator($Attribute, $Separator=MarC::MARC_OPTION_SPC)
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
		
		$this -> Set_AllValuesSeparators($Attribute, $Separator);
	}
	
	/**
	 * set text into element;
	 * code generated by previous objects of class CodeGenerator is allowed
	 *
	 * @param string $Text
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if it was set and empty element was used without using of function Set_InLineElement
	 * @throws MarC_Exception if it was not set as string, integer or double
	 *
	 * @example Set_Text();
	 * @example Set_Text(1331);
	 * @example Set_Text('example');
	 * @example Set_Text($Example);
	 */
	public function Set_Text($Text="")
	{
		try
		{
			if(self::$List_AvailableElements[$this -> Element]['Siblings'] == 'EMPTY' && $this -> Enable_InLineElement == FALSE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_DMDFUNCTION2);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_VariableNameAsText($this -> Element), $this -> Element);
		}
		
		try
		{
			if(!in_array(gettype($Text), MarC::Show_Options_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Text), MarC::Show_Options_Scalars());
		}

		try
		{
			if(preg_match(MarC::MARC_XPSN_PSNELMT_GNRDCODE, $Text) && self::$List_AvailableElements[$this -> Element]['Siblings'] == MarC::MARC_OPTION_DATA)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_PRHBOPTION, MarC::MARC_XCPT_XPLN_DTDFILE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $Text);
		}

		if(preg_match(MarC::MARC_XPSN_PSNELMT_GNRDCODE, $Text))
		{
			$this -> Enable_OneLineElement = FALSE;
		}
		
		$this -> Text[] = $Text;
	}

	/**
	 * converts array of styles into text value of attribute style
	 *
	 * @param array $Styles
	 *
	 * @return string
	 */
	private function Convert_Styles($Styles)
	{
		$Text = array();

		foreach($Styles AS $Name => $Value)
		{
			if(!empty($Value))
			{
				$Text[] = sprintf(self::MARC_CODE_STYLES_ONE, $Name, $Value);
			}
		}

		return sprintf(self::MARC_CODE_STYLES_FULL, implode(" ", $Text) );
	}

	/**
	 * conversion of array of attributes to text
	 *
	 * @param array $Attributes
	 *
	 * @return string
	 */
	private function Convert_Attributes($Attributes)
	{
		$Text = array();

		foreach($Attributes AS $Name => $Value)
		{
			$Text[] = sprintf(self::MARC_CODE_ATTRIBUTES, $Name, $Value);
		}

		return implode(" ", $Text);
	}

	/**
	 * converts text in according with setting of in-line element and used element;
	 * adds or deletes items of array of text
	 *
	 * @return array
	 */
	private function Convert_PrepareText()
	{
		if(self::$List_AvailableElements[$this -> Element]['Siblings'] == MarC::MARC_OPTION_EMPTY)
		{
			switch($this -> Enable_InLineElement)
			{
				case self::MARC_OPTION_LEFT:
					if(count($this -> Text) == 0)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 1)
					{
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 2)
					{
						$this -> Text;
					}
					else
					{
						$this -> Text = array_slice($this -> Text, 0, 2);
					}
					break;
				case self::MARC_OPTION_RIGHT:
					if(count($this -> Text) == 0)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 1)
					{
						array_unshift($this -> Text, '');
					}
					elseif(count($this -> Text) == 2)
					{
						return $this -> Text;
					}
					else
					{
						$this -> Text = array_slice($this -> Text, 0, 2);
					}
					break;
				case self::MARC_OPTION_BOTH:
					if(count($this -> Text) == 0)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 1)
					{
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 2)
					{
						$this -> Text;
					}
					else
					{
						$this -> Text = array_slice($this -> Text, 0, 2);
					}
					break;
			}
		}
		else
		{
			switch($this -> Enable_InLineElement)
			{
				case self::MARC_OPTION_LEFT:
					if(count($this -> Text) == 0)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
						$this -> Text[] = '';

					}
					elseif(count($this -> Text) == 1)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 2)
					{
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 3)
					{
						$this -> Text;
					}
					else
					{
						$this -> Text = array_slice($this -> Text, 0, 3);
					}
					break;
				case self::MARC_OPTION_RIGHT:
					if(count($this -> Text) == 0)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 1)
					{
						array_unshift($this -> Text, '');
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 2)
					{
						array_unshift($this -> Text, '');
					}
					elseif(count($this -> Text) == 3)
					{
						$this -> Text;
					}
					else
					{
						$this -> Text = array_slice($this -> Text, 0, 3);
					}
					break;
				case self::MARC_OPTION_BOTH:
					if(count($this -> Text) == 0)
					{
						$this -> Text[] = '';
						$this -> Text[] = '';
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 1)
					{
						array_unshift($this -> Text, '');
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 2)
					{
						$this -> Text[] = '';
					}
					elseif(count($this -> Text) == 3)
					{
						$this -> Text;
					}
					else
					{
						$this -> Text = array_slice($this -> Text, 0, 3);
					}
					break;
			}
		}
	}

	/**
	 * assembles part of code
	 *
	 * @return string
	 */
	private function Get_AssembledCode($Parts)
	{
		$Parts = func_get_args();

		try
		{
			if(count($Parts) != 4)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDEQARGS);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 4);
		}

		/*
		 * extraction of parts that will be assembled into only one text;
		 * Form - where will be added other parts;
		 * Element - element;
		 * Attributes - attributes excepting style;
		 * Styles - styles;
		 * AttributesStyles - help variable that will contain text of styles and attributes
		 * Text - text wrapped in closed element
		 */
		$Form = $Parts[0];
		$Attributes = $Parts[1];
		$Styles = $Parts[2];
		$Text = $Parts[3];
		$AttributesStyles = FALSE;

		if( ($Attributes != self::MARC_OPTION_NOATTR) && ($Styles != self::MARC_OPTION_NOSTL) )
		{
			$AttributesStyles = $Attributes." ".$Styles;
		}
		elseif( ($Attributes != self::MARC_OPTION_NOATTR) && ($Styles == self::MARC_OPTION_NOSTL) )
		{
			$AttributesStyles = $Attributes;
		}
		elseif( ($Attributes == self::MARC_OPTION_NOATTR) && ($Styles != self::MARC_OPTION_NOSTL) )
		{
			$AttributesStyles = $Styles;
		}
		elseif( ($Attributes == self::MARC_OPTION_NOATTR) && ($Styles == self::MARC_OPTION_NOSTL) )
		{
			$AttributesStyles = "";
		}

		if(self::$List_AvailableElements[$this -> Element]['Siblings'] != MarC::MARC_OPTION_EMPTY)
		{
			switch($this -> Enable_InLineElement)
			{
				case self::MARC_OPTION_LEFT:
					return sprintf($Form, $Text[0], $this -> Element, $AttributesStyles, $Text[1], self::$List_AvailableElements[$this -> Element]['ClosingPart'], $Text[2]);
				case self::MARC_OPTION_RIGHT:
					return sprintf($Form, $Text[0], $this -> Element, $AttributesStyles, $Text[1], self::$List_AvailableElements[$this -> Element]['ClosingPart'], $Text[2]);
				case self::MARC_OPTION_BOTH:
					return sprintf($Form, $Text[0], $this -> Element, $AttributesStyles, $Text[1], self::$List_AvailableElements[$this -> Element]['ClosingPart'], $Text[2]);
				default:
					return sprintf($Form, $this -> Element, $AttributesStyles, $Text[0], self::$List_AvailableElements[$this -> Element]['ClosingPart']);
			}
		}
		else
		{
			switch($this -> Enable_InLineElement)
			{
				case self::MARC_OPTION_LEFT:
					return sprintf($Text[0], $Form, $this -> Element, $AttributesStyles, $Text[1]);
				case self::MARC_OPTION_RIGHT:
					return sprintf($Text[0], $Form, $this -> Element, $AttributesStyles, $Text[1]);
				case self::MARC_OPTION_BOTH:
					return sprintf($Text[0], $Form, $this -> Element, $AttributesStyles, $Text[1]);
				default:
					return sprintf($Form, $this -> Element, $AttributesStyles);
			}
		}
	}
	
	/**
	 * assembling of code
	 *
	 * @return string|void
	 *
	 * @throws MarC_Exception if closed element was used and text for wrapping was not prepared (at least empty)
	 */
	public function Execute()
	{
		/*
		 * eliminates unneccessary multiple appearance of the same element
		 */
		self::$Disable_Indention = (self::$Disable_Indention == FALSE) ? FALSE : array_unique(self::$Disable_Indention);
		
		/*
		 * conversion of arrays of set styles and attributes into texts
		 */
		$Styles = isset($this -> ElementStyles_Global[$this -> Element]) ? $this -> Convert_Styles($this -> ElementStyles_Global[$this -> Element]) : self::MARC_OPTION_NOSTL;
		$Attributes = isset($this -> ElementAttributes_Global[$this -> Element]) ? $this -> Convert_Attributes($this -> ElementAttributes_Global[$this -> Element]) : self::MARC_OPTION_NOATTR;
		
		/*
		 * detection of closed/empty element
		 */
		$IsClosedElement = (self::$List_AvailableElements[$this -> Element]['Siblings'] != MarC::MARC_OPTION_EMPTY) ? TRUE : FALSE;
		
		try
		{
			if($IsClosedElement == TRUE && $this -> Text == FALSE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_DMDFUNCTION2, MarC::MARC_XCPT_XPLN_CLOSEDELMT);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_VariableNameAsText($this -> Element), $this -> Element, 'Set_Text');
		}

		$this -> Convert_PrepareText();
		
		/*
		 * generation of code of empty element
		 */
		if($IsClosedElement == FALSE)
		{
			$Form = ($this -> Enable_InLineElement == FALSE) ? self::MARC_CODE_ELEMENT_EMPTY_ML : self::MARC_CODE_ELEMENT_EMPTY_IN;
			
			$this -> LocalCode = $this -> Get_AssembledCode($Form, $Attributes, $Styles, $this -> Text);
		}
		/*
		 * generation of code of closed element
		 */
		else
		{
			$Form = ($this -> Enable_OneLineElement == TRUE) ? self::MARC_CODE_ELEMENT_CLOSED_1L : ($this -> Enable_InLineElement != FALSE ? self::MARC_CODE_ELEMENT_CLOSED_IN : self::MARC_CODE_ELEMENT_CLOSED_ML);
			
			$this -> Text = preg_replace('/\n/', "\n\t", $this -> Text);
			$this -> LocalCode = $this -> Get_AssembledCode($Form, $Attributes, $Styles, $this -> Text);
		}
		
		/*
		 * eliminates spaces from opening parts of elements
		 */
		$this -> LocalCode = preg_replace('/\x20\>/', '>', $this -> LocalCode);
		
		/*
		 * deleting of indention;
		 * this is not default
		 */
		if(self::$Disable_Indention != FALSE)
		{
			foreach(self::$Disable_Indention AS $IndentedElement)
			{
				$this -> LocalCode = preg_replace('/([\t]*)\<'.$IndentedElement.' ([^\<\>]*)\>[\n][\t]+/', '<'.$IndentedElement.' $2>'."\n", $this -> LocalCode);
				$this -> LocalCode = preg_replace('/([\t]*)\<'.$IndentedElement.'\>[\n][\t]+/', '<'.$IndentedElement.'>'."\n", $this -> LocalCode);
			
				$this -> LocalCode = preg_replace('/([\t]*)\<'.$IndentedElement.' ([^\<\>]*)\>/', '<'.$IndentedElement.' $2>', $this -> LocalCode);
				$this -> LocalCode = preg_replace('/([\t]*)\<'.$IndentedElement.'\>/', '<'.$IndentedElement.'>', $this -> LocalCode);
			
				$this -> LocalCode = preg_replace('/([\t]*)\<\/'.$IndentedElement.'\>/', '</'.$IndentedElement.'>', $this -> LocalCode);
			
				if(preg_match_all('/\<'.$IndentedElement.'([^\<\>]*)\>(.*)\<\/'.$IndentedElement.'\>/s', $this -> LocalCode, $Text, PREG_SET_ORDER))
				{
					$Text = preg_replace('/[\t]/', '', $Text[0][2]);
				}
				
				if(in_array($IndentedElement, self::$List_UsedElements))
				{
					$Code = preg_replace('/\<'.$IndentedElement.'([^\<\>]*)\>(.*)\<\/'.$IndentedElement.'\>/s', '<'.$IndentedElement.'$1>'.$Text.'</'.$IndentedElement.'>', $this -> LocalCode);
				}
			}
		}
		
		/*
		 * sets way how code will be exported;
		 * exports code
		 */
		MarC::Set_ExportWay(static::$ExportWay);
		MarC::Add_ConditionalComment($this -> LocalCode, static::$ConditionalComments);
		MarC::Add_Comments($this -> LocalCode, static::$Comments);
		return MarC::Convert_Code($this -> LocalCode, __CLASS__);
	}
}

?>