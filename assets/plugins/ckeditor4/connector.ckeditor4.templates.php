<?php
// Get Template from resource for ckeditor4

$self = 'assets/plugins/ckeditor4/connector.ckeditor4.templates.php';
$base_path = str_replace($self, '', str_replace('\\', '/', __FILE__));

define('MODX_API_MODE','true');
define('IN_MANAGER_MODE','true');
include_once("{$base_path}index.php");
if( !file_exists(MODX_BASE_PATH."assets/lib/class.modxRTEbridge.php")) { // Add Fall-Back for now
	require_once(MODX_BASE_PATH."assets/plugins/ckeditor4/class.modxRTEbridge.php");
} else {
	require_once(MODX_BASE_PATH."assets/lib/class.modxRTEbridge.php");
}
require_once(MODX_BASE_PATH."assets/plugins/ckeditor4/bridge.ckeditor4.inc.php");

$bridge = new ckeditor4bridge();

$templatesList = $bridge->getTemplateChunkList();    // $templatesArr could be modified/bridged now for different editors before sending
$templatesArr = array();

foreach($templatesList as $row) {
	$newTemplate = array(
		'title'=>$row['title'],
		'image'=>'chunk_'. $row['title'] .'.png',    // @todo: Enable set image via HTML-Comments, and strip comments then?
		'description'=>$row['description'],
		'html'=>$row['content']
	);
	$templatesArr[] = $newTemplate;
}

if (0 < count($templatesArr)) {
    // Make output a real JavaScript file!
    header('Content-type: application/x-javascript');
    header('pragma: no-cache');
    header('expires: 0');
    // @todo: Set imagesPath via Plugin-Configuration?
    echo "CKEDITOR.addTemplates('default', {
        imagesPath: '". MODX_SITE_URL . $imagesPath ."',
        templates: ". json_encode($templatesArr) ."
    });";
};