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
 * pre-set class for generation of simple list with elements ul > li
 */
final class ListAssembler_Ul extends SimpleAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('ul', 'li');
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