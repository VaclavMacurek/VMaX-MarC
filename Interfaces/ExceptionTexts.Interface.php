<?php

namespace MarC;

use UniCAT\I_UniCAT_Texts_Exceptions;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * exceptions explanation texts
 */
interface I_MarC_Texts_Exceptions extends I_UniCAT_Texts_Exceptions
{
	/**
	 * explanation of possible alternative way
	 */
	const MARC_EXCEPTIONS_XPLN_EMPTYATTR = 'EXPLANATION: Use function %s to allow attributes without value';
	/**
	 * explanation of empty element
	 */
	const MARC_EXCEPTIONS_XPLN_EMPTYELMT = 'EXPLANATION: Empty element cannot wrap text - use called function to add text to the front of element or behind element';
	/**
	 * explanation of closed element
	 */
	const MARC_EXCEPTIONS_XPLN_CLOSEDELMT = 'EXPLANATION: Closed element has to wrap text - using of function Set_Text without parameter is valid';
	/**
	 * explanation of used element
	 */
	const MARC_EXCEPTIONS_XPLN_USEDELMT = 'EXPLANATION: Styles and attributes may be set only to used elements';
	/**
	 * explanation of element tree validity
	 */
	const MARC_EXCEPTIONS_XPLN_ELMTTREEVALID = 'EXPLANARTION: Element tree validity can be checked only for at least two elements';
	/**
	 * explanation by too many options
	 */
	const MARC_EXCEPTIONS_XPLN_DTDFILE = 'EXPLANATION: Read DTD file to see allowed options';
	
}

?>