<?php

namespace MarC;

use UniCAT\CodeExport;
use UniCAT\UniCAT;
use UniCAT\Comments;

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
final class CodeGenerator extends ElementListSetting implements I_MarC_Texts_CodeGenerator, I_MarC_Options_ElementConstruction
{
	use StylesAttributesSetting, CodeExport, Comments;
	
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
	 * disables text indent;
	 * useful in case of usage of textarea
	 *
	 * @static
	 * @var string|array
	 */
	private static $Disable_Indention;
	/**
	 * enables XML-mode for empty elements
	 *
	 * @static
	 * @var bool
	 */
	private static $Enable_XMLStyledEmptyElement = FALSE;
	
	/**
	 * sets used element;
	 * enables/disables usage of XML styled empty elements
	 *
	 * @param string $Element
	 * @param bool $XMLStyle
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if element name was not set
	 * @throws MarC_Exception if XML-style was enabled by wrong option
	 */
	public function __construct($Element="", $XMLStyle=NULL)
	{
		/*
		 * disables multiple new lines and shortens code in that way
		 */
		MarC::Set_DisableMultipleNewLines();
		
		try
		{
			/*
			 * parameter $XMLStyle may be fully empty;
			 * or there must be used TRUE or FALSE
			 */
			if($XMLStyle !== FALSE && $XMLStyle !== TRUE && $XMLStyle !== NULL)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1], MarC::Show_Options_Booleans());
		}
		
		self::$Enable_XMLStyledEmptyElement = ($XMLStyle == TRUE) ? TRUE : FALSE;
		
		try
		{
			if(empty($Element))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0]);
		}
		
		/*
		 * checks element name - against expression and list of available elements
		 */
		if($this -> Check_ElementName($Element))
		{
			$this -> Element = $this -> Check_ElementAvailable($Element);
			self::$List_UsedElements[] = $this -> Element;
		}
	}
	
	/**
	 * prevents sharing of content given to variable written into
	 */
	public function __destruct()
	{
	}

	/**
	 * enables creation of one-line element
	 *
	 * @return void
	 * @throws nothing
	 */
	public function Set_EnableOneLineElement()
	{
		$this -> Enable_OneLineElement = TRUE;
	}
	
	/**
	 * disables creation of one-line element
	 *
	 * @return void
	 * @throws nothing
	 */
	public function Set_DisableOneLineElement()
	{
		$this -> Enable_OneLineElement = FALSE;
	}
	
	/**
	 * disables indention of code;
	 * only lines of chosen elements lost indention;
	 *
	 * @param string $Elements
	 *
	 * @return void
	 *
	 * @throws MarC_Exception nothing
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
				$Element = $this -> Check_ElementAvailable($Elements[0]);
				
				if(empty($Element))
				{
					$this -> Set_DisableIndention();
				}
				else
				{
					if($this -> Check_ElementName($Element))
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
	 * checks element name
	 *
	 * @param string $Element
	 *
	 * @return boolean
	 *
	 * @throws MarC_Exception if element name does not match pattern of element name
	 */
	private function Check_ElementName($Element)
	{
		/*
		 * IE conditional comments MUST be enabled - by function Set_EnableIEConditions
		 */
		if(self::$Enable_IEConditions == FALSE)
		{
			try
			{
				if(preg_match(MarC::MARC_PATTERN_NAME_ELEMENT_OPEN, $Element))
				{
					return TRUE;
				}
				else
				{
					throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGREGEX);
				}
			}
			catch(MarC_Exception $Exception)
			{
				$Exception -> ExceptionWarning(__CLASS__, $Exception -> Get_CallerFunctionName(), $Exception -> Get_Parameters(__CLASS__, __FUNCTION__), $Element, MarC::MARC_PATTERN_NAME_ELEMENT_OPEN);
			}
		}
		else
		{
			try
			{
				if(preg_match(MarC::MARC_PATTERN_NAME_ELEMENT_OPEN, $Element) || preg_match(MarC::MARC_PATTERN_NAME_IECONDITION_OPEN, $Element))
				{
					return TRUE;
				}
				else
				{
					throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGREGEX);
				}
			}
			catch(MarC_Exception $Exception)
			{
				$Exception -> ExceptionWarning(__CLASS__, $Exception -> Get_CallerFunctionName(), $Exception -> Get_Parameters(__CLASS__, __FUNCTION__), $Element, array(MarC::MARC_PATTERN_NAME_ELEMENT_OPEN, MarC::MARC_PATTERN_NAME_IECONDITION_OPEN));
			}
		}
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
				$Text[] = sprintf(self::MARC_CODE_STYLES_1, $Name, $Value);
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
	 * assembles part of code
	 *
	 * @return string
	 */
	private function Get_AssembledCode($Parts="")
	{
		$Parts = func_get_args();
		
		try
		{
			if(empty($Parts))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__));
		}
		
		try
		{
			if(count($Parts) != 5)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_DMDEQARGS);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__), 5);
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
		$Element = $Parts[1];
		$Attributes = $Parts[2];
		$Styles = $Parts[3];
		$Text = $Parts[4];
		$AttributesStyles = FALSE;
		
		if( ($Attributes != self::MARC_OPTION_NOATTRIBUTE) && ($Styles != self::MARC_OPTION_NOSTYLE) )
		{
			$AttributesStyles = $Attributes." ".$Styles;
		}
		elseif( ($Attributes != self::MARC_OPTION_NOATTRIBUTE) && ($Styles == self::MARC_OPTION_NOSTYLE) )
		{
			$AttributesStyles = $Attributes;
		}
		elseif( ($Attributes == self::MARC_OPTION_NOATTRIBUTE) && ($Styles != self::MARC_OPTION_NOSTYLE) )
		{
			$AttributesStyles = $Styles;
		}
		elseif( ($Attributes == self::MARC_OPTION_NOATTRIBUTE) && ($Styles == self::MARC_OPTION_NOSTYLE) )
		{
			$AttributesStyles = "";
		}
		
		$Text = ($Text != self::MARC_OPTION_NOTEXT) ? $Text : "";
		
		if(preg_match('/<%s>/', $Form))
		{
			return sprintf($Form, $Element, $AttributesStyles, $Text, self::$List_AvailableElements[$Element]);
		}
		else
		{
			return sprintf($Form, $Element, $AttributesStyles);
		}
	}
	
	/**
	 * add style to element
	 *
	 * @param string $Name
	 * @param string $Value
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if style name was not set
	 */
	public function Set_Style($Name="", $Value="")
	{
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0]);
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
	 */
	public function Set_Attribute($Name="", $Value="")
	{
		try
		{
			if(empty($Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0]);
		}
		
		/*
		 * attribute value may be empty;
		 * empty values for attributes MUST be enabled
		 */
		try
		{
			if((empty($Value) && $Value != 0) && self::$Enable_NoValueAttributes == FALSE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING, MarC::MARC_EXCEPTIONS_XPLN_EMPTYATTR);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1], 'Set_EnableNoValueAttributes');
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
						throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGVALTYPE);
					}
					else
					{
						$this -> Set_AllElementsAttributes($this -> Element, $Name, $Value);
					}
				}
				catch(MarC_Exception $Exception)
				{
					$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1], gettype($Value), MarC::Show_Options_Scalars());
				}
			}
		}
	}
	
	/**
	 * set text into element;
	 * code generated by previous objects of class CodeGenerator is allowed
	 *
	 * @param string $Text
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if it was set and empty element was used
	 * @throws MarC_Exception if it was not set as string, integer or double
	 */
	public function Set_Text($Text="")
	{
		try
		{
			if(self::$List_AvailableElements[$this -> Element] == $this -> Element)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_VAR, UniCAT::UNICAT_EXCEPTIONS_SEC_VAR_PRHBFUNCTION1, MarC::MARC_EXCEPTIONS_XPLN_EMPTYELMT);
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
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__), gettype($Text), MarC::Show_Options_Scalars());
		}
		
		$this -> Text[] = $Text;
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
		$Styles = isset($this -> ElementStyles_Global[$this -> Element]) ? $this -> Convert_Styles($this -> ElementStyles_Global[$this -> Element]) : FALSE;
		$Attributes = isset($this -> ElementAttributes_Global[$this -> Element]) ? $this -> Convert_Attributes($this -> ElementAttributes_Global[$this -> Element]) : FALSE;
		
		/*
		 * detection of closed/empty element
		 */
		$IsClosedElement = (self::$List_AvailableElements[$this -> Element] != $this -> Element) ? TRUE : FALSE;
		
		try
		{
			if($IsClosedElement == TRUE && $this -> Text == FALSE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_VAR, UniCAT::UNICAT_EXCEPTIONS_SEC_VAR_DMDFUNCTION2, MarC::MARC_EXCEPTIONS_XPLN_CLOSEDELMT);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_VariableNameAsText($this -> Element), $this -> Element, 'Set_Text');
		}
		
		/*
		 * generation of code of empty element
		 */
		if($IsClosedElement == FALSE)
		{
			/*
			 * if XML style was enabled;
			 * this is not default
			 */
			if( self::$Enable_XMLStyledEmptyElement == TRUE )
			{
				/*
				 * if attributes and styles were set;
				 * if only attributes were set;
				 * if only styles were set
				 * if attributes and styles were not set
				 */
				if(($Attributes != FALSE) && ($Styles != FALSE))
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_XML, $this -> Element, $Attributes, $Styles, self::MARC_OPTION_NOTEXT);
				}
				elseif(($Attributes != FALSE) && ($Styles == FALSE))
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_XML, $this -> Element, $Attributes, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
				}
				elseif(($Attributes == FALSE) && ($Styles != FALSE))
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_XML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, $Styles, self::MARC_OPTION_NOTEXT);
				}
				else
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_XML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
				}
			}
			/*
			 * if XML style was not enabled
			*/
			else
			{
				/*
				 * if attributes and styles were set;
				 * if only attributes were set;
				 * if only styles were set
				 * if attributes and styles were not set
				 */
				if(($Attributes != FALSE) && ($Styles != FALSE))
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_HTML, $this -> Element, $Attributes, $Styles, self::MARC_OPTION_NOTEXT);
				}
				elseif(($Attributes != FALSE) && ($Styles == FALSE))
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_HTML, $this -> Element, $Attributes, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
				}
				elseif(($Attributes == FALSE) && ($Styles != FALSE))
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_HTML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, $Styles, self::MARC_OPTION_NOTEXT);
				}
				else
				{
					$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_EMPTY_HTML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
				}
			}
		}
		/*
		 * generation of code of closed element
		 */
		else
		{
			/*
			 * if text was set
			 */
			if(!empty($this -> Text) || ($this -> Text == 0))
			{
				/*
				 * automatical increasing of indention
				 */
				$this -> Text = preg_replace('/[\n]/', "\n\t", implode(' ', $this -> Text));
				
				/*
				 * if opening and closing part may be on the same line;
				 * this is not default
				 */
				if($this -> Enable_OneLineElement == TRUE)
				{
					/*
					 * if attributes and styles were set;
					 * if only attributes were set;
					 * if only styles were set
					 * if attributes and styles were not set
					 */
					if(($Attributes != FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, $Attributes, $Styles, $this -> Text);
					}
					elseif(($Attributes != FALSE) && ($Styles == FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, $Attributes, self::MARC_OPTION_NOSTYLE, $this -> Text);
					}
					elseif(($Attributes == FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, $Styles, $this -> Text);
					}
					else
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, self::MARC_OPTION_NOSTYLE, $this -> Text);
					}
				}
				/*
				 * if opening and closing part cannot be on the same line
				 */
				else
				{
					/*
					 * if attributes and styles were set;
					 * if only attributes were set;
					 * if only styles were set
					 * if attributes and styles were not set
					 */
					if(($Attributes != FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, $Attributes, $Styles, $this -> Text);
					}
					elseif(($Attributes != FALSE) && ($Styles == FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, $Attributes, self::MARC_OPTION_NOSTYLE, $this -> Text);
					}
					elseif(($Attributes == FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, $Styles, $this -> Text);
					}
					else
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, self::MARC_OPTION_NOSTYLE, $this -> Text);
					}
				}
			}
			/*
			 * if text was not set (but its variable MUST be prepared)
			 */
			else
			{
				/*
				 * if opening and closing part may be on the same line;
				 * this is not default
				 */
				if($this -> Enable_OneLineElement == TRUE)
				{
					/*
					 * if attributes and styles were set;
					 * if only attributes were set;
					 * if only styles were set
					 * if attributes and styles were not set
					 */
					if(($Attributes != FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, $Attributes, $Styles, self::MARC_OPTION_NOTEXT);
					}
					elseif(($Attributes != FALSE) && ($Styles == FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, $Attributes, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
					}
					elseif(($Attributes == FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, $Styles, self::MARC_OPTION_NOTEXT);
					}
					else
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_1L, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
					}
				}
				/*
				 * if opening and closing part may be on the same line
				 */
				else
				{
					/*
					 * if attributes and styles were set;
					 * if only attributes were set;
					 * if only styles were set
					 * if attributes and styles were not set
					 */
					if(($Attributes != FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, $Attributes, $Styles, self::MARC_OPTION_NOTEXT);
					}
					elseif(($Attributes != FALSE) && ($Styles == FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, $Attributes, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
					}
					elseif(($Attributes == FALSE) && ($Styles != FALSE))
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, $Styles, self::MARC_OPTION_NOTEXT);
					}
					else
					{
						$this -> LocalCode = $this -> Get_AssembledCode(self::MARC_CODE_ELEMENT_CLOSED_ML, $this -> Element, self::MARC_OPTION_NOATTRIBUTE, self::MARC_OPTION_NOSTYLE, self::MARC_OPTION_NOTEXT);
					}
				}
			}
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
		MarC::Add_Comments($this -> LocalCode, static::$Comments);
		return MarC::Convert_Code($this -> LocalCode, __CLASS__);
	}
}

?>