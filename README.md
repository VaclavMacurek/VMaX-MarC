VMaX-MarC
=========
**V**áclav **Ma**cůrek e**x**perimental - **Mar**kup **c**ode

Brief history of project
========================

Originally developed only for my needs - and for generation of only HTML, as part of project that allowed generation of also CSS and JavaScript.
Later I allowed also other sets of elements.
And in February of 2015, after separation from original project, I _published_ it for public using, if someone would like to use it.

And development goes on.
Some releases are very important because they change behaviour of one or more parts.
And some ones only fix error that came from inattention.

Development
===========

You may write your ideas for further development on G+ community of the same name as this project [VMaX-MarC](https://plus.google.com/communities/102534414600899651018) or here.
Mostly awaited are ideas for

*  new classes (they have to keep current style of universal classes capable to create any code of the same structure)
*  new or improved features/functionalities of classes (like setting of style and attribute values, content or so)

Purpose (and brief history) of classes and some other parts
===========================================================

**Advanced classes** (Adv.Classes)
----------------------------------

DualAssembler

*  designed to create dropdown menu in all possible configurations

    >     <select>
    >         <optgroup>
    >             <option></option>
    >         </optgroup>
    >         <option></option>
    >     </select>

*  rewritten to use any elements


FluentElement

*  designed to use CodeGenerator with fluent interface (without rewriting of CodeGenerator for fluent interface) and simplify generation of elements


SimpleAssembler

*  designed to create row of the same elements inside else element (or the same element)

    >     <tr>
    >         <td></td>
    >     </tr>

*  rewritten to use any elements


UniqueAssembler

*  designed to create row of elements that are unique in set (parent element and siblings)

    >     <html>
    >         <head></head>
    >         <body></body>
    >     </html>

**Basic classes** (Base.Classes)
-------------------------------

CodeGenerator

*  designed to create any element


DTDLine

*  designed to create DTD header of pages
*  rewritten to create header of XML files too


ElementListSetting

*  designed to parse DTD file and create list of elements ready to use
*  rewritten to also provide simple validation of thought element tree


MarC

*  provides access to constants written in interfaces


** Exceptions ** (Exceptions)
-----------------------------

MarC_Exception

*  designed only to clearly say that error has happened in VMaX-MarC


** Extending classes ** (Ext.Classes)
-------------------------------------

LinksAssembler_All

*  extends class SimpleAssembler
*  set to create

    >     <link />


LinksAssembler_IE

*  extends class SimpleAssembler
*  set to create

    >     <!--[if IE]>
    >         <link />
    >     <![endif]-->


ListAssembler_Ol

*  extends class SimpleAssembler
*  set to create

    >     <ol>
    >         <li></li>
    >     </ol>


ListAssembler_Ul

*  extends class SimpleAssembler
*  set to create

    >     <ul>
    >         <li></li>
    >     </ul>


MenuAssembler_SelectOptgroupOption

*  extends class DualAssembler
*  set to create

    >     <select>
    >         <optgroup>
    >             <option></option>
    >         </optgroup>
    >     </ol>


RootAssembler_Html

*  extends class UniqueAssembler
*  set to create

    >     <html>
    >         <head></head>
    >         <body></body>
    >     </html>


RowAssembler_Div

*  extends class SimpleAssembler
*  set to create

    >     <div>
    >         <div></div>
    >     </div>


RowAssembler_Tr

*  extends class SimpleAssembler
*  set to create

    >     <tr>
    >         <td></td>
    >     </tr>


ScriptsAssembler_All

*  extends class SimpleAssembler
*  set to create

    >     <script></script>


ScriptsAssembler_IE

*  extends class SimpleAssembler
*  set to create

    >     <!--[if IE]>
    >         <script></script>
    >     <![endif]-->


Releases review
===============

All exact changes may be checked via Sourceforge.net web UI.

[1.](https://sourceforge.net/p/vmaxmarc/code/1/) file upload

  -  added files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     DTD/DoctypeHtml4Frameset.html
  >     DTD/DoctypeHtml4Transitional.html
  >     DTD/DoctypeXhtml1Transitional.html
  >     DTD/DoctypeXhtml1TransitionalXml.html
  >     DTD/DoctypeXml.html
  >     DTD/XmlHead.html
  >     DTD/html4-frameset.dtd
  >     DTD/html4-loose.dtd
  >     DTD/html4-strict.dtd
  >     DTD/svg10.dtd
  >     DTD/wml13.dtd
  >     DTD/xhtml1-frameset.dtd
  >     DTD/xhtml1-strict.dtd
  >     DTD/xhtml1-transitional.dtd
  >     DTD/xsl-fo.dtd
  >     DTD/xslt10.dtd
  >
  >     Exceptions/MarC_Exception.Exception.php
  >
  >     Ext.Classes/ListAssembler_Ol.Class.php
  >     Ext.Classes/ListAssembler_Ul.Class.php
  >     Ext.Classes/MenuAssembler_SelectOptgroupOption.Class.php
  >     Ext.Classes/RootAssembler_Html.Class.php
  >     Ext.Classes/RowAssembler_Div.Class.php
  >     Ext.Classes/RowAssembler_Tr.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Expressions.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     License/lgpl-3.0.txt
  >
  >     Traits/StylesAttributesSetting.Trait.php
  >
  >     MarC.php


[2.](https://sourceforge.net/p/vmaxmarc/code/2/) code change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php


[3.](https://sourceforge.net/p/vmaxmarc/code/3/) code change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Exceptions/MarC_Exception.Exception.php
  >
  >     Ext.Classes/ListAssembler_Ol.Class.php
  >     Ext.Classes/ListAssembler_Ul.Class.php
  >     Ext.Classes/MenuAssembler_SelectOptgroupOption.Class.php
  >     Ext.Classes/RootAssembler_Html.Class.php
  >     Ext.Classes/RowAssembler_Div.Class.php
  >     Ext.Classes/RowAssembler_Tr.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Expressions.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     Traits/StylesAttributesSetting.Trait.php
  >
  >     MarC.php


[4.](https://sourceforge.net/p/vmaxmarc/code/4/) code change

  -  changed files
  >     DTD/DoctypeXhtml1TransitionalXml.html
  >     DTD/DoctypeXml.html


[5.](https://sourceforge.net/p/vmaxmarc/code/5/) code change

  -  changed files
  >     Adv.Classes/UniqueAssembler.Class.php


[6.](https://sourceforge.net/p/vmaxmarc/code/6/) charset change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Exceptions/MarC_Exception.Exception.php
  >
  >     Ext.Classes/ListAssembler_Ol.Class.php
  >     Ext.Classes/ListAssembler_Ul.Class.php
  >     Ext.Classes/MenuAssembler_SelectOptgroupOption.Class.php
  >     Ext.Classes/RootAssembler_Html.Class.php
  >     Ext.Classes/RowAssembler_Div.Class.php
  >     Ext.Classes/RowAssembler_Tr.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Expressions.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     MarC.php


[7.](https://sourceforge.net/p/vmaxmarc/code/7/) charset change

  -  changed files
  >     DTD/XmlHead.html


[8.](https://sourceforge.net/p/vmaxmarc/code/8/) charset change

  -  changed files
  >     Traits/StylesAttributesSetting.Trait.php


[9.](https://sourceforge.net/p/vmaxmarc/code/9/) code change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >
  >     Traits/StylesAttributesSetting.Trait.php


[10.](https://sourceforge.net/p/vmaxmarc/code/10/) code change, files change

  -  added files
  >     Ext.Classes/LinksAssembler_All.Class.php
  >     Ext.Classes/LinksAssembler_IE.Class.php
  >     Ext.Classes/ScriptsAssembler_All.Class.php
  >     Ext.Classes/ScriptsAssembler_IE.Class.php
  >
  >     Traits/ConditionaComments.Trait.php


  -  deleted files
  >     DTD/html4-frameset.dtd
  >     DTD/html4-loose.dtd
  >     DTD/html4-strict.dtd


  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Ext.Classes/RootAssembler_Html.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Expressions.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     Traits/ConditionaComments.Trait.php
  >
  >     MarC.php


[11.](https://sourceforge.net/p/vmaxmarc/code/11/) code change

  -  changed files
  >     Adv.Classes/UniqueAssembler.Class.php


[12.](https://sourceforge.net/p/vmaxmarc/code/12/) code change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Exceptions/MarC_Exception.Exception.php
  >
  >     Ext.Classes/LinksAssembler_All.Class.php
  >     Ext.Classes/LinksAssembler_IE.Class.php
  >     Ext.Classes/ListAssembler_Ol.Class.php
  >     Ext.Classes/ListAssembler_Ul.Class.php
  >     Ext.Classes/MenuAssembler_SelectOptgroupOption.Class.php
  >     Ext.Classes/RootAssembler_Html.Class.php
  >     Ext.Classes/RowAssembler_Div.Class.php
  >     Ext.Classes/RowAssembler_Tr.Class.php
  >     Ext.Classes/ScriptsAssembler_All.Class.php
  >     Ext.Classes/ScriptsAssembler_IE.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Expressions.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     Traits/ConditionalComments.Trait.php
  >     Traits/StylesAttributesSetting.Trait.php
  >
  >     MarC.php


[13.](https://sourceforge.net/p/vmaxmarc/code/13/) code change

  -  changed files
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/ElementListSetting.Class.php


[14.](https://sourceforge.net/p/vmaxmarc/code/14/) code change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Expressions.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     Traits/ConditionalComments.Trait.php
  >     Traits/StylesAttributesSetting.Trait.php


[15.](https://sourceforge.net/p/vmaxmarc/code/15/) files change

  -  deleted files
  >     DTD/DoctypeHtml4Frameset.html
  >     DTD/DoctypeHtml4Transitional.html
  >     DTD/DoctypeXhtml1Transitional.html
  >     DTD/DoctypeXhtml1TransitionalXml.html
  >     DTD/DoctypeXml.html


[16.][] files change

  -  deleted files
  >     DTD/XmlHead.html


[17.](https://sourceforge.net/p/vmaxmarc/code/17/) code change

  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Ext.Classes/LinksAssembler_All.Class.php
  >     Ext.Classes/LinksAssembler_IE.Class.php
  >     Ext.Classes/RootAssembler_Html.Class.php
  >     Ext.Classes/RowAssembler_Div.Class.php
  >     Ext.Classes/ScriptsAssembler_All.Class.php
  >     Ext.Classes/ScriptsAssembler_IE.Class.php
  >
  >     Interfaces/ExceptionTexts.Interface.php
  >     Interfaces/Options.Interface.php
  >
  >     Traits/ConditionalComments.Trait.php
  >     Traits/StylesAttributesSetting.Trait.php
  >
  >     MarC.php


*  [18.](https://sourceforge.net/p/vmaxmarc/code/18/) code change, files change

  -  added files
  >     Interfaces/Placeholders.Interface.php


  -  changed files
  >     Base.Classes/CodeGenerator.Class.php


*  [19.](https://sourceforge.net/p/vmaxmarc/code/19/) code change, files change

  -  added files
  >     Adv.Classes/SingleElement.Class.php


  -  changed files
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >
  >     MarC.php


*  [20.](https://sourceforge.net/p/vmaxmarc/code/20/) code change

  -  changed files
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >
  >     Traits/StylesASttributesSetting.Trait.php


*  [21.](https://sourceforge.net/p/vmaxmarc/code/21/) code change, files change

  -  added files
  >  Adv.Classes/FluentElement.Class.php


  -  deleted files
  >  Adv.Classes/SingleElement.Class.php


  -  changed files
  >     Adv.Classes/DualAssembler.Class.php
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/DTDLine.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >     Base.Classes/MarC.Class.php
  >
  >     Ext.Classes/LinksAssembler_All.Class.php
  >     Ext.Classes/LinksAssembler_IE.Class.php
  >     Ext.Classes/ListAssembler_Ol.Class.php
  >     Ext.Classes/ListAssembler_Ul.Class.php
  >     Ext.Classes/MenuAssembler_SelectOptgroupOption.Class.php
  >     Ext.Classes/RootAssembler_Html.Class.php
  >     Ext.Classes/RowAssembler_Div.Class.php
  >     Ext.Classes/RowAssembler_Tr.Class.php
  >     Ext.Classes/ScriptsAssembler_All.Class.php
  >
  >     Traits/ConditionalComments.Trait.php
  >     Traits/StylesAttributesSetting.Trait.php
  >
  >     MarC.php


*  [22.](https://sourceforge.net/p/vmaxmarc/code/22/) code change

  -  changed files
  >     Adv.Classes/SimpleAssembler.Class.php
  >     Adv.Classes/UniqueAssembler.Class.php
  >
  >     Base.Classes/CodeGenerator.Class.php
  >     Base.Classes/ElementListSetting.Class.php
  >
  >     Interfaces/ConstructionTexts.Interface.php
  >     Interfaces/ExceptionTexts.Interface.php
  >
  >     Traits/ConditionalComments.Trait.php