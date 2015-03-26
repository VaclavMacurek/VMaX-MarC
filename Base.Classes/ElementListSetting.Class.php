<?php

namespace MarC;

use UniCAT\BasicOptions;
use UniCAT\UniCAT;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * setting of element list
 */
class ElementListSetting implements I_MarC_Expressions_ElementListSetting
{
	use BasicOptions;
	
	/**
	 * list of elements got from DTD file
	 *
	 * @static
	 * @var array
	 */
	protected static $List_AvailableElements = array();
	/**
	 * enables usage of IE conditions
	 *
	 * @static
	 * @var bool
	 */
	protected static $Enable_IEConditions;
	
	/**
	 * direct access for function Set_ElementList
	 *
	 * @param string $File
	 * @param boolean $ResetList
	 * @return void
	 */
	public function __construct($File="", $ResetList=NULL)
	{
		/*
		 * initial setting of instance of class MarC;
		 * using of function __construct is also available
		 */
		MarC::Set_Instance();
		
		$this -> Set_ElementList($File, $ResetList);
	}
	
	/**
	 * setting of file with element definition;
	 * setting if new set will be created or original list will be extended
	 *
	 * @param string $File
	 * @param boolean $ResetList
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if list of available elements was reset by wrong option
	 * @throws MarC_Exception if list of available elements was not prepared
	 */
	public function Set_ElementList($File="", $ResetList=NULL)
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
			if($ResetList !== FALSE && $ResetList !== TRUE && $ResetList !== NULL)
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_DMDOPTION);
			}
		}
		catch (MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $Exception -> Get_CallerFunctionName(), $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1], MarC::Show_Options_Booleans());
		}
		
		self::$List_AvailableElements = ($ResetList == TRUE) ? array() : self::$List_AvailableElements;
			
		try
		{
			if(empty($File))
			{
				if(empty(self::$List_AvailableElements))
				{
					throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_MISSING);
				}
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $Exception -> Get_CallerFunctionName(), $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0]);
		}
		
		try
		{
			if(!file_exists($File))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_SRC_MISSING);
			}
		}
		catch (MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $Exception -> Get_CallerFunctionName(), $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0], 'file '.$File);
		}
		
		$this -> Get_ElementList($File);
	}
	
	/**
	 * adds one element to element list;
	 * alternative to main function for cases if dtd file is not available
	 *
	 * @param string $Open
	 * @param string $Close
	 *
	 * @return void
	 *
	 * @throws MarC_Exception if element names were not set
	 * @throws MarC_Exception if element names do not match pattern of element name
	 */
	public function Set_AddElement($Open="", $Close="")
	{
		try
		{
			if(empty($Open))
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
			if(empty($Close))
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
			if(!preg_match(MarC::MARC_PATTERN_NAME_ELEMENT_OPEN, $Open) || !preg_match(MarC::MARC_PATTERN_NAME_IECONDITION_OPEN, $Open))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[0], array(MarC::MARC_PATTERN_NAME_ELEMENT_OPEN, MarC::MARC_PATTERN_NAME_IECONDITION_OPEN));
		}
		
		try
		{
			if(!preg_match('/'.MarC::MARC_PATTERN_NAME_ELEMENT_CLOSE, $Close) || !preg_match('/'.MarC::MARC_PATTERN_NAME_IECONDITION_CLOSE, $Close))
			{
				throw new MarC_Exception(UniCAT::UNICAT_EXCEPTIONS_MAIN_CLS, UniCAT::UNICAT_EXCEPTIONS_MAIN_FNC, UniCAT::UNICAT_EXCEPTIONS_MAIN_PRM, UniCAT::UNICAT_EXCEPTIONS_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, $Exception -> Get_Parameters(__CLASS__, __FUNCTION__)[1], array(MarC::MARC_PATTERN_NAME_ELEMENT_CLOSE, MarC::MARC_PATTERN_NAME_IECONDITION_CLOSE));
		}
		
		self::$List_AvailableElements[$Open] = $Close;
	}

	/**
	 * extraction of element set from given file
	 *
	 * @param string $File
	 *
	 * @return void
	 */
	private function Get_ElementList($File="")
	{
		/*
		 * reads file;
		 * converts new lines;
		 * splits file content
		 */
		$File = file_get_contents($File);
		$File = str_replace("\r\n", "\n", $File);
		$File = str_replace("\r", "\n", $File);
		$File = explode("\n", $File);
		
		foreach($File AS $Line)
		{
			/*
			 * if element definition is detected
			 */
			if(preg_match(self::MARC_PATTERN_DEFINITION_ANYELEMENT, $Line))
			{
				/*
				 * splits line text
				 */
				$Line_Elements = preg_split('/ /', $Line);
				
				/*
				 * if empty element definition is detected
				 */
				if(in_array("EMPTY", $Line_Elements) || preg_match(self::MARC_PATTERN_DEFINITION_EMPTYELEMENT, $Line))
				{
					$Replace_What = array("\n", "\t", "(", ")");
					$Replace_With = array("", "", "", "");
					$Line_Elements = preg_split('/\|/', str_replace($Replace_What, $Replace_With, $Line_Elements[1]));
					
					/*
					 * sets found element into list of available elements
					 */
					foreach($Line_Elements AS $Element)
					{
						if(!preg_match(self::MARC_PATTERN_DEFINITION_ENTITY, $Element))
						{
							self::$List_AvailableElements[strtolower(trim($Element))] = strtolower(trim($Element));
						}
					}
				}
				/*
				 * if closed element definition is detected
				 */
				else
				{
					$Replace_What = array("\n", "\t", "(", ")");
					$Replace_With = array("", "", "", "");
					$Line_Elements = preg_split('/\|/', str_replace($Replace_What, $Replace_With, $Line_Elements[1]));
					
					/*
					 * sets found element into list of available elements
					 */
					foreach($Line_Elements AS $Element)
					{
						if(!preg_match(self::MARC_PATTERN_DEFINITION_ENTITY, $Element))
						{
							self::$List_AvailableElements[strtolower(trim($Element))] = "/".strtolower(trim($Element));
						}
					}
				}
			}
		}
	}
	
	/**
	 * adds IE conditions to element list
	 *
	 * @todo add another conditions if needed
	 * @return void
	 */
	public function Set_EnableIEConditions()
	{
		self::$Enable_IEConditions = TRUE;
		
		$Conditions = array(	"!--[if IE]" => "![endif]--",
								"!--[if IE 5]" => "![endif]--",
								"!--[if IE 5.0]" => "![endif]--",
								"!--[if IE 5.5]" => "![endif]--",
								"!--[if IE 6]" => "![endif]--",
								"!--[if IE 7]" => "![endif]--",
								"!--[if IE 8]" => "![endif]--",
								"!--[if gte IE 5]" => "![endif]--",
								"!--[if gte IE 5.5]" => "![endif]--",
								"!--[if gte IE 6]" => "![endif]--",
								"!--[if gte IE 7]" => "![endif]--",
								"!--[if lte IE 5.5]" => "![endif]--",
								"!--[if lte IE 6]" => "![endif]--",
								"!--[if lte IE 7]" => "![endif]--",
								"!--[if lte IE 8]" => "![endif]--",
								"!--[if lt IE 5.5]" => "![endif]--",
								"!--[if lt IE 6]" => "![endif]--",
								"!--[if lt IE 7]" => "![endif]--",
								"!--[if lt IE 8]" => "![endif]--",
								);
		
		self::$List_AvailableElements = array_merge(self::$List_AvailableElements, $Conditions);
	}
}

?>