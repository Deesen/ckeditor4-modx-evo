<?php
/**
 * Function:       English language file for Modx Richtext
 * Encoding:       ISO-Latin-1
 * Author:         Jeff Whitfield and yama
 * Date:           2014/02/01
 * Version:        3.5.10
 * MODX version:   0.9.5-1.1
*/

$_lang['editor_theme_title'] = 'Theme';
$_lang['editor_theme_message'] = 'Here you can select which theme or skin to use with the editor.';
$_lang['editor_custom_plugins_title'] = 'Custom Plugins';
$_lang['editor_custom_plugins_message'] = 'Enter the plugins to use for the \'custom\' theme as a comma separated list.';
$_lang['editor_custom_buttons_title'] = 'Custom Buttons';
$_lang['editor_custom_buttons_message'] = 'Enter the buttons to use for the \'custom\' theme as a comma separated list for each row. Be sure that each button has the required plugin enabled in the \'Custom Plugins\' setting.';
$_lang['editor_css_selectors_title'] = 'CSS Selectors';
$_lang['editor_css_selectors_message'] = 'Here you can enter a list of selectors that should be available in the editor. Enter them as follows:<br />\'displayName=selectorName;displayName2=selectorName2\'<br />For instance, say you have <b>.mono</b> and <b>.smallText</b> selectors in your CSS file, you could add them here as:<br />\'Monospaced text=mono;Small text=smallText\'<br />Note that the last entry should not have a semi-colon after it.';
$_lang['settings'] = 'Settings';
$_lang['theme_simple'] = 'Simple';
$_lang['theme_full'] = 'Full';
$_lang['theme_advanced'] = 'Advanced';
$_lang['theme_editor'] = 'MODX Style';
$_lang['theme_custom'] = 'Custom';
$_lang['theme_creative'] = 'Creative';
$_lang['theme_logic'] = 'xhtml';
$_lang['theme_legacy'] = 'legacy style';
$_lang['theme_global_settings'] = 'Use the global setting';
$_lang['editor_skin_title'] = 'Skin';
$_lang['editor_skin_message'] = 'Design of toolbar. see ';
$_lang['editor_entermode_title'] = 'Enter Key Mode';
$_lang['editor_entermode_message'] = 'Operation when the enter key is pressed is set up.';
$_lang['entermode_opt1'] = 'Wrap &lt;p&gt;&lt;/p&gt;';
$_lang['entermode_opt2'] = 'Insert &lt;br /&gt;';

$_lang['element_format_title'] = 'Element Format';
$_lang['element_format_message'] = 'This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br /&gt; will be &lt;br&gt; if you set this option to &quot;html&quot;.';
$_lang['schema_title'] = 'Schema';
$_lang['schema_message'] = 'The schema option enables you to switch between the HTML4 and HTML5 schema. This controls the valid elements and attributes that can be placed in the HTML. This value can either be the default html4 or html5.';

$_lang['toolbar1_msg'] = 'Default: undo,redo,|,bold,forecolor,backcolor,strikethrough,formatselect,fontsizeselect, pastetext,pasteword,code,|,fullscreen,help';
$_lang['toolbar2_msg'] = 'Default: image,media,link,unlink,anchor,|,justifyleft,justifycenter,justifyright,|,bullist, numlist,|,blockquote,outdent,indent,|,table,hr,|,template,visualblocks,styleprops,removeformat';

$_lang['tpl_title'] = 'Template Button';
$_lang['tpl_msg'] = 'You could define templates on chunk or ressource base for the template button in [+editorLabel+] (won\'t be displayed by default). The content of the chunk/of the resource will be inserted at the cursor position as html code in [+editorLabel+]. Multiple chunk names or ressource IDs have to be separated by comma.';
$_lang['tpl_docid'] = 'Resource IDs';
$_lang['tpl_chunkname'] = 'Chunk Names';

$_lang['default'] = 'Default: ';
$_lang['height_title'] = 'Height';
$_lang['height_message'] = 'Set default-height in px or % like 400px.';