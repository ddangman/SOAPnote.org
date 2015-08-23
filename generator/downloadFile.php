<?php
include(dirname(__FILE__).'/functions.php');
$userId = getUserId();
if($_GET['type'] == 'txt') {
        $realFilename = $_SESSION['txtFileName'] . '.txt';
        $filename =  $userId .'.txt';
        $filepath = DATA_PATH.'/text/'.$userId .'.txt';
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=\"".$realFilename."\"");
        //header("Content-Transfer-Encoding: binary");
        //header("Content-Length: ".filesize($filepath));
        ob_end_flush();
        @readfile($filepath);
} else if($_GET['type'] == 'zip') {
        $realFilename = $_SESSION['txtFileName'] . '.zip';
        
        $filename =  $userId .'.zip';
        $filepath = DATA_PATH.'/zip/'.$userId .'.zip';
        $content = file_get_contents(DATA_PATH.'/html/'.$userId . '.html');
        
        getHtml($content, $filepath);
        
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$realFilename."\"");
        header("Content-Transfer-Encoding: binary");
        
        ob_end_flush();
        @readfile($filepath);
}
