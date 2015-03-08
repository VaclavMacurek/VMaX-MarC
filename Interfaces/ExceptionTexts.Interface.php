<?php

namespace MarC;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * setting of content for multi-leveled constructions
 */

interface I_MarC_Texts_Exceptions
{
	/**
	 * explanation of possible alternative way
	 */
	const MARC_EXCEPTIONS_XPLN_EMPTYATTR = 'EXPLANATION: Use function %s to allow attributes without value';
	/**
	 * explanation of empty element
	 */
	const MARC_EXCEPTIONS_XPLN_EMPTYELMT = 'EXPLANATION: Empty element cannot wrap text';
	/**
	 * explanation of closed element
	 */
	const MARC_EXCEPTIONS_XPLN_CLOSEDELMT = 'EXPLANATION: Closed element has to wrap text';
	/**
	 * explanation of used element
	 */
	const MARC_EXCEPTIONS_XPLN_USEDELMT = 'EXPLANATION: Styles and attributes may be set only to used elements';
}

?>