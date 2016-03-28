<?php

namespace MarC;

use UniCAT\UniCAT;

/**
 * pre-set class for generation of table row with elements div > div
 *
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 */

final class MenuAssembler_SelectOptgroupOption extends DualAssembler
{
	/**
	 * sets used elements
	 */
	public function __construct()
	{
		parent::__construct('select', 'optgroup', 'option');
		$this -> Set_MiddleLevelContentAttribute('label');
		$this -> Set_SubLevelContentAttribute('value');
		$this -> Set_ChoiceAttribute('selected');
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