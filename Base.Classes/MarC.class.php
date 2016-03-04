<?php

namespace MarC;

use UniCAT\InstanceOptions;
use UniCAT\CodeExport;
use UniCAT\CodeMemory;
use UniCAT\Comments;
use UniCAT\UniCAT;
use UniCAT\ClassScope;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * class for easier access to class constants of interfaces
 */
final class MarC extends UniCAT implements I_MarC_Expressions_Union, I_MarC_Options_Union, I_MarC_Texts_Union, I_MarC_Exceptions
{
	use CodeExport, CodeMemory, Comments, ConditionalComments,
	InstanceOptions
	{
		Set_Instance as public;
	}
	
	/**
	 * prepares lists of options
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		
		self::$Options['element_construction'] = ClassScope::Get_ConstantsValues("MarC\I_MarC_Options_ElementConstruction");
		self::$Options['content_usage'] = ClassScope::Get_ConstantsValues("MarC\I_MarC_Options_ContentUsage");
		self::$Options['values_separation'] = ClassScope::Get_ConstantsValues("MarC\I_MarC_Options_ValuesSeparation");
		self::$Options['element_setting'] = ClassScope::Get_ConstantsValues("MarC\I_MarC_Options_ElementsSetting");
		self::$Options['in_line_setting'] = ClassScope::Get_ConstantsValues("MarC\I_MarC_Options_InLineSetting");
		self::$Options['code_heading'] = ClassScope::Get_ConstantsValues('MarC\I_MarC_Texts_CodeHeading');
	}
}

?>