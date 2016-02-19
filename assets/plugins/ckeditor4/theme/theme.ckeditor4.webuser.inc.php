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
 * Check theme.ckeditor4.base.inc.php for more examples
 * */

// @todo: Set default-config for webusers
if( !empty( $this->pluginParams['pluginWebPlugins'])) {
    $this->set('plugins', $this->pluginParams['pluginWebPlugins'], 'string' );
};

$this->set('toolbar',         '[
                { name: "row1",     items: [ '. $this->addQuotesToCommaList( $this->pluginParams['pluginWebButtons1'] ) .' ]},
                '. (!empty($this->pluginParams['pluginWebButtons2']) ? '"/",' : '') .'
                { name: "row2",     items: [ '. $this->addQuotesToCommaList( $this->pluginParams['pluginWebButtons2'] ) .' ]},
                '. (!empty($this->pluginParams['pluginWebButtons3']) ? '"/",' : '') .'
                { name: "row3",     items: [ '. $this->addQuotesToCommaList( $this->pluginParams['pluginWebButtons3'] ) .' ]},
                '. (!empty($this->pluginParams['pluginWebButtons4']) ? '"/",' : '') .'
                { name: "row4",     items: [ '. $this->addQuotesToCommaList( $this->pluginParams['pluginWebButtons4'] ) .' ]},
                { name: "about",    items: [ "About" ]}
            ]', 'json' );