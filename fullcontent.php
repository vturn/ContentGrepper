<?php

require_once('GrepFullContent.class.php');

$link = 'https://hk.lifestyle.yahoo.com//lg-h440n-android-5-0-lollipop-071024416.html';
$local = '<!-- google_ad_section_start -->{%}<!-- google_ad_section_end -->';

$feed = new GrepFullContent($link, $local);
echo $feed->run();


?>
