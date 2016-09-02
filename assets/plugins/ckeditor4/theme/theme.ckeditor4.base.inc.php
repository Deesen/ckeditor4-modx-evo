<?php
/*
 * All available config-params of CKEditor4
 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
 *
 * Belows default configuration setup assures all editor-params have a fallback-value, and type per key is known
 * $this->set( $editorParam, $value, $type, $emptyAllowed=false )
 *
 * $editorParam     = param to set
 * $value           = value to set
 * $type            = string, number, bool, json (array or string)
 * $emptyAllowed    = true, false (allows param:'' instead of falling back to default)
 * If $ckConfigParam is empty and $emptyAllowed is true, $defaultValue will be ignored
 *
 * $modxParams holds an array of actual Modx- / user-settings
 *
 * */

// CKEditor4 - Base config --- See gesettings/bridge.ckeditor4.inc.php for more base params

if( !empty( $params['pluginFormats'] )) {
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-format_tags
    $tagsStr = str_replace(',', ';', $params['pluginFormats']);
    $this->set('format_tags', $tagsStr, 'string' );

    // Important: Each entry must have a corresponding configuration 'format_(tagName)' - let´s take care
    $tagsArr = explode(';', $tagsStr);
    foreach( $tagsArr as $tag ) {
        $this->set('format_'.$tag, '{ element: "'.$tag.'" }', 'object' );
    };
};

// Avoid set empty contentsCss
if( !empty( $modx->config['editor_css_path'] )) {
    $this->set('contentsCss', $modx->config['editor_css_path'], 'string');
}

$this->set('language',              $this->lang('lang_code'),       'string' );   // Set default language
$this->set('skin',                  'moono',                        'string' );   // Set default skin
$this->set('skin',                  $modxParams['skin'] );                        // Overwrite with Modx-setting
$this->set('enterMode',             'p',                            'constant' ); // Translated via bridge.ckeditor4.inc.php
$this->set('width',                 $pluginParams['width'],         'string' );   // Set width as per plug-configuration
$this->set('height',                $pluginParams['height'],        'string' );   // Set width as per plug-configuration
$this->set('extraPlugins',          'dialogadvtab,tableresize,stylescombo,embed,showborders,nbsp', 'string', true );    // Enhance default-plugin-setup

// Filebrowser config
$this->set('filebrowserBrowseUrl', 'media/browser/[+which_browser+]/browse.php?opener=ckeditor4&type=files', 'string');
$this->set('filebrowserImageBrowseUrl', 'media/browser/[+which_browser+]/browse.php?opener=ckeditor4&type=images', 'string');
$this->set('filebrowserFlashBrowseUrl', 'media/browser/[+which_browser+]/browse.php?opener=ckeditor4&type=flash', 'string');


/*
$this->set('toolbarGroups',     '[
                { name: "document",    groups: [ "mode", "document", "doctools" ] },
                { name: "clipboard",   groups: [ "clipboard", "undo" ] },
                { name: "editing",     groups: [ "find", "selection", "spellchecker" ] },
                { name: "forms" },
                "/",
                { name: "basicstyles", groups: [ "basicstyles", "cleanup" ] },
                { name: "paragraph",   groups: [ "list", "indent", "blocks", "align", "bidi" ] },
                { name: "links" },
                { name: "insert" },
                "/",
                { name: "styles" },
                { name: "colors" },
                { name: "tools" },
                { name: "others" },
                { name: "about" }
            ]', 'json' );
*/

/*
// http://docs.ckeditor.com/#!/guide/dev_toolbar-section-%22item-by-item%22-configuration
// http://ckeditor.com/forums/CKEditor/Complete-list-of-toolbar-items
$this->set('toolbar',     '[
                { name: "undo",     items: [ "Undo", "Redo" ] }
                ]', 'json' );
*/