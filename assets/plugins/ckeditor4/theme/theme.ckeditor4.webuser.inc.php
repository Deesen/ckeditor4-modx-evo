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
 *   $ck->set( $key, $value, $default=NULL )
 *
 * Belows default configuration setup assures all CK-config-params have a fallback-value, and format per key is known
 * $this->def( $ckConfigKey, $type, $defaultValue, $emptyAllowed=false )
 *
 * $ckConfigParam   = Every config-param can be set via Modx-Plugin-Configuration, or this config-file
 * $type            = string, number, bool, json (array or string)
 *
 * $emptyAllowed    = true, false
 * If $ckConfigParam is empty and $emptyAllowed is true, $defaultValue will be ignored
 *
 * */

// @todo: Set default-config for webusers
$this->set('height',                'string',   '400px'   );
$this->set('width',                 'string',   '100%'    );
$this->set('language',              'string',   'en'      );
$this->set('skin',                  'string',   'moono'   );
$this->set('toolbarGroups',         'json',     '[
            { name: "document",    groups: [ "mode", "document", "doctools" ] },
            { name: "clipboard",   groups: [ "clipboard", "undo" ] },
            { name: "editing",     groups: [ "find", "selection", "spellchecker" ] },
            "/",
            { name: "paragraph",   groups: [ "list", "indent", "blocks", "align", "bidi" ] }
            ]' );