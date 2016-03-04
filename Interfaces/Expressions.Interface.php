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
 * interface for class ElementListSetting
 */
interface I_MarC_Expressions_ElementsSetting
{
	/**
	 * pattern for identification of element definition in DTD file
	 */
	const MARC_XPSN_DTDELMT_NAMECONTENT = '/\<\!ELEMENT (?<ElementName>[^\>\x20]*)[[:space:]]+(?<ElementSetting>[^\>]*)\>/s';
	/**
	 * pattern for identification of element's siblings in DTD file
	 */
	const MARC_XPSN_DTDELMT_CONTENT = '/(?<Element>\#{0,1}[[:alnum:]]{1,}\:{0,1}[[:alnum:]]{0,})/';
	
	/**
	 * pattern for identification of entity definition in DTD file;
	 * this is one possible way how to extract all entities;
	 * complexive expression would not exctract some entities
	 */
	const MARC_XPSN_DTDNTT_BLOCK = '/<!ENTITY ([^\>]+)>/s';
	/**
	 * pattern for extraction of name and content of entities
	 */
	const MARC_XPSN_DTDNTT_NAMECONTENT = '/\%[[:space:]]+(?<EntityName>.+)[[:space:]]+"(?<EntitySetting>.+)"/';
	/**
	 * pattern for identification of entities used in definition of elements and else entities
	 */
	const MARC_XPSN_DTDNTT_USED = '/(?<Entities>%[a-zA-Z0-9]{1,}\.{0,1}[a-zA-Z0-9]{0,};)/';

	/**
	 * pattern for opening form of element name
	 */
	const MARC_XPSN_ADDELMT_NAME = '/^[a-zA-Z0-9]{1,}\:{0,1}[a-zA-Z0-9]{0,}$/i';
	/**
	 * pattern for detection of presence of any element in given text;
	 * for purpose of automatic enabling of one line elements
	 */
	const MARC_XPSN_PSNELMT_GNRDCODE = '/\<[^\>]*\/{0,1}\>(.*){0,}\<\/[^\>]*\>{0,1}/';
}

/**
 * interface for trait ElementAttributesStylesSetting
 */
interface I_MarC_Expressions_StylesAttributesSetting
{
	/**
	 * pattern for identifying of correct form of stylesheet name
	 */
	const MARC_XPSN_STYLESHEETNAME = '/^[^0-9\_\-][^\-][a-zA-Z0-9\_][^\_\-]/';
	/**
	 * pattern for identifying of correct form of attribute name
	 */
	const MARC_XPSN_ATTRIBUTENAME = '/^[^\-][a-zA-Z\-]/';
	/**
	 * pattern for identifying of correct form of style name
	 */
	const MARC_XPSN_STYLENAME = '/^[^0-9][a-zA-Z\-]/i';
}

/**
 * union interface of chosen expressions;
 * other set of expressions is only for inner
 */
interface I_MarC_Expressions_Union extends 	I_MarC_Expressions_ElementsSetting,
									I_MarC_Expressions_StylesAttributesSetting
{
}

?>