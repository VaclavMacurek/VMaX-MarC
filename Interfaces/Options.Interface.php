<?php

namespace MarC;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 */

/**
 * interface for class SimpleAssembler;
 * support options for content usage
 */
interface I_MarC_Options_ContentUsage
{
	/**
	 * use to say that key or value will be used as text closed in element
	 */
	const MARC_OPTION_ELMTTEXT = 'ELEMENT_TEXT';
	/**
	 * use to say that key or value will be used as value of any attribute
	 */
	const MARC_OPTION_ATTRVAL = 'ATTRIBUTE_VALUE';
	/**
	 * use to say that key or value will not be used
	 */
	const MARC_OPTION_NOTUSE = 'NOT_USE';
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
interface I_MarC_Options_ElementsSetting
{
	/**
     * use to say that element will be empty
     */
	const MARC_OPTION_EMPTY = 'EMPTY';
	/**
	 * use to say that element will allow only simple text
	 */
	const MARC_OPTION_DATA = '#PCDATA';
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
	const MARC_OPTION_LEFT = 'LEFT';
	/**
	 * use to say that in creation of in-line element text will be insert after element
	 */
	const MARC_OPTION_RIGHT = 'RIGHT';
	/**
	 * use to say that in creation of in-line element text will be insert to the front of and after element
	 */
	const MARC_OPTION_BOTH = 'BOTH';
}

interface I_MarC_Options_Union extends	I_MarC_Options_ContentUsage,
								I_MarC_Options_ValuesSeparation,
								I_MarC_Options_ElementsSetting,
								I_MarC_Options_InLineSetting
{
}
?>