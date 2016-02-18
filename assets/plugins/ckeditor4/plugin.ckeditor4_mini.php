//<?php
/**
 * CKEditor4 for MODX Evolution
 *
 * Javascript rich text editor
 *
 * @category    plugin
 * @version     4.5.7.0
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal    @properties &width=Width;text;200px &height=Height;text;200px
 * @internal    @events OnRichTextEditorRegister,OnRichTextEditorInit,OnInterfaceSettingsRender
 * @internal    @modx_category Manager and Admin
 *
 * @author Deesen / updated: 18.02.2016
 * Latest Updates / Issues on Github : https://github.com/Deesen/ckeditor4-modx-evo
 */
if (!defined('MODX_BASE_PATH')) { die('What are you doing? Get out of here!'); }

// Init
include_once(MODX_BASE_PATH."assets/plugins/ckeditor4/class.modxRTEbridge.inc.php");
$rte = new modxRTEbridge('ckeditor4','mini');

// Overwrite item-parameters
// $rte->set('width',          '150px', 'string' );                             // Overwrite width parameter
// $rte->set('height',         isset($height) ? $height : '150px', 'string' );  // Get/set height from plugin-configuration
// $rte->set('height',         NULL );                                          // Removes "height" completely from editor-init

// Internal Stuff - Don´t touch!
$showSettingsInterface = false; // Show/Hide interface in Modx- / user-configuration (false for "Mini")
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