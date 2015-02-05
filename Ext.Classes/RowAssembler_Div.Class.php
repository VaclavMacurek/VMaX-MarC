<?php

namespace MarC;

use UniCAT\UniCAT;

/**
 * pre-set class for generation of table row with elements div > div
 *
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014, Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 */

final class RowAssembler_Div extends SimpleAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct( array('div', 'div'), array('div', 'span') );
		$this -> Set_Comment('row start', UniCAT::UNICAT_OPTION_ABOVE);
		$this -> Set_Comment('row end', UniCAT::UNICAT_OPTION_BELOW);
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