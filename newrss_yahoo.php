<?php

require_once('RssCreator.class.php');
require_once('GrepFeed.class.php');

//filename
$a = microtime();
list($usec, $sec) = explode(" ", $a);
$filename =  $sec . '-' . substr($usec, 2, 5);

$config = array(
    'global' => '',
    'local' => '<div><a href="{%}" class="title{*}">{%}</a></div>{*}<p class="description">{%}<a href="',
    'order' => array('link', 'title', 'description'),
    'local_content' => '<!-- google_ad_section_start -->{%}<!-- google_ad_section_end -->',
    'channel' => array(
        'title' => 'Yahoo Men',
        'link' => 'https://hk.lifestyle.yahoo.com/men/gadgets/',
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
