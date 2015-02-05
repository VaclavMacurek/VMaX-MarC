<?php

namespace MarC;

use UniCAT\UniCAT;

/**
 * @package VMaX-MarC
 *
 * @author V�clav Mac�rek <VaclavMacurek@seznam.cz>
 * @copyright 2014, V�clav Mac�rek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * pre-set class for generation of simple list with elements ol > li
 */
final class RootAssembler_Html extends UniqueAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('html', array('head', 'body'));
	}
	
	/**
	 * erases non-static variables of parent class
	 *
	 * @return void
	 */
	public function __destruct()
	{
		parent::__destruct();
	}
}

?>