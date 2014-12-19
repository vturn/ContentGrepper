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
    $currdir = dirname($url) . '/'; 

    //case 1: absolute path without protocol type, for example href="//...
    $content = preg_replace('/(href|src)\s*=\s*"\/\/(.*?)\s*"/i', '$1="http://$2"', $homepage);
    //case 2: absolute path with protocol type, for example href="http://...
    $content = preg_replace('/(href|src)\s*=\s*"(.*):\/\/(.*?)\s*"/i', '$1="$2://$3"', $content);
    //case 3: path from root, for example href="/...
    $content = preg_replace('/(href|src)\s*=\s*"\/(.*?)\s*"/i', '$1="' . $domain. '$2"', $content);
    //case 4: relative path
    $content = preg_replace('/(href|src)\s*=\s*"(.[^<:\/\/>]*?)\s*"/i', '$1="' . $currdir . '$2"', $content);
    return $content;
}

?>
