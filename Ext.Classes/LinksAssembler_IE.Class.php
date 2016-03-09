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
 * pre-set class for generation of list of linked IE-related stylesheets
 */
final class LinksAssembler_IE extends SimpleAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('', 'link');
		$this -> Set_Comment('IE conditional styles start', UniCAT::UNICAT_OPTION_ABOVE);
		$this -> Set_Comment('IE conditional styles end', UniCAT::UNICAT_OPTION_BELOW);
		$this -> Set_ConditionalComment(MarC::MARC_CODE_CONDCOMMENT_IE);	
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