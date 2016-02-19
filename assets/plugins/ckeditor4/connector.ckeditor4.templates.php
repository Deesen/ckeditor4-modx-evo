<?php
// Get Template from resource for CKEditor4
// Based on get_template.php for TinyMCE3 by Yamamoto
//
// Changelog:
// @author Deesen / updated: 19.02.2016

// Config options
// $templates_to_ignore = array();        // Template IDs to ignore from the link list
// $include_page_ids = false;
// $charset = 'UTF-8';
// $sortby = 'menuindex'; // Could be menuindex or menutitle
// $limit = 0;
$imagesPath = 'assets/plugins/ckeditor4/images/';

/* That's it to config! */
define('MODX_API_MODE', true);
define('IN_MANAGER_MODE', "true");
$self = 'assets/plugins/ckeditor4/connector.ckeditor4.templates.php';
$base_path = str_replace($self, '', str_replace('\\', '/', __FILE__));
include_once("{$base_path}index.php");
$modx->db->connect();

/* only display if manager user is logged in */
if ($modx->getLoginUserType() === 'manager') {

    $modx->getSettings();
    $ids    = $modx->config['ckeditor4_template_docs'];
    $chunks = $modx->config['ckeditor4_template_chunks'];
    $templatesArr = array();

    if (!empty($ids)) {
        $docs = $modx->getDocuments($ids, 1, 0, $fields = 'id,pagetitle,menutitle,description,content');
        foreach ($docs as $i => $a) {
            $newTemplate = array(
                'title'=>($docs[$i]['menutitle'] !== '') ? $docs[$i]['menutitle'] : $docs[$i]['pagetitle'],
                'image'=>'res_'. $docs[$i]['id'] .'.png',   // @todo: Enable set image via HTML-Comments, and strip comments then?
                'description'=>$docs[$i]['description'],
                'html'=>$docs[$i]['content']
            );
            $templatesArr[] = $newTemplate;
        }
    }

    if (!empty($chunks)) {
        $tbl_site_htmlsnippets = $modx->getFullTableName('site_htmlsnippets');
        if (strpos($chunks, ',') !== false) {
            $chunks  = array_filter(array_map('trim', explode(',', $chunks)));
            $chunks  = $modx->db->escape($chunks);
            $chunks  = implode("','", $chunks);
            $where   = "`name` IN ('{$chunks}')";
            $orderby = "FIELD(name, '{$chunks}')";
        } else {
            $where   = "`name`='{$chunks}'";
            $orderby = '';
        }

        $rs = $modx->db->select('id,name,description,snippet', $tbl_site_htmlsnippets, $where, $orderby);

        while ($row = $modx->db->getRow($rs)) {
            $newTemplate = array(
                'title'=>$row['name'],
                'image'=>'chunk_'. $row['name'] .'.png',    // @todo: Enable set image via HTML-Comments, and strip comments then?
                'description'=>$row['description'],
                'html'=>$row['snippet']
            );
            $templatesArr[] = $newTemplate;
        }
    }
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

?>