//<?php
/**
 * CKEditor4 for MODX Evolution
 *
 * Javascript rich text editor
 *
 * @category    plugin
 * @version     4.5.7.0
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &pluginCustomParams=Custom Parameters <b>(Be careful or leave empty!)</b>;textarea; &pluginFormats=Block Formats;text;p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre &pluginEntityEncoding=Entity Encoding;list;named,numeric,named+numeric,raw;named &pluginEntities=Entities;text; &pluginPathOptions=Path Options;list;Site config,Absolute path,Root relative,URL,No convert;Site config &pluginResizing=Advanced Resizing;list;true,false;false &pluginDisabledButtons=Disabled Buttons;text; &pluginWebTheme=Web Theme;test;webuser &pluginWebPlugins=Web Plugins;text; &pluginWebButtons1=Web Buttons 1;text;Bold,Italic,Underline,Strike,Cleanup,JustifyLeft,JustifyCenter,JustifyRight &pluginWebButtons2=Web Buttons 2;text;Link,Unlink,Image,Smiley,Undo,Redo &pluginWebButtons3=Web Buttons 3;text; &pluginWebButtons4=Web Buttons 4;text; &pluginWebAlign=Web Toolbar Alignment;list;ltr,rtl;ltr &width=Width;text;100% &height=Height;text;400
 * @internal    @events OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender 
 * @internal    @modx_category Manager and Admin
 *
 * @author Deesen / updated: 18.02.2016
 * Latest Updates / Issues on Github : https://github.com/Deesen/ckeditor4-modx-evo
 */
if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

// Init
include_once(MODX_BASE_PATH."assets/plugins/ckeditor4/class.modxRTEbridge.inc.php");
$rte = new modxRTEbridge('ckeditor4');

// Overwrite item-parameters
// $rte->set('width',          '75%', 'string' );                               // Overwrite width parameter
// $rte->set('height',         isset($height) ? $height : '400px', 'string' );  // Get/set height from plugin-configuration
// $rte->set('height',         NULL );                                          // Removes "height" completely from editor-init

// Internal Stuff - Don´t touch!
$showSettingsInterface = true;  // Show/Hide interface in Modx- / user-configuration (false for "Mini")
$editorLabel = $rte->pluginParams['editorLabel'];

$e = &$modx->event;
switch ($e->name) {
    // register for manager
    case "OnRichTextEditorRegister":
        $e->output($editorLabel);
        break;

    // render script for JS-initialization
    case "OnRichTextEditorInit":
        if ($editor === $editorLabel) {
            $script = $rte->getEditorScript();
            $e->output($script);
        };
        break;

    // render Modx- / User-configuration settings-list
    case "OnInterfaceSettingsRender":
        if( $showSettingsInterface === true ) {
            $html = $rte->getModxSettings();
            $e->output($html);
        };
        break;

    default :
        return; // important! stop here!
        break;
}