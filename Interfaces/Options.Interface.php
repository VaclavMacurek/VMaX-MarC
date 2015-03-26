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
	 * use in code assembling function to say that no text is included
	 */
	const MARC_OPTION_NOTEXT = 'NOTEXT';
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

?>