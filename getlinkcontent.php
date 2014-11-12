<?php

require_once('lib/tools.php');

if (!isset($_GET["u"])){
    return;
}
    $url = $_GET["u"];
    //$url = "http://www.dcfever.com/news/index.php?type=phones";
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $content = curl_exec($ch);
    curl_close($ch);
    $content = replaceAbsoluteURL($content, $url);
    $temp = tmpfile();
fwrite($temp, $content);
fseek($temp, 0);
show_source($temp);
fclose($temp);
    //highlight_string($content);
    //echo show_source('tmp.html');
?>
