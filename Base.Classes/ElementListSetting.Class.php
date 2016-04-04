<?php

namespace MarC;

use UniCAT\ErrorOptions;
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
 * setting of element list
 */
class ElementListSetting implements I_MarC_Expressions_ElementsSetting
{
	use ErrorOptions;
	
	/**
	 * list of elements got from DTD file;
	 * form of each item:
	 * [ElementName] => array(	[ClosingPart] = '/ElementName',
	 *			    			[Siblings] = EMPTY|#PCDATA|array(.,.,.)
	 * );
	 *
	 * @static
	 * @var array list of elements with their siblings
	 */
	protected static $AvailableElements = array();
	
	/**
	 * direct access for function Set_ElementList
	 *
	 * @param string $File DTD file
	 * @param boolean $ResetList to start new list of elements or not (and add next elements)
	 *
	 * @example new ElementListSetting('example.dtd');
	 * @example new ElementListSetting('example.dtd', TRUE);
	 */
	public function __construct($File, $ResetList=NULL)
	{
		/*
		 * initial setting of instance of class MarC;
		 * using of function __construct is also available
		 */
		MarC::Set_Instance();
		
		$this -> Set_ElementList($File, $ResetList);
	}

	/**
	 * prevents using of non-public functions
	 *
	 * @param string $Method function name
	 * @param array $Parameters function parameter values - arguments
	 * 
	 * @throws MarC_Exception
	 */
	public function __call($Method, $Parameters)
	{
		try
		{
			if(method_exists($this, $Method))
			{
				if(MethodScope::Check_IsPublic(get_called_class(), $Method))
				{
					call_user_func_array($Method, $Parameters);
				}
				else
				{
					throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_SEC_FNC_PRHBUSE1);
				}
			}
			else
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_SEC_FNC_MISSING1);
			}
		}
		catch(UniCAT_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $Method);
		}
	}

	/**
	 * setting of file with element definition;
	 * setting if new set will be created or original list will be extended
	 *
	 * @param string $File DTD file
	 * @param boolean $ResetList to start new list of elements or not (and add next elements)
	 *
	 * @throws MarC_Exception
	 *
	 * @example Set_ElementList('example.dtd');
	 * @example Set_ElementList('example.dtd', TRUE);
	 */
	public function Set_ElementList($File, $ResetList=FALSE)
	{
		/*
		 * disables multiple new lines and shortens code in that way
		 */
		MarC::Set_DisableMultipleNewLines();

		try
		{
			if(empty($File))
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
			if(!file_exists($File))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_SRC_MISSING);
			}
		}
		catch (MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), 'file '.$File);
		}

		try
		{
			/*
			 * parameter $ResetList may be fully empty;
			 * or there must be used TRUE or FALSE
			 */
			if($ResetList !== FALSE && $ResetList !== TRUE)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch (MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::ShowOptions_Booleans());
		}
		
		self::$AvailableElements = ($ResetList === TRUE) ? array() : self::$AvailableElements;		
		
		$this -> Get_ElementList($File);
	}
	
	/**
	 * adds one element to element list;
	 * alternative to main function for cases if dtd file is not available
	 *
	 * @param string $Open element name
	 * @param string|array $Siblings allowed siblings
	 *
	 * @throws MarC_Exception
	 *
	 * @example Set_AddElement('example'); to add empty element <example>
	 * @example Set_AddElement('example', '#PCDATA'); to add element <example> without any sibling
	 * @example Set_AddElement('example', 'usage'); to add element <example> with only one sibling, <usage>
	 * @example Set_AddElement('example', array('usage', ...) ); to add element <example> with more siblings
	 */
	public function Set_AddElement($Name, $Siblings=MarC::MARC_OPTION_EMPTY)
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

		try
		{
			if(!preg_match(MarC::MARC_XPSN_ADDELMT_NAME, $Name))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGREGEX);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), MarC::MARC_XPSN_ADDELMT_NAME);
		}
		
		try
		{
			if(!is_array($Siblings) || !is_string($Siblings))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), gettype($Siblings), array('array', 'string') );
		}
		
		try
		{
			if(is_string($Siblings) && !in_array($Siblings, MarC::ShowOptions_ElementSetting()))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), __FUNCTION__, MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__, 1), MarC::ShowOptions_ElementSetting());
		}
		
		switch($Siblings)
		{
			case MarC::MARC_OPTION_ANY:
				self::$AvailableElements[$Open]['ClosingPart'] = '/'.$Open;
				self::$AvailableElements[$Open]['Siblings'] = array_keys(self::$AvailableElements);
				break;
			case MarC::MARC_OPTION_EMPTY:
				self::$AvailableElements[$Open]['ClosingPart'] = $Open;
				self::$AvailableElements[$Open]['Siblings'] = MarC::MARC_OPTION_EMPTY;
				break;
			case MarC::MARC_OPTION_ONLYTEXT:
				self::$AvailableElements[$Open]['ClosingPart'] = '/'.$Open;
				self::$AvailableElements[$Open]['Siblings'] = MarC::MARC_OPTION_ONLYTEXT;
				break;
			default:
				self::$AvailableElements[$Open]['ClosingPart'] = '/'.$Open;
				self::$AvailableElements[$Open]['Siblings'] = $Siblings;
				break;
		}
	}
	
	/**
	 * checks if elements of given part of code are valid;
	 * element validity means that given elements are present in list of available elemewnt and that they can be used as siblings/parents of else given elements;
	 * index 0 = main element, index 1 = sibling of main element, index 2 = sibling of previous element ... and so on
	 * 
	 * @param string|array $Elements element name
	 * 
	 * @throws MarC_Exception
 	 */
	protected function Check_ElementTreeValidity($Elements)
	{
		$Elements = func_get_args();
		$Error = NULL;
		
		try
		{
			for($Index = 0; $Index < count($Elements); $Index++)
			{
				if(is_array($Elements[$Index]) && ($Index != count($Elements)-1))
				{
					$Error = $Index;
					throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_WRONGVALTYPE);
				}
			}			
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), gettype($Elements[$Error]), MarC::ShowOptions_Scalars());
		}

		try
		{
			if(count($Elements) == 1 && !is_array($Elements) && !array_key_exists($Elements[0], self::$AvailableElements))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_PRHBOPTION, MarC::MARC_XCPT_XPLN_DTDFILE);
			}
		}
		catch(MarC_Exception $Exception)
		{
			$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $Elements[0]);
		}
		
		try
		{
			for($Index = 0; $Index < count($Elements); $Index++)
			{
				if(!is_array($Elements[$Index]))
				{
					if(!array_key_exists($Elements[$Index], self::$AvailableElements))
					{
						$Error = 1;
						break;
					}
					elseif(isset($Elements[$Index-1]) && !is_array(self::$AvailableElements[$Elements[$Index-1]]['Siblings']))
					{
						$Error = 2;
						break;
					}
					elseif(isset($Elements[$Index-1]) && !in_array($Elements[$Index], self::$AvailableElements[$Elements[$Index-1]]['Siblings']))
					{
						$Error = 3;
						break;
					}
				}
				elseif(is_array($Elements[$Index] && isset($Elements[$Index-1])))
				{
					if(array_intersect($Elements[$Index], self::$AvailableElements[$Elements[$Index-1]]['Siblings']) != $Elements[$Index])
					{
						$Error = 4;
						break;
					}
				}
				else
				{
					if(array_intersect($Elements[$Index], array_keys(self::$AvailableElements)) != $Elements[$Index])
					{
						$Error = 1;
						break;
					}
				}
			}

			if($Error == 1)
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_PRHBOPTION, MarC::MARC_XCPT_XPLN_DTDFILE);
			}
			elseif(in_array($Error, range(2, 4)))
			{
				throw new MarC_Exception(UniCAT::UNICAT_XCPT_MAIN_CLS, UniCAT::UNICAT_XCPT_MAIN_FNC, UniCAT::UNICAT_XCPT_MAIN_PRM, UniCAT::UNICAT_XCPT_SEC_PRM_DMDOPTION);
			}
			else
			{
				return TRUE;
			}
		}
		catch(MarC_Exception $Exception)
		{
			if($Error == 1)
			{
				$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), $Elements[$Index]);
			}
			elseif(in_array($Error, range(2, 4)))
			{
				$Exception -> ExceptionWarning(get_called_class(), $this -> Get_CallerFunctionName(), MethodScope::Get_ParameterName(__CLASS__, __FUNCTION__), self::$AvailableElements[$Elements[0]]['Siblings']);
			}
		}
	}

	/**
	 * extraction of element set from given file
	 *
	 * @param string $File DTD file
	 */
	private function Get_ElementList($File)
	{
		/*
		 * reads file;
		 * replaces unwanted "spaces"
		 */
		$File = file_get_contents($File);
		$File = preg_replace("/[[:cntrl:]]+/", "", $File);
		
		/*
		 * searches for elements;
		 * searches for entities
		 */
		$Result = preg_match_all(self::MARC_XPSN_DTDELMT_NAMECONTENT, $File, $Elements, PREG_SET_ORDER);
		$Result = preg_match_all(self::MARC_XPSN_DTDNTT_BLOCK, $File, $Entities, PREG_SET_ORDER);
		
		/*
		 * converts entities to usable form
		 */
		$this -> Convert_SimplifyEntities($Entities);
		$this -> Convert_PrepareEntities($Entities);
		
		/*
		 * iterates list of elements and sets closing part of element and usable siblings
		 */
		foreach ($Elements as $Element)
		{
			self::$AvailableElements[$Element['ElementName']]['ClosingPart'] = ($Element['ElementSetting'] == MarC::MARC_OPTION_EMPTY) ? $Element['ElementName'] : '/'.$Element['ElementName'];
			self::$AvailableElements[$Element['ElementName']]['Siblings'] = $this -> Get_Siblings($Element['ElementSetting'], $Entities);
		}
	}
	
	/**
	 * pairing of siblings to parent element
	 *
	 * @param string $ElementSetting raw list of siblings as is extracted from DTD file
	 * @param array $Entities list of entities extrtacted from DTD file
	 *
	 * @return array|string list of siblings, EMPTY, #PCDATA or list of siblings with #PCDATA
	 */
	private function Get_Siblings($ElementSetting, $Entities)
	{
		$Result = preg_match_all(self::MARC_XPSN_DTDNTT_USED, $ElementSetting, $Entities_Used, PREG_PATTERN_ORDER);
		
		if($Result)
		{
			$Replace_What = $Entities_Used['Entities'];
			$Replace_With = array();
			
			foreach($Replace_What as $Entity)
			{
				$Replace_With[$Entity] = $Entities[trim($Entity, '%;')];
			}
			
			$ElementSetting = str_replace($Replace_What, $Replace_With, $ElementSetting);
		}
		
		$Result = preg_match_all(self::MARC_XPSN_DTDELMT_CONTENT, $ElementSetting, $Elements, PREG_PATTERN_ORDER);
		
		if($Result)
		{
			$ElementSetting = array_unique($Elements['Element']);
		}

		$ElementSetting = preg_replace('/\(#PCDATA\)/', '#PCDATA', $ElementSetting);
		$ElementSetting = (count($ElementSetting) == 1) ? (($ElementSetting[0] == '#PCDATA' || $ElementSetting[0] == 'EMPTY') ? $ElementSetting[0] : $ElementSetting) : $ElementSetting;
		
		return $ElementSetting;
	}
	
	/**
	 * prepares simplified associative array of items with form [EntityName] => EntityValue
	 *
	 * @param array $Entities entities extracted from DTD file
	 */
	private function Convert_SimplifyEntities(&$Entities)
	{	
		$Entities_New = array();
		
		foreach($Entities as $Entity)
		{
			$Result = preg_match_all(self::MARC_XPSN_DTDNTT_NAMECONTENT, $Entity[1], $Entity, PREG_PATTERN_ORDER);
			
			$Entities_New[trim($Entity['EntityName'][0])] = $Entity['EntitySetting'][0];
		}
		
		$Entities = $Entities_New;
	}
	
	/**
	 * prepares entities for later using in replacement of entities used in elements setting of siblings
	 * 
	 * @param array $Entities simplified array given by extraction from DTD file
	 */
	private function Convert_PrepareEntities(&$Entities)
	{
		$Entities_Old = $Entities;
		$Entities_Final = array();
		$Entities_Replaced = array();
		
		foreach($Entities_Old as $Name => $Value)
		{
			if(!preg_match_all('/(?<Entities>%[a-zA-Z0-9]{1,}\.{0,1}[a-zA-Z0-9]{0,};)/', $Value))
			{
				$Entities_Final[$Name] = $Value;
			}
			else
			{
				$Entities_Replaced[$Name] = $Value;
			}
		}
		
		foreach($Entities_Replaced as $Name => $Value)
		{
			$Result = preg_match_all('/(?<Entities>%[a-zA-Z0-9]{1,}\.{0,1}[a-zA-Z0-9]{0,};)/', $Value, $Entities_Used, PREG_PATTERN_ORDER);
			
			$Replace_What = $Entities_Used['Entities'];
			$Replace_With = array();
			
			foreach($Replace_What as $Entity)
			{
				$Replace_With[$Entity] = (array_key_exists(trim($Entity, '%;'), $Entities_Final) ? $Entities_Final[trim($Entity, '%;')] : '' );
			}
			
			$Entities_Final[$Name] = str_replace($Replace_What, $Replace_With, $Value);
		}
		
		$Entities = $Entities_Final;
	}
	
}

?>