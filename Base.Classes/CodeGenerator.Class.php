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
	use ConditionalComments, StylesAttributesSetting, CodeExport, Comments
	{
		Add_Comments as private;
		Add_ConditionalComments as private;
	}
	
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
	private $Text = FALSE;
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
	 * @param string $Element element name
	 *
	 * @throws MarC_Exception
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
					$this -> Element = $Element;
				}
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__));
		}

		if(is_array(self::$AvailableElements[$this -> Element]['Siblings']) && in_array('#PCDATA', self::$AvailableElements[$this -> Element]['Siblings']))
		{
			$this -> Enable_OneLineElement = TRUE;
		}
		elseif(!is_array(self::$AvailableElements[$this -> Element]['Siblings']) && self::$AvailableElements[$this -> Element]['Siblings'] == '#PCDATA')
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
		$this -> Text = FALSE;
		$this -> Enable_OneLineElement = FALSE;
		$this -> Enable_InLineElement = FALSE;
	}

	/**
	 * enables creation of in-line element;
	 * allows insertion of text also to empty element
	 *
	 * @param string $Position position of text added to element
	 *
	 * @throws MarC_Exception
	 */
	public function Set_EnableInLineElement()
	{
		$this -> Enable_InLineElement = TRUE;
	}
	
	/**
	 * disables indention of code;
	 * only lines of chosen elements lost indention;
	 *
	 * @param string $Elements element name
	 *
	 * @example Set_DisableIndention();
	 * @example Set_DisableIndention('textarea');
	 * @example Set_DisableIndention('textarea', 'form');
	 */
	public function Set_DisableIndention($Elements="")
	{		
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
	 * @param string $Name style name
	 * @param string $Value style value
	 *
	 * @throws MarC_Exception
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
	 * @param string $Name attribute name
	 * @param string|array $Value attribute value(s)
	 *
	 * @throws MarC_Exception
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
		
		if($this -> Check_AttributeName($Name))
		{
			try
			{
				if(!in_array(gettype($Value), MarC::ShowOptions_Scalars()))
				{
					throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
				}
				else
				{
					if(in_array(gettype($Value), MarC::ShowOptions_Scalars()))
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
							if(self::$Enable_NoValueAttributes == TRUE)
							{
								$this -> Set_AllElementsAttributes($this -> Element, $Name, (!empty($Value) ? implode($this -> ValuesSeparators_Global[$Name], $Value) : '' ));
							}
							else
							{
								if(!empty($Value))
								{
									$this -> Set_AllElementsAttributes($this -> Element, $Name, implode($this -> ValuesSeparators_Global[$Name], $Value));
								}
							}
						}
						else
						{
							/*
								 * default option for sticking of multiple values;
								 * if character was not set for current attribute
								 */
							if(self::$Enable_NoValueAttributes == TRUE)
							{
								$this -> Set_AllElementsAttributes($this -> Element, $Name, (!empty($Value) ? implode(MarC::ShowOptions_ValuesSeparation()[1], $Value) : '' ));
							}
							else
							{
								if(!empty($Value))
								{
									$this -> Set_AllElementsAttributes($this -> Element, $Name, implode(MarC::ShowOptions_ValuesSeparation()[1], $Value));
								}
							}
						}
					}
				}
			}
			catch(MarC_Exception $Exception)
			{
				$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), gettype($Value), MarC::ShowOptions_Scalars());
			}
		}
	}
	
	/**
	 * sets separator of values of attributes;
	 * this function has to be in the front of functions for setting of attributes
	 *
	 * @param string $Attribute attribute name
	 * @param string $Separator character used to separate attribute values
	 *
	 * @throws MarC_Exception
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
			if(in_array($Separator, MarC::ShowOptions_ValuesSeparation()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::ShowOptions_ValuesSeparation());
		}
		
		$this -> Set_AllValuesSeparators($Attribute, $Separator);
	}
	
	/**
	 * set text into element;
	 * code generated by previous objects of class CodeGenerator is allowed
	 *
	 * @param string $Text just text that will be wrapped by element - or added before or after element
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
			if(!in_array(gettype($Text), MarC::ShowOptions_Scalars()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Text), MarC::ShowOptions_Scalars());
		}

		try
		{
			if(preg_match(MarC::MARC_XPSN_PSNELMT_GNRDCODE, $Text) && self::$AvailableElements[$this -> Element]['Siblings'] == MarC::MARC_OPTION_DATA)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_PRHBOPTION, MarC::MARC_XCPT_XPLN_DTDFILE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $Text);
		}

		if(preg_match(MarC::MARC_XPSN_PSNELMT_GNRDCODE, $Text) || preg_match('/\n/', $Text))
		{
			$this -> Enable_OneLineElement = FALSE;
		}

		$this -> Text = preg_replace('/\n/', "\n\t", $Text);
	}

	/**
	 * converts array of styles into text attribute value style
	 *
	 * @param array $Styles styles
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
	 * @param array $Attributes attributes
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
	 * @return string assembled code
	 */
	private function Get_AssembledCode($Parts)
	{
		$Parts = func_get_args();

		try
		{
			if(!in_array(count($Parts), array(3,4)))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDLMTDARGS1);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 3, 4);
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
		$Text = (isset($Parts[3]) ? $Parts[3] : FALSE);
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

		if(self::$AvailableElements[$this -> Element]['Siblings'] != MarC::MARC_OPTION_EMPTY)
		{
			return sprintf($Form, $this -> Element, $AttributesStyles, $Text, self::$AvailableElements[$this -> Element]['ClosingPart']);
		}
		else
		{
			return sprintf($Form, $this -> Element, $AttributesStyles);
		}
	}
	
	/**
	 * assembling of code
	 *
	 * @return string|void generated code or nothing
	 *
	 * @throws MarC_Exception
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
		$IsClosedElement = (self::$AvailableElements[$this -> Element]['Siblings'] != MarC::MARC_OPTION_EMPTY) ? TRUE : FALSE;
		
		try
		{
			if($IsClosedElement == TRUE && $this -> Text === FALSE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_VAR, UniCAT::UNICAT_XCPT_SEC_VAR_DMDFUNCTION2, MarC::MARC_XCPT_XPLN_CLOSEDELMT);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(__CLASS__, __FUNCTION__, $Exception -> Get_VariableNameAsText($this -> Element), $this -> Element, 'Set_Text');
		}
		
		/*
		 * generation of code of empty element;
		 * generation of code of closed element
		 */
		if($IsClosedElement == FALSE)
		{
			$Form = ($this -> Enable_InLineElement == TRUE) ? self::MARC_CODE_ELEMENT_EMPTY_IN : self::MARC_CODE_ELEMENT_EMPTY_ML;
			$this -> LocalCode = $this -> Get_AssembledCode($Form, $Attributes, $Styles);
		}
		else
		{
			$Form = ($this -> Enable_InLineElement == TRUE) ? self::MARC_CODE_ELEMENT_CLOSED_IN : ($this -> Enable_OneLineElement == TRUE ? self::MARC_CODE_ELEMENT_CLOSED_1L : self::MARC_CODE_ELEMENT_CLOSED_ML);
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
  			}
		}
		
		/*
		 * sets way how code will be exported;
		 * exports code
		 */
		MarC::Set_ExportWay(static::$ExportWay);
		MarC::Add_ConditionalComments($this -> LocalCode, static::$ConditionalComments);
		MarC::Add_Comments($this -> LocalCode, static::$Comments);
		static::$Comments = FALSE;
		return MarC::Convert_Code($this -> LocalCode, __CLASS__);
	}
}

?>