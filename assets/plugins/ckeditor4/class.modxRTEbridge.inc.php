<?php

class modxRTEbridge
{
    public $editorKey = '';                 // Key for config/tpl/settings-files (ckeditor4, tinymce4, ...)
    public $theme = '';                     // Theme-key (default, simple, mini ... )
    public $pluginParams    = array();      // Params from Modx plugin-config
    public $modxParams      = array();      // Holds actual settings coming from Modx- or user-configuration
    public $bridgeParams    = array();      // Holds translation of Modx Configuration-Keys to Editor Configuration-Keys
    public $themeConfig     = array();      // Valid params and defaults for Editor
    public $initOnceArr     = array();      // Holds custom HTML-Code to inject into tpl.xxxxxxxxx.init_once.html
    public $customSettings  = array();      // Holds custom settings to enable setting via Modx- / user-configuration
    public $defaultValues   = array();      // Holds default values for settings

    public function __construct($editorKey=NULL, $theme='' )
    {
        global $modx, $settings, $usersettings;

        if( $editorKey == NULL ) { exit('modxRTEbridge: No editorKey set in plugin-initialization.'); };

        // Check right path
        $current_path = str_replace('\\', '/', dirname(__FILE__)) . '/';
        if (strpos($current_path, MODX_BASE_PATH) !== false) {
            $path       = substr($current_path, strlen(MODX_BASE_PATH));
            $basePath   = MODX_BASE_PATH . $path;
            $baseUrl    = MODX_BASE_URL . $path;
        } else exit('modxRTEbridge: Path-Error');

        // Get modxRTEbridge-config
        if ( is_readable("{$basePath}gsettings/bridge.{$editorKey}.inc.php")) {
            include("{$basePath}gsettings/bridge.{$editorKey}.inc.php");
            $this->bridgeParams     = isset( $bridgeParams )    ? $bridgeParams     : array();
            $this->customSettings   = isset( $customSettings )  ? $customSettings   : array();
            $this->defaultValues    = isset( $defaultValues )   ? $defaultValues    : array();
        } else exit("modxRTEbridge: {$basePath}gsettings/bridge.{$editorKey}.inc.php not found");

        // Determine settings from Modx
        switch ($modx->manager->action)
        {
            // Create empty array()
            case 11:    // Create new user
                $editorConfig = array();
                break;
            // Get user-config
            case 12:    // Edit user
            case 119:   // Purge plugin processor
                $editorConfig = $usersettings;
                if(!empty($usersettings[$this->editorKey .'_theme']))
                {
                    $usersettings[$this->editorKey .'_theme'] = $settings[$this->editorKey .'_theme'];
                }
                break;
            // Get Modx-config
            case 17:    // Modx-configuration
            default:
                $editorConfig = $settings;
                break;
        };

        // Modx default WYSIWYG-params
        $modxParamsArr = array(
            'theme', 'skin', 'entermode', 'element_format', 'schema', 'css_selectors',
            'custom_plugins', 'custom_buttons1', 'custom_buttons2', 'custom_buttons3', 'custom_buttons4',
            'template_docs', 'template_chunks'
        );

        // Add custom settings from bridge
        foreach( $this->customSettings as $name=>$x ) {
            if(!in_array($name,$modxParamsArr)) $modxParamsArr[] = $name;
        };

        // Take over editor-configuration from Modx
        foreach( $modxParamsArr as $p ) {
            $value = isset( $editorConfig[$editorKey .'_'. $p] )            ? $editorConfig[$editorKey .'_'. $p] : NULL;
            $value = $value === NULL && isset( $this->defaultValues[$p] )   ? $this->defaultValues[$p] : $value;

            $this->modxParams[$p] = $value;
        };

        // Get/set pluginParams
        $this->editorKey                        = $editorKey;
        if( !empty( $theme )) {
            $this->theme                        = $theme;
        } else {
            $this->theme                        = isset($this->modxParams['theme']) ? $this->modxParams['theme'] : 'default';
        };
        $this->pluginParams                     = $modx->event->params;
        $this->pluginParams['editorLabel']      = isset( $editorLabel ) ? $editorLabel : 'No editorLabel set';
        $this->pluginParams['editorLabel']     .= $theme == 'mini' ? ' Mini' : '';
        $this->pluginParams['skinsDirectory']   = isset( $skinsDirectory ) && !empty( $skinsDirectory ) ? trim($skinsDirectory, "/") . "/" : '';
        $this->pluginParams['base_path']        = $basePath;
        $this->pluginParams['base_url']         = $baseUrl;
    }

    // Function to override parameters from Plugin-Configuration
    // $value = NULL deletes key completely from editor-config
    public function set( $key, $value, $type=false, $emptyAllowed=NULL )
    {
        if( $value === NULL ) {
            // Delete Parameter completely from JS-initialization
            $this->themeConfig[$key]  = NULL;
        } else {

            $this->themeConfig[$key]['type']    = $type == false ? 'string' : $type;
            $this->themeConfig[$key]['default'] = !isset( $this->themeConfig[$key]['default'] ) ? $value : $this->themeConfig[$key]['default'];
            $this->themeConfig[$key]['empty']   = isset( $this->themeConfig[$key]['empty'] ) && $emptyAllowed == NULL ? $this->themeConfig[$key]['empty'] : $emptyAllowed;

            $default = $value == ''
                       && $this->themeConfig[$key]['empty'] == true
                       ? '' : $this->themeConfig[$key]['default'];

            $this->pluginParams[$key] = $value !== '' ? $value : $default;
        }
    }

    // Function to append string to existing parameters
    public function appendSet( $key, $value, $separator=',' )
    {
        if( $value === '' ) { return; }; // Nothing to append

        if( isset($this->pluginParams[$key]) ) {
            $this->pluginParams[$key] .= !empty( $this->pluginParams[$key] ) ? $separator : '';
            $this->pluginParams[$key] .= $value;
        };
    }

    // Function to append custom HTML-Code to tpl.editor.init_once.html
    public function appendInitOnce($str)
    {
        if( !in_array($str, $this->initOnceArr)) {  // Avoid doubling..
            $this->initOnceArr[] = $str;
        };
    }

    // Helper to translate "bold,strike,underline,italic" to "bold","strike","underline","italic"
    // Translates Modx Plugin-configuration strings to JSON-compatible string
    public function addQuotesToCommaList($str, $quote='"')
    {
        if( empty($str)) { return ''; }

        $elements = explode(',', $str);
        foreach($elements as $key=>$val) {
            $elements[$key] = $quote.$val.$quote;
        };
        return implode(',', $elements);
    }

    // Renders complete JS-Script
    public function getEditorScript()
    {
        global $modx;
        $params = & $this->pluginParams;
        $modxParams = $this->modxParams;    // For use in themes
        $ph = array();

        // Catch params already set within plugin
        $pluginParams = $this->pluginParams;
        $themeConfig = $this->themeConfig;  // Catch nulled params

        // Load theme for user or webuser
        if($modx->isBackend() || (intval($_GET['quickmanagertv']) == 1 && isset($_SESSION['mgrValidated']))) {
            // User is logged into Manager
            // Load default first to assure Modx settings like entermode, editor_css_path are given set, can be overwritten in custom theme
            include("{$this->pluginParams['base_path']}theme/theme.{$this->editorKey}.default.inc.php");
            if( $this->theme != 'default' )
                include("{$this->pluginParams['base_path']}theme/theme.{$this->editorKey}.{$this->theme}.inc.php");
            $this->pluginParams['language'] = !isset($this->pluginParams['language']) ? $this->getLang($modx->config['manager_language']) : $this->pluginParams['language'];
        } else {
            // User is a webuser
            $webuserTheme = !empty( $params['pluginWebTheme'] ) ? $params['pluginWebTheme'] : 'webuser';
            include("{$this->pluginParams['base_path']}theme/theme.{$this->editorKey}.{$webuserTheme}.inc.php");
            // @todo: determine user-language?
            $this->pluginParams['language'] = !isset($this->pluginParams['language']) ? $this->getLang($modx->config['manager_language']) : $this->pluginParams['language'];
        }

        // Now merge back plugin-settings
        $this->pluginParams = array_merge($this->pluginParams, $pluginParams);
        $this->themeConfig = array_merge($this->themeConfig, $themeConfig);

        // Prepare config output
        $ph['configJs'] = $this->renderConfigString();
        $ph['configKey'] = $this->theme;

        $ph = array_merge( $params, $ph );  // Make config and params available as [+placeholder+] in templates
        if( !isset($ph['pluginCustomParams'])) { $ph['pluginCustomParams'] = ''; }; // Don´t leave [+pluginCustomParams+] in JS-initialization

        $tpl = '';

        // Init only once at all - Load Editors-Library, CSS etc
        if(!defined($this->editorKey.'_INIT_ONCE')) {
            define($this->editorKey.'_INIT_ONCE', 1);
            $tpl .= file_get_contents("{$params['base_path']}tpl/tpl.{$this->editorKey}.init_once.html");
            if(!empty($this->initOnceArr)) {
                $tpl .= implode(",\n", $this->initOnceArr);
            }
        }

        // Init only once per config (enables multiple config-objects i.e. for richtext / richtextmini via [+configJs+])
        if(!defined($this->editorKey.'_INIT_CONFIG_'.$this->theme)) {
            define($this->editorKey.'_INIT_CONFIG_'.$this->theme, 1);
            $tpl .= file_get_contents("{$params['base_path']}tpl/tpl.{$this->editorKey}.config.html");
        }

        // Now loop through tvs
        foreach($params['elements'] as $id)
        {
            $initTpl = file_get_contents("{$params['base_path']}tpl/tpl.{$this->editorKey}.init.html");
            $tpl .= $modx->parseText($initTpl,array('id'=>$id,'configKey'=>$this->theme));
        };

        return $modx->parseText($tpl,$ph);
    }

    // Renders String for initialization via JS
    public function renderConfigString()
    {
        $config = array();

        // Call functions inside $bridgeParams
        $bridgedParams = array();   // Params translated by bridge.xxxxxxxxxx.inc.php
        foreach( $this->bridgeParams as $editorParam=>$editorKey ) {
            if(is_callable($this->bridgeParams[$editorParam])) {     // Call function, get return
                $return = $this->bridgeParams[$editorParam]();
                if( $return !== NULL ) {
                    $bridgedParams[$editorParam] = $return;
                };
            };
        }

        // Build config-string as per themeConfig
        foreach($this->themeConfig as $key=> $conf)
        {
            if( $conf === NULL ) { continue; }; // Skip nulled parameters

            // Determine Value
            $value = isset( $bridgedParams[$key] )                          ? $bridgedParams[$key]      : NULL;
            $value = $value === NULL && isset( $this->pluginParams[$key] )  ? $this->pluginParams[$key] : $value;
            $value = $value === ''   && $conf['empty'] == true              ? ''                        : $value;
            $value = $value === NULL                                        ? $conf['default']          : $value;

            // Escape quotes
            if(strpos($value,"'")!==false)
                $value = str_replace("'", "\\'", $value);

            // Determine output-type
            switch( $conf['type'] ) {
                case 'str':
                case 'string':
                    $config[$key] = "        {$key}:'{$value}'";
                    break;
                case 'int':
                case 'const':
                case 'constant':
                case 'number':
                    $config[$key] = "        {$key}:{$value}";
                    break;
                case 'bool':
                case 'boolean':
                    $value = $value == true ? 'true' : 'false';
                    $config[$key] = "        {$key}:{$value}";
                    break;
                case 'json':
                    // @todo finish when it is needed..
                    if( is_array($value)) $value = json_encode($value);
                    $config[$key] = "        {$key}:{$value}\n        ";
                    break;
            };
        }

        return implode(",\n", $config);
    }

    //////////////////////////////////////////////////////////////////
    // SETTINGS PARTS
    //////////////////////////////////////////////////////////////////

    // Outputs Modx- / user-configuration settings
    public function getModxSettings()
    {
        global $modx, $_lang, $usersettings, $settings;
        $params = & $this->pluginParams;

        if(defined('INTERFACE_RENDERED_'.$this->editorKey)) { return ''; }
        define('INTERFACE_RENDERED_'.$this->editorKey, 1);

        // Fallback to english
        include("{$params['base_path']}lang/english.inc.php");

        // Language settings
        if (file_exists("{$params['base_path']}lang/".$modx->config['manager_language'].'.inc.php'))
        {
            include("{$params['base_path']}lang/".$modx->config['manager_language'].'.inc.php');
        }

        // Avoid conflicts with older TinyMCE base configs, assure unique placeholders for template-handling like [+ckeditor4_custom_plugins+]
        $prependModxParams = array();
        foreach( $this->modxParams as $key=>$val ) {
            $prependModxParams[$this->editorKey.'_'.$key] = $val;
        }

        $ph = array_merge($prependModxParams, $params, $_lang);

        // Prepare [+display+]
        $ph['display'] = ($_SESSION['browser']==='modern') ? 'table-row' : 'block';
        $ph['display'] = $modx->config['use_editor']==1 ? $ph['display']: 'none';

        // Prepare setting "editor_theme"
        $theme_options = '';
        switch($modx->manager->action)
        {
            case '11';
            case '12';
            case '119';
                $selected = empty($ph[$this->editorKey.'_theme']) ? '"selected"':'';
                $theme_options .= '<option value="" ' . $selected . '>' . $_lang['theme_global_settings'] . "</option>\n";
        }

        // Prepare setting "theme"
        $ph['theme_options'] = $this->getThemeNames( $_lang );

        // Prepare setting "skin"
        $ph['skin_options']  = $this->getSkinNames();

        // Prepare setting "entermode_options"
        $entermode = !empty($ph[$this->editorKey.'_entermode']) ? $ph[$this->editorKey.'_entermode'] : 'p';
        $ph['entermode_options'] = '<label><input name="[+name+]" type="radio" value="p" '.  $this->checked($entermode=='p') . '/>' . $_lang['entermode_opt1'] . '</label><br />';
        $ph['entermode_options'] .= '<label><input name="[+name+]" type="radio" value="br" '. $this->checked($entermode=='br') . '/>' . $_lang['entermode_opt2'] . '</label>';
        switch($modx->manager->action)
        {
            case '11':
            case '12':
            case '119':
                $ph['entermode_options']  .= '<br />';
                $ph['entermode_options']  .= '<label><input name="[+name+]" type="radio" value="" '.  $this->checked(empty($params[$this->editorKey.'_entermode'])) . '/>' . $_lang['theme_global_settings'] . '</label><br />';
                break;
        }

        // Prepare setting "element_format_options"
        $element_format = !empty($ph[$this->editorKey.'_element_format']) ? $ph[$this->editorKey.'_element_format'] : 'xhtml';
        $ph['element_format_options'] = '<label><input name="[+name+]" type="radio" value="xhtml" '.  $this->checked($element_format=='xhtml') . '/>XHTML</label><br />';
        $ph['element_format_options'] .= '<label><input name="[+name+]" type="radio" value="html" '. $this->checked($element_format=='html') . '/>HTML</label>';
        switch($modx->manager->action)
        {
            case '11':
            case '12':
            case '119':
                $ph['element_format_options']  .= '<br />';
                $ph['element_format_options']  .= '<label><input name="[+name+]" type="radio" value="" '.  $this->checked(empty($params[$this->editorKey.'_element_format'])) . '/>' . $_lang['theme_global_settings'] . '</label><br />';
                break;
        }

        // Prepare setting "schema_options"
        $schema = !empty($ph[$this->editorKey.'_schema']) ? $ph[$this->editorKey.'_schema'] : 'html5';
        $ph['schema_options'] = '<label><input name="[+name+]" type="radio" value="html4" '.  $this->checked($schema=='html4') . '/>HTML4(XHTML)</label><br />';
        $ph['schema_options'] .= '<label><input name="[+name+]" type="radio" value="html5" '. $this->checked($schema=='html5') . '/>HTML5</label>';
        switch($modx->manager->action)
        {
            case '11':
            case '12':
            case '119':
                $ph['schema_options']  .= '<br />';
                $ph['schema_options']  .= '<label><input name="[+name+]" type="radio" value="" '.  $this->checked(empty($params[$this->editorKey.'_schema'])) . '/>' . $_lang['theme_global_settings'] . '</label><br />';
                break;
        };

        // Prepare settings rows output
        include( $params['base_path'].'gsettings/gsettings.rows.inc.php');
        $settingsRowTpl = file_get_contents("{$params['base_path']}gsettings/gsettings.row.inc.html");
        $settingsRows = array_merge( $settingsRows, $this->customSettings );

        $ph['rows'] = '';
        foreach($settingsRows as $name=>$row) {

            if($row == NULL) { continue; };     // Skip disabled config-settings

            $row['name']        = $this->editorKey .'_'. $name;
            $row['editorKey']   = $this->editorKey;
            $row['title']       = $ph[$row['title']];
            $row['message']     = $ph[$row['message']];
            $row['messageVal']  = !empty( $row['messageVal'] ) ? $row['messageVal'] : '';

            // Prepare displaying of default values
            $row['default'] = isset( $this->defaultValues[$name] ) ? '<span style="margin:0.5em 0;float:right;">'. $_lang['default'] .'<i>'.$this->defaultValues[$name].'</i></span>' : '';

            // Enable nested parsing
            $tmp         = $modx->parseText($settingsRowTpl, $row);
            $tmp         = $modx->parseText($tmp, $ph);
            $ph['rows'] .= $modx->parseText($tmp, $row) ."\n";
        };

        $settingsBody = file_get_contents("{$params['base_path']}gsettings/gsettings.body.inc.html");

        return $modx->parseText($settingsBody, $ph);
    }

    // helpers for getModxSettings()
    public function getThemeNames( $_lang )
    {
        global $modx;
        $params = $this->pluginParams;

        $themeDir= "{$params['base_path']}theme/";

        switch($modx->manager->action)
        {
            case '11':
            case '12':
            case '119':
                $selected = $this->selected(empty($params[$this->editorKey.'_skin']));
                $option[] = '<option value=""' . $selected . '>' . "{$_lang['mce_theme_global_settings']}</option>";
                break;
        }

        foreach(glob("{$themeDir}*") as $file)
        {
            $file = str_replace('\\','/',$file);
            $file = str_replace($themeDir, '', $file );
            $file = str_replace('theme.'.$this->editorKey.'.', '', $file );

            $theme = trim(str_replace('.inc.php', '', $file ));
            $label = isset($_lang['theme_'.$theme]) ? $_lang['theme_'.$theme] : $theme; // Get optional translation
            $selected = $this->selected($theme == $this->modxParams['theme']);

            $option[] = '<option value="' . $theme . '"' . $selected . '>' . "{$label}</option>";
        }

        return is_array( $option ) ? implode("\n",$option) : '<!-- '. $this->editorKey .': No themes found -->';
    }
    public function getSkinNames()
    {
        global $modx, $_lang, $usersettings, $settings;
        $params = $this->pluginParams;

        if( empty($params['skinsDirectory'])) { return '<option value="">No skinsDirectory set</option>'; };

        $skinDir= "{$params['base_path']}{$params['skinsDirectory']}";

        switch($modx->manager->action)
        {
            case '11':
            case '12':
            case '119':
                $selected = $this->selected(empty($params[$this->editorKey.'_skin']));
                $option[] = '<option value=""' . $selected . '>' . "{$_lang['mce_theme_global_settings']}</option>";
                break;
        }
        foreach(glob("{$skinDir}*",GLOB_ONLYDIR) as $dir)
        {
            $dir = str_replace('\\','/',$dir);
            $skin_name = substr($dir,strrpos($dir,'/')+1);
            $skins[$skin_name][] = 'default';
            $styles = glob("{$dir}/ui_*.css");
            if(is_array($styles) && 0 < count($styles))
            {
                foreach($styles as $css)
                {
                    $skin_variant = substr($css,strrpos($css,'_')+1);
                    $skin_variant = substr($skin_variant,0,strrpos($skin_variant,'.'));
                    $skins[$skin_name][] = $skin_variant;
                }
            }
            foreach($skins as $k=>$o);
            {
                foreach($o as $v)
                {
                    if($v==='default') $value = $k;
                    else               $value = "{$k}:{$v}";
                    $selected = $this->selected($value == $this->modxParams['skin']);
                    $option[] = '<option value="' . $value . '"' . $selected . '>' . "{$value}</option>";
                }
            }
        }

        return is_array( $option ) ? implode("\n",$option) : '<!-- '. $this->editorKey .': No skins found -->';
    }
    
    public function lang($key='')
    {
        global $modx;
        
        if(!$key) return;
        
        $lang_name = $modx->config['manager_language'];
        $lang_path = $this->pluginParams['base_path'] . "lang/{$lang_name}.inc.php";
        
        if(is_file($lang_path)) include_once($lang_path);
        
        if(isset($_lang[$key])) return $_lang[$key];
        else {
            include_once($this->pluginParams['base_path'] . 'lang/english.inc.php');
            if(isset($_lang[$key])) return $_lang[$key];
            else                    return '';
        }
    }
    
    public function selected($cond = false)
    {
        if($cond !== false) return ' selected="selected"';
        else                return '';
    }
    public function checked($cond = false)
    {
        if($cond !== false) return ' checked="checked"';
        else                return '';
    }

    // Remove all but numbers
    public function onlyNumbers($string)
    {
        return preg_replace("/[^0-9]/","",$string); // Remove px, % etc
    }

    // Convert Modx-Langcode into Editor-Langcode
    public function getLang($lang)
    {
        // @todo: Compare Modx-Codes / language-files
        switch(strtolower($lang))
        {
            case 'bulgarian'             : $lc = 'bg';      break;
            case 'chinese'               :
            case 'simple_chinese-gb2312' : $lc = 'zh-cn';   break;
            case 'czech'                 : $lc = 'cs';      break;
            case 'danish'                : $lc = 'da';      break;
            case 'dutch'                 :
            case 'nederlands-utf8'       :
            case 'nederlands'            : $lc = 'nl';      break;
            case 'finnish'               : $lc = 'fi';      break;
            case 'francais'              :
            case 'francais-utf8'         : $lc = 'fr';      break;
            case 'german'                : $lc = 'de';      break;
            case 'hebrew'                : $lc = 'he';      break;
            case 'italian'               : $lc = 'it';      break;
            case 'japanese-utf8'         :
            case 'japanese-euc'          : $lc = 'ja';      break;
            case 'norsk'                 : $lc = 'nb';      break;
            case 'persian'               : $lc = 'fa';      break;
            case 'polish-utf8'           :
            case 'polish'                : $lc = 'pl';      break;
            case 'portuguese-br'         : $lc = 'pt-br';   break;
            case 'portuguese'            : $lc = 'pt';      break;
            case 'russian'               :
            case 'russian-utf8'          : $lc = 'ru';      break;
            case 'spanish-utf8'          :
            case 'spanish'               : $lc = 'es';      break;
            case 'svenska'               :
            case 'svenska-utf8'          : $lc = 'sv';      break;

            default                      : $lc = 'en';
        }
        return $lc;
    }
}