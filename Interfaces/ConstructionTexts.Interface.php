<?php

namespace MarC;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
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
	const MARC_CODE_STYLES_ONE = '%s: %s;';
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
	 * order of %s: text, element name, attributes and styles, text, element name, text
	 */
	const MARC_CODE_ELEMENT_CLOSED_IN = "\n%s <%s %s>%s<%s> %s\n";

	/**
	 * order of %s: element name, attributes and styles
	 */
	const MARC_CODE_ELEMENT_EMPTY_ML = "\n<%s %s />\n";
	/**
	 * order of %s: text, element name, attributes and styles, text
	 */
	const MARC_CODE_ELEMENT_EMPTY_IN = "\n%s <%s %s /> %s\n";
}

/**
 * interface with construction text of comments
 */
interface I_MarC_Texts_Comments
{
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_COMMENT = "\n\t\n<!-- %s -->\n\t\n";
}

/**
 * interface with construction texts of conditional comments
 */
interface I_MarC_Texts_ConditionalComments
{
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_IE = "\n<!--[if IE]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_IE7 = "\n<!--[if IE 7]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_IE8 = "\n<!--[if IE 8]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_IE9 = "\n<!--[if IE 9]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_IE10 = "\n<!--[if IE 10]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_IE11 = "\n<!--[if IE 11]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTIE7 = "\n<!--[if lt IE 7]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTIE8 = "\n<!--[if lt IE 8]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTIE9 = "\n<!--[if lt IE 9]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTIE10 = "\n<!--[if lt IE 10]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTIE11 = "\n<!--[if lt IE 11]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTEIE7 = "\n<!--[if lte IE 7]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTEIE8 = "\n<!--[if lte IE 8]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTEIE9 = "\n<!--[if lte IE 9]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTEIE10 = "\n<!--[if lte IE 10]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_LTEIE11 = "\n<!--[if lte IE 11]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTIE7 = "\n<!--[if gt IE 7]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTIE8 = "\n<!--[if gt IE 8]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTIE9 = "\n<!--[if gt IE 9]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTIE10 = "\n<!--[if gt IE 10]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTIE11 = "\n<!--[if gt IE 11]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTEIE7 = "\n<!--[if gte IE 7]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTEIE8 = "\n<!--[if gte IE 8]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTEIE9 = "\n<!--[if gte IE 9]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTEIE10 = "\n<!--[if gte IE 10]>\n\t%s\n\t\n<![endif]-->\n";
	/**
	 * order of %s: comment text
	 */
	const MARC_CODE_CONDCOMMENT_GTEIE11 = "\n<!--[if gte IE 11]>\n\t%s\n\t\n<![endif]-->\n";
}

/**
 * interface for class DTDLine;
 * these constants are not construction texts with possibility of adding of any text;
 * they are inserterd into code as they are
 */
interface I_MarC_Texts_CodeHeading
{
	/**
	 * XHTML1 Strict
	 */
	const MARC_CODE_XHTML1STRICT = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
	/**
	 * XHTML1 Transitional
	 */
	const MARC_CODE_XHTML1TRANS = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/xhtml1-transitional.dtd">';
	/**
	 * XHTML1 Frameset
	 */
	const MARC_CODE_XHTML1FRAME = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">';
	/**
	 * XML
	 */
	const MARC_CODE_XML = '<?xml version="1.0" encoding="UTF-8" ?>';
}

interface I_MarC_Texts_Union extends	I_MarC_Texts_CodeHeading,
								I_MarC_Texts_ConditionalComments
{

}

?>