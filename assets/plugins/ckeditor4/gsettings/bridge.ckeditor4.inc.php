<?php
/* CKEditor4 for Modx Evolution
   Base: v4.5.7
*/

// Editor-Settings
$editorLabel    = 'CKEditor4';         // Name displayed in Modx-Dropdowns
$skinsDirectory = 'ckeditor/skins';    // Relative to plugin-dir

// Dynamic translation of Modx-settings to editor-settings
$bridgeParams = array(

    // editor-param => translate-function (return NULL = no translation, still allows using functions like appendInitOnce() )

    'enterMode'         => function($modxParams) {
                                switch($modxParams['entermode']) {
                                    case 'br':
                                        return 'CKEDITOR.ENTER_BR';
                                    case 'div':
                                        return 'CKEDITOR.ENTER_DIV';
                                    case 'p':
                                    default:
                                        return 'CKEDITOR.ENTER_P';
                                }
                           },
    'element_format'    => function($modxParams) {
                                switch($modxParams['element_format']) {
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
                           }
);

// Custom settings to show below Modx- / user-configuration
$customSettings = array(

    'schema'=>NULL,         // Hides "HTML Schema" from settings

    'height'=>array(
        'title'=>'height_title',
        'configTpl'=>'<input type="text" class="inputBox" style="width: 50px;" name="[+name+]" value="[+[+editorKey+]_height+]" />',
        'message'=>'height_message'
    )
);

$defaultValues = array(
    'entermode'=>'p',
    'element_format'=>'xhtml',
    'schema'=>'html5',
    'height'=>'400px'
);
?>