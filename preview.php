<?php

require_once('RssCreator.class.php');
require_once('GrepFeed.class.php');

//filename
$a = microtime();
list($usec, $sec) = explode(" ", $a);
$filename =  $sec . '-' . substr($usec, 2, 5);


//General settings
$title = $_POST['three_feed_title'];
$link = $_POST['three_feed_url'];
$description = $_POST['three_feed_desc'];
$local = $_POST['local'];
$local_content = '';

//Feed settings
$item_title = $_POST['three_item_title'];
$item_url = $_POST['three_item_url'];
$item_description = $_POST['three_item_desc'];

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
//header('Content-Type: text/xml');
//echo $content;

$handle = fopen($path . '/' . $filename . '.xml', 'w');
fwrite($handle, $content);
fclose($handle);

echo $path . '/' . $filename . '.xml';


?>
