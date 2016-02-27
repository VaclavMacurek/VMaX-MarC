<?php

namespace MarC;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 */

/**
 * interface for class CodeGenerator;
 * support options for element construction
 */
interface I_MarC_Options_ElementConstruction
{
	/**
	 * use in code assembling function to say that no attribute is included
	 */
	const MARC_OPTION_NOATTRIBUTE = 'NOATTR';
	/**
	 * use in code assembling function to say that no style is included
	 */
	const MARC_OPTION_NOSTYLE = 'NOSTYLE';
}

/**
 * interface for class SimpleAssembler;
 * support options for content usage
 */
interface I_MarC_Options_ContentUsage
{
	/**
	 * use to say that key or value will be used as text closed in element
	 */
	const MARC_OPTION_ELEMENTTEXT = 'ELEMENTTEXT';
	/**
	 * use to say that key or value will be used as value of any attribute
	 */
	const MARC_OPTION_ATTRIBUTEVALUE = 'ATTRIBUTEVALUE';
	/**
	 * use to say that key or value will not be used
	 */
	const MARC_OPTION_NOTUSE = 'NOTUSE';
}

/**
 * interface for trait StylesAttributesSetting and related classes;
 * support options for separation of values of attributes
 */
interface I_MarC_Options_ValuesSeparation
{
	/**
	 * use to say that values will be separated by comma
	 */
	const MARC_OPTION_COM = ',';
	/**
	 * use to say that values will be separated by space
	 */
	const MARC_OPTION_SPC = "\x20";
}

/**
 * interface for class ElementListSetting;
 * support options for manual setting of new element
 */
interface I_MarC_Options_ElementSetting
{
	/**
     * use to say that element will be empty
     */
	const MARC_OPTION_EMPTY = 'EMPTY';
	/**
	 * use to say that element will allow only simple text
	 */
	const MARC_OPTION_ONLYTEXT = '#PCDATA';
	/**
	 * use to say that element will allow all elements;
	 * all other previously set elements will be copied to list of siblings
	 */
	const MARC_OPTION_ANY = 'ANY';
}

interface I_MarC_Options_InLineSetting
{
	/**
	 * use to say that in creation of in-line element text will be insert to the front of element
	 */
	const MARC_OPTION_FRONT = 'FRONT';
	/**
	 * use to say that in creation of in-line element text will be insert after element
	 */
	const MARC_OPTION_AFTER = 'AFTER';
	/**
	 * use to say that in creation of in-line element text will be insert to the front of and after element
	 */
	const MARC_OPTION_BOTH = 'BOTH';
}
?>