<?php

require_once('RssCreator.class.php');
require_once('GrepFeed.class.php');

$currtime = time();
$period = 4; //hours
$range = 60 * 60 * $period;
$range_max = $currtime + $range;
$range_min = $currtime - $range;

$path = 'feeds';

$files = scandir($path);

function locateLastFeed($old_rss, $new_rss){
            $i = 0;
            foreach ($old_rss as $ri){
                foreach ($new_rss as $nri){
                    if ($nri['title'] == $ri['title']){
                        return $i;
                    }
                    $i++;
                }
            }
            return $i;
}


foreach ($files as $file){
    if (strstr($file, '.') == '.xml'){
        $filetimestamp = strstr($file, '-', true);
        if ($filetimestamp < $range_max && $filetimestamp > $range_min){
            $xml = file_get_contents($path . '/' . $file);
            /*$p = xml_parser_create();
            xml_parse_into_struct($p, $xml, $vals, $index);
            xml_parser_free($p); 
            print_r($index);
            print_r($vals);*/
            $x = new SimpleXmlElement($xml);

            $configjson = (string)$x->channel->config;

            $config = json_decode(urldecode($configjson), true);
            $config['channel']['settings'] = $configjson;

            $filename = $config['channel']['stamp'];
            //var_dump($configjson);
            //var_dump($config);

            $rss_items = array();

            foreach($x->channel->item as $entry) {
                $rss_item = array();
                $rss_item['title'] = (string)$entry->title;
                $rss_item['link'] = (string)$entry->link;
                $rss_item['description'] = (string)$entry->description;
                $rss_item['pubDate'] = (string)$entry->pubDate;
                $rss_item['pubStamp'] = (int)(string)$entry->pubStamp;
                $rss_items[] = $rss_item;
            }
echo '/////////////////////OLD//////////////////' . PHP_EOL;
            var_dump($rss_items);
            $newfeed = new GrepFeed($config);
            $newrss_items = $newfeed->run();
echo '/////////////////////NEW///////////////////' . PHP_EOL;
            var_dump($newrss_items);
      
            $pointer = locateLastFeed($rss_items, $newrss_items);

            echo $pointer . PHP_EOL;
echo '////////////////////////FINAL///////////////' . PHP_EOL;
            $final_rss = array();
            $j = 0;
            for ($i = 0; $i < count($rss_items); $i++){
                if ($i < $pointer){ 
                    $final_rss[] = $newrss_items[$i];
                } else {
                    $final_rss[] = $rss_items[$j++];
                }
            }

            var_dump($final_rss);
            
            $rss = new RssCreator($config, $final_rss);
            $content = $rss->create_feed();
            unlink($path . '/' . $filename . '.xml');
            $handle = fopen($path . '/' . $filename . '.xml', 'w');
            fwrite($handle, $content);
            fclose($handle);

 
         }
    }
}



?>
