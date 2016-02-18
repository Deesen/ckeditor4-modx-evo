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

// @todo: replace "Styles" with editCss-Button to "misc2"

// http://docs.ckeditor.com/#!/guide/dev_toolbar-section-%22item-by-item%22-configuration
// http://ckeditor.com/forums/CKEditor/Complete-list-of-toolbar-items
$this->set('toolbar',     '[
                { name: "undo",     items: [ "Undo", "Redo" ]},
                { name: "basic",    items: [ "Bold", "Strike" ]},
                { name: "colors",   items: [ "TextColor", "BGColor" ]},
                { name: "style",    items: [ "Format", "FontSize" ]},
                { name: "paste",    items: [ "PasteText", "PasteFromWord" ]},
                { name: "misc1",    items: [ "Source", "Maximize" ]},
                { name: "about",    items: [ "About" ]},
                "/",
                { name: "media",    items: [ "Image", "Flash", "Embed" ]},
                { name: "links",    items: [ "Link", "Unlink", "Anchor" ]},
                { name: "justify",  items: [ "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock" ]},
                { name: "list",     items: [ "BulletedList", "NumberedList" ]},
                { name: "indent",   items: [ "Outdent", "Indent" ]},
                { name: "misc2",    items: [ "Blockquote", "Table", "HorizontalRule", "RemoveFormat", "Styles" ] }
            ]', 'json' );

// $this->set('plugins',        '',         'string', true );
// $this->set('extraPlugins',   '',         'string', true );
// $this->set('removePlugins',  '',         'string', true );