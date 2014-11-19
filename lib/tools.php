<?php

function generateFilename(){
    //filename
    $a = microtime();
    list($usec, $sec) = explode(" ", $a);
    return  $sec . '-' . substr($usec, 2, 5);
}

function createFile($content, $filename, $path){
    try {
        $handle = fopen($path . '/' . $filename, 'w');
        fwrite($handle, $content);
        fclose($handle);
        return true;
    } catch (Exception $e){
        echo $e->getMessage();
        return false;
    }
}

function replaceAbsoluteURL($homepage, $url){

    $feedUrl = parse_url($url);
    $domain = $feedUrl['scheme'] . '://' . $feedUrl['host'] . '/';

    $content = str_replace("//", "/", $homepage);
    $content = preg_replace("/(href|src)\=\"([^(http)])(\/)?/", "$1=\"$domain$2", $homepage);
    //$url = str_replace("//", "/", $url);
    return $content;
}

?>
