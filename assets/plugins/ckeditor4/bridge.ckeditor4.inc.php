<?php
/* CKEditor4 for Modx Evolution
   Base: v4.5.7
*/

class ckeditor4bridge extends modxRTEbridge {
	public function __construct($tvOptions = array()) {
		$bridgeConfig = array(
			// Editor-Settings
			'editorLabel'    => 'CKEditor4',           // Name displayed in Modx-Dropdowns - No HTML!
			'skinsDirectory' => 'ckeditor/skins',      // Relative to plugin-dir
			'editorVersion'  => '4.5.7',              // Version of TinyMCE4-Library
			'editorLogo'     => 'ckeditor/plugins/about/dialogs/hidpi/logo_ckeditor.png',  // Optional Image displayed in Modx-settings
			
			// List of all relevant / existing bridge-functions
			'bridgeParams'   => array('css_selectors','enterMode','element_format','entities_additional','entity_encoding',
				'path_options','advanced_resizing','remove_buttons','contentsLangDirection','template_button','resize_maxHeight',
				'callCk'),

			// Custom settings to show below Modx- / user-configuration
			'gSettingsCustom'=> array(
				'css_selectors' => null,  // Hides "CSS Selectors" from settings
				'schema' => NULL,         // Hides "HTML Schema" from settings

				// 'maxHeight' will be available as $this->modxParams['maxHeight']
				// will be handled by $this->bridgeParams[resize_maxHeight]()
				'maxHeight' => array(
					'title' => 'maxHeight_title',
					'configTpl' => '<input type="text" class="inputBox" style="width: 50px;" name="[+name+]" value="[+[+editorKey+]_maxHeight+]" />',
					'message' => 'maxHeight_message'
				)
			),

			// For Modx- and user-configuration
			'gSettingsDefaultValues' => array(
				'entermode' => 'p',
				'element_format' => 'xhtml',
				'schema' => 'html5',
				'maxHeight' => '800',
				'custom_plugins'=>'',
				'custom_buttons1'=>'Undo,Redo,Bold,TextColor,BGColor,Strike,Format,FontSize,PasteText,PasteFromWord,Source,Maximize,About',
				'custom_buttons2'=>'Image,Embed,Link,Unlink,Anchor,JustifyLeft,JustifyCenter,JustifyRight,BulletedList,NumberedList,Blockquote,Outdent,Indent,Table,HorizontalRule,Templates,ShowBlocks,RemoveFormat'
			)
		);

		// Init bridge first before altering Lang()
		parent::__construct('ckeditor4', $bridgeConfig, $tvOptions, __FILE__);

		// Add translation for monolingual custom-messages with $this->setLang( key, string, overwriteExisting=false )
		$this->setLang('editor_custom_buttons1_msg', '<div style="width:70vw;word-wrap:break-word;overflow-wrap:break-word;">[+default+]<i>'.$defaultValues['custom_buttons1'].'</i></div>' );
		$this->setLang('editor_custom_buttons2_msg', '<div style="width:70vw;word-wrap:break-word;overflow-wrap:break-word;">[+default+]<i>'.$defaultValues['custom_buttons2'].'</i></div>' );
		$this->setLang('editor_css_selectors_schema', 'Title==Tag==CSS-Class');
		$this->setLang('editor_css_selectors_example', 'Mono==pre==mono||Small Text==span==small');
		$this->setLang('editor_css_selectors_separator', '||');

	}
	
	///////////////////////////////////////////////////////////////////
	// Dynamic translation/bridging of Modx-settings to editor-settings
	
	// editor-param => translate-function (return NULL = no translation, still allows $this->set(), $this->appendInitOnce() etc )
	public function bridge_css_selectors($selector) {
        global $modx;

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
	}
	
	public function bridge_enterMode($selector) {
        global $modx;

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
	}
	
	public function bridge_element_format($selector) {
        global $modx;

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
	}
	
	public function bridge_entities_additional($selector) {
        global $modx;

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
	}
	
	public function bridge_entity_encoding($selector) {
        global $modx;

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
	}
	
	public function bridge_path_options($selector) {
        global $modx;

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
	}
	
	public function bridge_advanced_resizing($selector) {
        global $modx;

		// http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-resize_dir
		if( $this->pluginParams['pluginResizing'] == 'true' ) {
			$this->appendSet('extraPlugins', 'resize', ',');
			$this->set('resize_dir', 'both', 'string');
		};
	}
	
	public function bridge_remove_buttons($selector) {
        global $modx;

		// List of Buttons: http://ckeditor.com/comment/123266#comment-123266
		if( !empty( $this->pluginParams['pluginRemoveButtons'] )) {
			$this->set('removeButtons', trim($this->pluginParams['pluginRemoveButtons'], ','), 'string');
		};
	}
	
	public function bridge_contentsLangDirection($selector) {
        global $modx;

		// http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-contentsLangDirection
		if( $this->pluginParams['pluginWebAlign'] == 'rtl') {
			$this->set('contentsLangDirection', 'rtl', 'string');
			$this->appendInitOnce('<style>.cke_toolbox,.cke_toolbar { float:right !important; }</style>');
		};
	}

	// Prepare Ressources-Content or Chunks for Template-Button
	public function bridge_template_button($selector) {
        global $modx;

		// http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Templates
		if( !empty( $this->modxParams['template_docs'] )) {
			$docsArr = explode(',', $this->modxParams['template_docs']);
			$this->set('templates_files', array($this->pluginParams['base_url'].'connector.ckeditor4.templates.php'), 'array');
		};
	}

	// Handles customSetting "maxHeight" as example
	public function bridge_resize_maxHeight($selector) {
        global $modx;

		// http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-resize_maxHeight
		if( !empty( $this->modxParams['maxHeight'] )) {
			$maxHeight = $this->onlyNumbers( $this->modxParams['maxHeight'] );
			$this->set('resize_maxHeight', $maxHeight, 'string');
		};
	}

	public function bridge_callCk($selector) {
		global $modx;

		$inlineMode = $this->determineValue('inline') == true && $this->pluginParams['inlineMode'] == 'enabled' ? true : false;

		// Manager Mode
		if (!$inlineMode) {
			$this->setPlaceholder('callCk', 'replace');

			// Prepare Inline-Magic
		} else {
			$this->setPlaceholder('callCk', 'inline');
			// $this->setPluginParam('elements', 'editable');  // Set missing plugin-parameter manually for Frontend

			$this->force('setup', NULL);               // Remove from parameters for Frontend
			$this->force('save_onsavecallback', 'function () {
            triggerSave();
        }', 'object');

			// Prepare save-button
			$this->appendInitOnce('
            <style>
              #action-save { position:fixed;top:0px;left:0px; color: #000000; background-color: #F0F0F0; text-shadow: -1px 1px #aaaaaa; display: inline-block; padding: 15px 30px !important; font-size: 24px; font-family: sans-serif; line-height: 1.8; appearance: none; box-shadow: none; border-radius: 0; border: none; cursor:pointer; }
              #action-save:hover { color: #222222; outline: none; background-color: #E3E3E3; }
            </style>
            <button id="action-save" class="button" title="Save Ressource">SAVE</button>
            <script>
    
            $("#action-save").on("click", function() { triggerSave(); });
            function triggerSave() {
    
            [+dataObject+]
    
            var saving = $.post( "' . $this->pluginParams['base_url'] . 'connector.ckeditor4.saveProcessor.php", data );
    
            saving.done(function( data ) {
                if( data == ' . $modx->documentIdentifier . ' ) {
                    $("#action-save").css("color","#00ff00");
                    setTimeout(function(){ $("#action-save").css("color","#000000") }, 3000);
                    // Force all instances to not dirty state
                    for (var key in window.tinymce.editors) {
                        // tinymce.get(key).setDirty(false);
                    }
                } else {
                    $("#action-save").css("color","#ff0000");
                    alert( data );  // Show (PHP-)Errors for debug
                }
            });
            }
        </script>
    ');
			// Prepare dataObject for submitting changes
			if (isset($modx->modxRTEbridge['editableIds'])) {
				$dataEls = array();
				$phs = '';
				$this->pluginParams['elements'] = array();
				foreach ($modx->modxRTEbridge['editableIds'] as $cssId=>$x) {
					$dataEls[] = "'{$cssId}': $('#modx_{$cssId}').html()";
					$phs .= (!empty($phs) ? ',' : '') . $cssId;
					$this->pluginParams['elements'][] = "modx_{$cssId}";
				}
				$dataEls = join(",\n                    ", $dataEls);

				$this->setPlaceholder('dataObject', "
                var data = {
                    'pluginName':'{$this->pluginParams['pluginName']}',
                    'rid':{$modx->documentIdentifier},
                    'secHash':'{$this->prepareAjaxSecHash($modx->documentIdentifier)}',
                    'phs':'{$phs}',
                    {$dataEls}
                };");
			}
		}
		return NULL;
		
		
	}
}



?>

