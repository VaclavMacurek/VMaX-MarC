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
 * interface for class ElementListSetting
 */
interface I_MarC_Expressions_ElementListSetting
{
	/**
	 * pattern for identification of element definition in DTD file
	 */
	const MARC_PATTERN_DEFINITION_ELEMENT_NAMECONTENT = '/\<\!ELEMENT (?<ElementName>[^\>\x20]*)[[:space:]]+(?<ElementSetting>[^\>]*)\>/s';
	/**
	 * pattern for identification of element's siblings in DTD file
	 */
	const MARC_PATTERN_DEFINITION_ELEMENT_CONTENT = '/(?<Element>\#{0,1}[[:alnum:]]{1,}\:{0,1}[[:alnum:]]{0,})/';
	/**
	 * pattern for identification of entity definition in DTD file;
	 * this is one possible way how to extract all entities;
	 * complexive expression would not exctract some entities
	 */
	const MARC_PATTERN_DEFINITION_ENTITY_BLOCK = '/<!ENTITY ([^\>]+)>/s';
	/**
	 * pattern for extraction of name and content of entities
	 */
	const MARC_PATTERN_DEFINITION_ENTITY_NAMECONTENT = '/\%[[:space:]]+(?<EntityName>.+)[[:space:]]+"(?<EntitySetting>.+)"/';
	/**
	 * pattern for identification of entities used in definition of elements and else entities
	 */
	const MARC_PATTERN_DEFINITION_ENTITY_USED = '/(?<Entities>%[a-zA-Z0-9]{1,}\.{0,1}[a-zA-Z0-9]{0,};)/';
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
}

/**
 * interface for trait ElementAttributesStylesSetting
 */
interface I_MarC_Expressions_StylesAttributesSetting
{
	/**
	 * pattern for identifying of correct form of stylesheet name
	 */
	const MARC_PATTERN_STYLESHEETNAME = '/^[^0-9\_\-][^\-][a-zA-Z0-9\_][^\_\-]/';
	/**
	 * pattern for identifying of correct form of attribute name
	 */
	const MARC_PATTERN_ATTRIBUTENAME = '/^[^\-][a-zA-Z\-]/';
	/**
	 * pattern for identifying of correct form of style name
	 */
	const MARC_PATTERN_STYLENAME = '/^[^0-9][a-zA-Z\-]/i';
}

/**
 * union interface of chosen expressions;
 * other set of expressions is only for inner
 */
interface I_MarC_Expressions_Union extends 	I_MarC_Expressions_Elements,
									I_MarC_Expressions_StylesAttributesSetting
{
}

?>