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
 * pre-set class for generation of simple list with elements ol > li
 */
final class RootAssembler_Html extends UniqueAssembler
{
	/**
	 * sets used elements
	 */
	public function __construct()
	{
		parent::__construct('html', array('head', 'body'));
	}
	
	/**
	 * erases non-static variables of parent class
	 */
	public function __destruct()
	{
		parent::__destruct();
	}
}

?>