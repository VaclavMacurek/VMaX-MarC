<?php

/**
 * @package VMaX-MarC
 *
 * generation of any XML code is allowed;
 * DTD file with written elements is neccessary;
 * depends on VMaX-UniCAT
 *
 * @author Václav Macůrek <VaclavMacurek@seznam.cz>
 * @copyright 2014 - 2016 Václav Macůrek
 *
 * @license GNU LESSER GENERAL PUBLIC LICENSE version 3.0
 *
 * classes for (X)HTML/XML code generation;
 */

define('MARC_ADR', __DIR__.'/');

/*
 * VMaX-MarC is dependent on VMaX-UniCAT
 */
if(!defined('UNICAT_ADR'))
{
	die('VMaX-UniCAT not available');
}

/*
 * Interfaces
 */
require_once MARC_ADR.'Interfaces/ConstructionTexts.Interface.php';
require_once MARC_ADR.'Interfaces/ExceptionTexts.Interface.php';
require_once MARC_ADR.'Interfaces/Placeholders.Interface.php';
require_once MARC_ADR.'Interfaces/Expressions.Interface.php';
require_once MARC_ADR.'Interfaces/Options.Interface.php';
/*
 * Traits
 */
require_once MARC_ADR.'Traits/StylesAttributesSetting.Trait.php';
require_once MARC_ADR.'Traits/ConditionalComments.Trait.php';
/*
 * Exceptions
 */
require_once MARC_ADR.'Exceptions/MarC_Exception.Exception.php';
/*
 * Base classes (Base.Classes);
 * support and simple code generation classes
 */
require_once MARC_ADR.'Base.Classes/MarC.class.php';
require_once MARC_ADR.'Base.Classes/ElementListSetting.Class.php';
require_once MARC_ADR.'Base.Classes/DTDLine.Class.php';
require_once MARC_ADR.'Base.Classes/CodeGenerator.Class.php';
/*
 * Advanced classes (Adv.Classes);
 * classes for generation of (not-only) larger code blocks
 */
require_once MARC_ADR.'Adv.Classes/FluentElement.Class.php';
require_once MARC_ADR.'Adv.Classes/SimpleAssembler.Class.php';
require_once MARC_ADR.'Adv.Classes/UniqueAssembler.Class.php';
require_once MARC_ADR.'Adv.Classes/DualAssembler.Class.php';
/*
 * Extension classes extensions (Ext.Classes);
 * final childs of advanced classes
 *
 * SIMPLE ASSEMBLER EXTENSIONS
 */
require_once MARC_ADR.'Ext.Classes/ListAssembler_Ol.Class.php';
require_once MARC_ADR.'Ext.Classes/ListAssembler_Ul.Class.php';
require_once MARC_ADR.'Ext.Classes/RowAssembler_Div.Class.php';
require_once MARC_ADR.'Ext.Classes/RowAssembler_Tr.Class.php';
require_once MARC_ADR.'Ext.Classes/LinksAssembler_All.Class.php';
require_once MARC_ADR.'Ext.Classes/LinksAssembler_IE.Class.php';
require_once MARC_ADR.'Ext.Classes/ScriptsAssembler_All.Class.php';
require_once MARC_ADR.'Ext.Classes/ScriptsAssembler_IE.Class.php';
/*
 * UNIQUE ASSEMBLER EXTENSIONS
 */
require_once MARC_ADR.'Ext.Classes/RootAssembler_Html.Class.php';
/*
 * DUAL ASSEMBLER EXTENSIONS
 */
require_once MARC_ADR.'Ext.Classes/MenuAssembler_SelectOptgroupOption.Class.php';

?>