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
 * interface with placeholders, constants that are used only internally
 */
interface I_MarC_Placeholders
{
	/**
	 * use in code assembling function to say that no attribute is included
	 */
	const MARC_OPTION_NOATTR = 'NO_ATTRIBUTE';
	/**
	 * use in code assembling function to say that no style is included
	 */
	const MARC_OPTION_NOSTL = 'NO_STYLE';

	/**
	 * use to set alternative element for setting of stylers and attributes - to avoid conflict of the same elements as top, mid or sub element
	 */
	const MARC_PLACEHOLDER_TOPELMT = 'TOP_ELMT';
	const MARC_PLACEHOLDER_MIDELMT = 'MID_ELMT';
	const MARC_PLACEHOLDER_SUBELMT = 'SUB_ELMT';
}

?>