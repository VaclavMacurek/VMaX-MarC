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
 * exceptions explanation texts
 */
interface I_MarC_Exceptions
{
	/**
	 * explanation of possible alternative way
	 */
	const MARC_XCPT_XPLN_EMPTYATTR = 'EXPLANATION: Use function %s to allow attributes without value';
	/**
	 * explanation of empty element
	 */
	const MARC_XCPT_XPLN_EMPTYELMT = 'EXPLANATION: Empty element cannot wrap text';
	/**
	 * explanation of closed element
	 */
	const MARC_XCPT_XPLN_CLOSEDELMT = 'EXPLANATION: Closed element has to wrap text - use function Set_Text without argument';
	/**
	 * explanation of used element
	 */
	const MARC_XCPT_XPLN_USEDELMT = 'EXPLANATION: Styles and attributes may be set only to used elements';
	/**
	 * explanation by too many options
	 */
	const MARC_XCPT_XPLN_DTDFILE = 'EXPLANATION: Read DTD file to see allowed options';
}

?>