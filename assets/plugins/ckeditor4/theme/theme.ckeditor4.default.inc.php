<?php
/*
 * CKEditor4 for Modx Evolution
 * CK-Base: CKEditor 4.5.7
 *
 * Latest Updates / Issues on Github:
 * https://github.com/Deesen/ckeditor4-modx-evo
 *
 * All available config-params of CKEditor4
 * http://docs.ckeditor.com/#!/api/CKEDITOR.config
 *
 * ! All params can be set individual via Modx-Plugin-Configuration, or directly in Plugin-code with
 *   $rte->set( $key, $value, $type )
 *
 * Belows default configuration setup assures all CK-config-params have a fallback-value, and format per key is known
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


$this->set('enterMode',             'p',                            'constant' );
$this->set('contentsCss',           $modxParams['editor_css_path'], 'string' );
$this->set('height',                $modxParams['height'],          'string' );

// $this->set('height',            '400px',    'string' );
// $this->set('width',             '100%',     'string' );
// $this->set('language',          'en',       'string' );
// $this->set('skin',              'moono',    'string' );
// $this->set('plugins',        '',         'string', true );
// $this->set('extraPlugins',      '',         'string', true );
// $this->set('removePlugins',     '',         'string', true );
// $this->set('enterMode',         'p',        'string', true );
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