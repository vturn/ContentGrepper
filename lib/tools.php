<?php

function replaceAbsoluteURL($homepage, $url){

    $feedUrl = parse_url($url);
    $domain = $feedUrl['scheme'] . '://' . $feedUrl['host'] . '/';

    $content = str_replace("//", "/", $homepage);
    $content = preg_replace("/(href|src)\=\"([^(http)])(\/)?/", "$1=\"$domain$2", $homepage);
    //$url = str_replace("//", "/", $url);
    return $content;
}

?>
