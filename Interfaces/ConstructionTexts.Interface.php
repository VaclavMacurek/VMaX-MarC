<?php

namespace MarC;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014, Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * interface with construction texts of class CodeGenerator
 */
interface I_MarC_Texts_CodeGenerator
{
	/**
	 * order of %s: style name, style value
	 */
	const MARC_CODE_STYLES_1 = '%s: %s;';
	/**
	 * order of %s: styles
	 */
	const MARC_CODE_STYLES_FULL = 'style="%s"';
	/**
	 * order of %s: attribute name, attribute value
	 */
	const MARC_CODE_ATTRIBUTES = '%s="%s"';
	
	/**
	 * order of %s: element name, attributes and styles, text, element name
	 */
	const MARC_CODE_ELEMENT_CLOSED_ML = "\n<%s %s>\n\t%s\n<%s>\n";
	const MARC_CODE_ELEMENT_CLOSED_1L = "\n<%s %s>%s<%s>\n";
	/**
	 * order of %s: element name, attributes and styles
	 */
	const MARC_CODE_ELEMENT_EMPTY_XML = "\n<%s %s />\n";
	const MARC_CODE_ELEMENT_EMPTY_HTML = "\n<%s %s>\n";
}

/**
 * interface with construction text of comments
 */
interface I_MarC_Texts_Comments
{
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_COMMENT = "\n<!-- %s -->\n";
}

?>