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
 * pre-set class for generation of list of linked general scripts
 */
final class ScriptsAssembler_IE extends SimpleAssembler
{
	/**
	 * sets used elements
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct('head', 'script');
		$this -> Set_DisableTopLevel();
		$this -> Set_Comment('IE conditional scripts start', UniCAT::UNICAT_OPTION_ABOVE);
		$this -> Set_Comment('IE conditional scripts end', UniCAT::UNICAT_OPTION_BELOW);
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