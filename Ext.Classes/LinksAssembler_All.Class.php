<?php

namespace MarC;

use UniCAT\UniCAT;

/**
 * @package VMaX-MarC
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * pre-set class for generation of list of linked general stylesheets
 */
final class LinksAssembler_All extends SimpleAssembler
{
	/**
	 * sets used elements
	 */
	public function __construct()
	{
		parent::__construct('', 'link');
		$this -> Set_Comment('general styles start', UniCAT::UNICAT_OPTION_ABOVE);
		$this -> Set_Comment('general styles end', UniCAT::UNICAT_OPTION_BELOW);
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