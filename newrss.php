<?php

require_once('RssCreator.class.php');
require_once('GrepFeed.class.php');

//filename
$a = microtime();
list($usec, $sec) = explode(" ", $a);
$filename =  $sec . '-' . substr($usec, 2, 5);

$config = array(
    'global' => '',
    'local' => '<div class="article_abstract"><h3><a href="{%}">{%}</a></h3>{*}<p>{%}</p>',
    'local_content' => '',
    'order' => array('link', 'title', 'description'),
    'channel' => array(
        'title' => 'dcfever phones news',
        'link' => 'http://www.dcfever.com/news/index.php?type=phones',
        'description' => '',
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

$path = 'feeds';

$rss = new RssCreator($config, $rss_items);
$content = $rss->create_feed();
$handle = fopen($path . '/' . $filename . '.xml', 'w');
fwrite($handle, $content);
fclose($handle);

?>