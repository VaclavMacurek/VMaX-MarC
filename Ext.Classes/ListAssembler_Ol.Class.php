<?php

namespace MarC;

use UniCAT\UniCAT;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * pre-set class for generation of simple list with elements ol > li
 */
final class ListAssembler_Ol extends SimpleAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('ol', 'li');
		$this -> Set_Comment('list start', UniCAT::UNICAT_OPTION_ABOVE);
		$this -> Set_Comment('list end', UniCAT::UNICAT_OPTION_BELOW);
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