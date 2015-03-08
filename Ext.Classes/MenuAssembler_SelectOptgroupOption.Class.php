<?php

namespace MarC;

use UniCAT\UniCAT;

/**
 * pre-set class for generation of table row with elements div > div
 *
 * @package VMaX-MarC
 *
 * @author Václav Macùrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2015 Václav Macùrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 */

final class MenuAssembler_SelectOptgroupOption extends DualAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('select', 'optgroup', 'option');
		$this -> Set_Comment('menu start', UniCAT::UNICAT_OPTION_ABOVE);
		$this -> Set_Comment('menu end', UniCAT::UNICAT_OPTION_BELOW);
		$this -> Set_MidLevelContentAttribute('label');
		$this -> Set_BottomLevelContentAttribute('value');
		$this -> Set_ChoiceAttribute('selected');
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