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
 * Check theme.ckeditor4.default.inc.php for more examples
 * */

// element_format is set via bridge as it requires additional Javascript injected

// @todo: customize plugin "stylescombo": http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-stylesSet

if( !empty( $params['pluginFormats'] )) {
    // @todo: format_tags causes error, why? http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-format_tags
    // $this->set('format_tags',       str_replace(',', ';', $params['pluginFormats']), 'string' );
};

$this->set('skin',                  'moono',                        'string' );   // Set default
$this->set('skin',                  $modxParams['skin'] );                        // Overwrite with Modx-setting
$this->set('enterMode',             'p',                            'constant' ); // Translated via bridge.ckeditor4.inc.php
$this->set('contentsCss',           $modx->config['editor_css_path'], 'string' );
$this->set('width',                 $pluginParams['width'],           'string' );
$this->set('height',                $pluginParams['height'],          'string' );
$this->set('extraPlugins',          'dialogadvtab,tableresize,stylescombo,embed,showborders,nbsp', 'string', true );

// $this->set('language',          'en',       'string' );
// $this->set('skin',              'moono',    'string' );
// $this->set('plugins',            '',        'string', true );
// $this->set('extraPlugins',      'dialogadvtab,xy', 'string', true );
// $this->set('removePlugins',     '',         'string', true );

/*
//
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