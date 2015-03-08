<?php

namespace MarC;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 */

/**
 * interface for class ElementListSetting
 */
interface I_MarC_Expressions_ElementListSetting
{
	const MARC_PATTERN_DEFINITION_ANYELEMENT = '/<!ELEMENT/';
	const MARC_PATTERN_DEFINITION_EMPTYELEMENT = '/<!ELEMENT (.*) EMPTY>/i';
	const MARC_PATTERN_DEFINITION_ENTITY = '/%(.*)\;/i';
}

/**
 * interface for classes ElementListSetting and CodeGenerator
 */
interface I_MarC_Expressions_Elements
{
	/**
	 * pattern for opening form of element name
	 */
	const MARC_PATTERN_NAME_ELEMENT_OPEN = '/^[a-zA-Z0-9]{1,}\:{0,1}[a-zA-Z0-9]{0,}$/i';
	/**
	 * pattern for closing form of element name
	 */
	const MARC_PATTERN_NAME_ELEMENT_CLOSE = '/^\/{1}[a-zA-Z0-9]{1,}\:{0,1}[a-zA-Z0-9]{0,}$/i';
	/**
	 * pattern for opening form of IE condition
	 */
	const MARC_PATTERN_NAME_IECONDITION_OPEN = '/^!--\[if (.*)\]$/i';
	/**
	 * pattern for closing form of IE condition
	 */
	const MARC_PATTERN_NAME_IECONDITION_CLOSE = '/^!\[endif\]--$/i';
}

/**
 * interface for trait ElementAttributesStylesSetting
 */
interface I_MarC_Expressions_StylesAttributesSetting
{
	/**
	 * pattern for identifying of correct form of stylesheet name
	 */
	const MARC_PATTERN_STYLESHEETNAME = '/^[^0-9\_\-][^\-][a-zA-Z0-9\_][^\_\-]/i';
	/**
	 * pattern for identifying of correct form of attribute name
	 */
	const MARC_PATTERN_ATTRIBUTENAME = '/^[^\-][a-zA-Z\-]/i';
	/**
	 * pattern for identifying of correct form of style name
	 */
	const MARC_PATTERN_STYLENAME = '/^[^0-9][a-zA-Z\-]/i';
}

/**
 * union interface of chosen expressions
 */
interface I_MarC_Expressions_Union extends I_MarC_Expressions_Elements, I_MarC_Expressions_StylesAttributesSetting
{
}

?>