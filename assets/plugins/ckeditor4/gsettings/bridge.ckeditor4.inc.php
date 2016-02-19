<?php
/* CKEditor4 for Modx Evolution
   Base: v4.5.7
*/

// Editor-Settings
$editorLabel    = 'CKEditor4';      // Name displayed in Modx-Dropdowns - No HTML!
$skinsDirectory = 'ckeditor/skins'; // Relative to plugin-dir
$editorVersion  = '4.5.7';          // Version of CKEditor-Library
$editorLogo     = 'ckeditor/plugins/about/dialogs/hidpi/logo_ckeditor.png'; // Optional Image displayed in Modx-settings

// Dynamic translation of Modx-settings to editor-settings
$bridgeParams = array(

    // editor-param => translate-function (return NULL = no translation, still allows $this->set(), $this->appendInitOnce() etc )

    'css_selectors' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-stylesSet

        // Input-Format:  Mono==pre==mono||Small Text==span==small
        // Output-Format: [{ name: "CSS Style", element: "span", attributes: { "class": "my_style" } }],

        if(!empty($this->modxParams['css_selectors'])) {
            $setsArr = explode('||', $this->modxParams['css_selectors']);

            $stylesSet = array();
            foreach( $setsArr as $setStr ){
                if(!empty($setStr)) {
                    $setArr = explode('==', $setStr);
                    $newSet = array(
                        'name'=>$setArr[0],
                        'element'=>$setArr[1],
                        'attributes'=>array('class'=>$setArr[2])
                    );
                    $stylesSet[]= $newSet;
                };
            };

            $this->set('stylesSet', $stylesSet, 'json');
        };
        return NULL;
    },
    'enterMode' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-enterMode
        switch ($this->modxParams['entermode']) {
            case 'br':
                return 'CKEDITOR.ENTER_BR';
            case 'div':
                return 'CKEDITOR.ENTER_DIV';
            case 'p':
            default:
                return 'CKEDITOR.ENTER_P';
        }
    },
    'element_format' => function () {
        // http://docs.ckeditor.com/#!/guide/dev_howtos_output
        switch ($this->modxParams['element_format']) {
            case 'xhtml':
                break;
            case 'html':
                $this->appendInitOnce("
                                            <script>
                                                CKEDITOR.on( 'instanceReady', function( ev )
                                                {
                                                    ev.editor.dataProcessor.writer.selfClosingEnd = '>';
                                                })
                                            </script>
                                        ");
                break;
        }
        return NULL; // important
    },
    'entities_additional' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-entities_additional
        if (!empty($this->pluginParams['pluginEntities'])) {
            $entities = explode(',', $this->pluginParams['pluginEntities']);
            foreach ($entities as $key => $val) {
                $val = urldecode($val);
                $entities[$key] = '#' . trim($val, '&;#');  // Remove all & ; #
            };
            $this->set('entities_additional', implode(',', $entities), 'string');
        }
        return NULL;    // Important
    },
    'entity_encoding' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-entities_processNumerical
        switch ($this->pluginParams['pluginEntityEncoding']) {
            case 'named':
            default:
                // $this->set('entities', true, 'bool'); // Not nessecary
                break;
            case 'numeric':
                $this->set('entities_processNumerical', 'force', 'string');
                break;
            case 'named+numeric':
                $this->set('entities_processNumerical', true, 'bool');
                break;
            case 'raw':
                $this->set('entities', false, 'bool');
                break;
        };
        return NULL;    // Important
    },
    'path_options' => function () {
        $relativeUrl = '';
        $absoluteUrl = '/';
        $fullUrl     = MODX_SITE_URL;

        switch ($this->pluginParams['pluginPathOptions']) {
            case 'Site config':
            case 'siteconfig':
                if ($modx->config['strip_image_paths'] == 1) {
                    // @todo: TinyMCE3 "convert_urls" equivalent setting/method in CKEditor?
                    // TinyMCE3: assets/images/example.png
                    // TinyMCE3: convert_urls = true
                    $this->set('baseHref', $relativeUrl, 'string', true);
                } else {
                    // TinyMCE3: http://localhost/assets/images/example.png
                    // TinyMCE3: convert_urls = true
                    $this->set('baseHref', $fullUrl, 'string', true);
                }
                break;
            case 'Root relative':
            case 'docrelative':
                // TinyMCE3: assets/images/example.png
                // TinyMCE3: convert_urls = true
                $this->set('baseHref', $relativeUrl, 'string', true);
                break;
            case 'Absolute path':
            case 'rootrelative':
                // TinyMCE3: /assets/images/example.png
                // TinyMCE3: convert_urls = true
                $this->set('baseHref', $absoluteUrl, 'string', true);
                break;
            case 'URL':
            case 'fullpathurl':
                // TinyMCE3: http://localhost/assets/images/example.png
                // TinyMCE3: convert_urls = true
                $this->set('baseHref', $fullUrl, 'string', true);
                break;
            case 'No convert':
            default:
                // TinyMCE3: assets/images/example.png
                // TinyMCE3: convert_urls = false
                $this->set('baseHref', $relativeUrl, 'string', true);
        }
        return NULL;    // Important
    },
    'advanced_resizing' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-resize_dir
        if( $this->pluginParams['pluginResizing'] == 'true' ) {
            $this->appendSet('extraPlugins', 'resize', ',');
            $this->set('resize_dir', 'both', 'string');
        };
    },
    'remove_buttons' => function () {
        // List of Buttons: http://ckeditor.com/comment/123266#comment-123266
        if( !empty( $this->pluginParams['pluginRemoveButtons'] )) {
            $this->set('removeButtons', trim($this->pluginParams['pluginRemoveButtons'], ','), 'string');
        };
    },
    'contentsLangDirection' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-contentsLangDirection
        if( $this->pluginParams['pluginWebAlign'] == 'rtl') {
            $this->set('contentsLangDirection', 'rtl', 'string');
            $this->appendInitOnce('<style>.cke_toolbox,.cke_toolbar { float:right !important; }</style>');
        };
    },

    // Handles customSetting "maxHeight" as example
    'resize_maxHeight' => function () {
        // http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-resize_maxHeight
        if( !empty( $this->modxParams['maxHeight'] )) {
            $maxHeight = $this->onlyNumbers( $this->modxParams['maxHeight'] );
            $this->set('resize_maxHeight', $maxHeight, 'string');
        };
    },
);

// Custom settings to show below Modx- / user-configuration
$customSettings = array(

    'schema' => NULL,         // Hides "HTML Schema" from settings

    // 'maxHeight' will be available as $this->modxParams['maxHeight']
    // will be handled by $this->bridgeParams[resize_maxHeight]()
    'maxHeight' => array(
        'title' => 'maxHeight_title',
        'configTpl' => '<input type="text" class="inputBox" style="width: 50px;" name="[+name+]" value="[+[+editorKey+]_maxHeight+]" />',
        'message' => 'maxHeight_message'
    )
);

// For Modx- and user-configuration
$defaultValues = array(
    'entermode' => 'p',
    'element_format' => 'xhtml',
    'schema' => 'html5',
    'maxHeight' => '800'
);
?>