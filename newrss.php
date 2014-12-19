<?php

require_once('RssCreator.class.php');
require_once('GrepFeed.class.php');
ini_set('memory_limit', '512M');
//filename
$a = microtime();
list($usec, $sec) = explode(" ", $a);
$filename =  $sec . '-' . substr($usec, 2, 5);


//General settings
$title = 'Feed Title';
$link = 'http://www.dcfever.com/news/index.php?type=phones';
$description = 'Feed Description';
$local = '<div class="article_abstract"><h3><a href="{%}">{%}</a></h3>{*}<p>{%}</p>';
$local_content = '<span id="word-snap-start"></span>{%}<span id="word-snap-stop"></span>';

//Feed settings
$item_title = '{%1}';
$item_url = '{%0}';
$item_description = '{%2}';

$config = array(
    'global' => '',
    'local' => $local,
    'local_content' => $local_content,
    'item_title' => $item_title,
    'item_url' => $item_url,
    'item_description' => $item_description,
    'channel' => array(
        'title' => $title,
        'link' => $link,
        'description' => $description,
        'stamp' => $filename,
        'settings' => '',
    ),
);

//var_dump($config);
$config['channel']['settings'] = urlencode(json_encode($config));

//$configjson = json_decode(urldecode($configjson), true);
//var_dump($configjson);

$feed = new GrepFeed($config);
$rss_items = $feed->run();

//var_dump($rss_items);

$path = 'feeds';

$rss = new RssCreator($config, $rss_items);
$content = $rss->create_feed();
header('Content-Type: text/xml');
//echo $content;

$handle = fopen($path . '/' . $filename . '.xml', 'w');
fwrite($handle, $content);
fclose($handle);

echo $path . '/' . $filename . '.xml';


?>
