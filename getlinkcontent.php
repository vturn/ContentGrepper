<?php

require_once('lib/tools.php');

if (!isset($_GET["a"])){
    return;
}
$action = $_GET["a"];

if ($action == 'one'){

    if (!isset($_GET["u"])){
        return;
    }
    $url = $_GET["u"];
    $ch = curl_init($url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $content = curl_exec($ch);
    curl_close($ch);
    $content = replaceAbsoluteURL($content, $url);
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
        echo trim(htmlentities($line)).PHP_EOL;
    } 
}

if ($action == 'two'){
    if (!isset($_GET["u"])){
        return;
    }
    if (!isset($_GET["p"])){
        return;
    }
    $url = $_GET["u"];
    $pattern = $_GET["p"];

    require_once('GrepFeedBasic.class.php');

    $feed = new GrepFeedBasic($url, $pattern);
    $rss_items = $feed->run();

    foreach ($rss_items as $count => $rss_item){
        /*foreach ($rss_item as $key => $item){
            if ($keytext == '{%pubDate}'){
                echo '<div class="two_feed_time">Item ' . ($count + 1) . ': ' . $item . '</div>';
                continue;
            }
        }*/
        echo '<div class="two_feed_time">Item ' . ($count + 1) . ': ' . $rss_item['pubDate']. '</div>';
        for($i = 0; $i < sizeof($rss_item['search']); $i++){
            echo '<div class="two_feed_content"><span class="red_bold">' . $rss_item['search'][$i] . '</span> = ' . $rss_item['replace'][$i]. '</div>';
        }
        echo '<div class="space"></div>';
    } 

}

?>
