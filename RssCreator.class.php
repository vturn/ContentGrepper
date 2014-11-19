<?php

    class RssCreator {
   
    var $channel = array(
        'title' => '',
        'link' => '',
        'description' => '',
    );

    var $rss_items = array();
    var $config = array();

    function __construct($config, $rss){
        $this->config = $config;
        $this->rss_items = $rss;
    }

    public function create_feed() {

        $currTimeStr = date("D, j M G:i:s") . ' +0000'; 
        $currTime = time();

        $generator = 'VTurn RSS Compiler 1.0';
        $ttl = 360;

        $xml = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $xml .= '<rss version="2.0">' . "\n";

        // channel required properties
        $xml .= '<channel>' . "\n";
        $xml .= '<title>' . $this->config["channel"]["title"] . '</title>' . "\n";
        $xml .= '<link>' . $this->config["channel"]["link"] . '</link>' . "\n";
        $xml .= '<description><![CDATA[' . $this->config["channel"]["description"] . ']]></description>' . "\n";
        $xml .= '<lastBuildDate>' . $currTimeStr . '</lastBuildDate>' . "\n";
        $xml .= '<generator>' . $generator . '</generator>' . "\n";
        $xml .= '<ttl>' . $ttl . '</ttl>' . "\n";
        $xml .= '<config>' . $this->config["channel"]["settings"] . '</config>';
        //$xml .= '<lastBuildStamp>' . $currTime . '</lastBuildStamp>';

        // channel image properties
        /*
        $xml .= '<image>' . "\n";
        $xml .= '<title>' . $this->config["channel"]["imagetitle"] . '</title>' . "\n";
        $xml .= '<link>' . $this->config["channel"]["link"] . '</link>' . "\n";
        $xml .= '<url>' . $this->config["channel"]["imageurl"] . '</url>' . "\n";
        $xml .= '</image>' . "\n";  
        */

        // get RSS channel items
        foreach($this->rss_items as $rss_item) {
            //item title image (to be implement)

            $xml .= '<item>' . "\n";
            $xml .= '<title>' . htmlspecialchars(html_entity_decode($rss_item['title'])) . '</title>' . "\n";
            $xml .= '<link>' . $rss_item['link'] . '</link>' . "\n";
            $xml .= '<description><![CDATA[' . $rss_item['description'] . ']]></description>' . "\n";
            $xml .= '<pubDate>' . $rss_item['pubDate'] . '</pubDate>' . "\n";
            $xml .= '<pubStamp>' . $rss_item['pubStamp'] . '</pubStamp>' . "\n";
         //   $xml .= '<category>' . $rss_item['category'] . '</category>' . "\n";
         //   $xml .= '<source>' . $rss_item['source'] . '</source>' . "\n";

         /*   if($this->full_feed) {
                $xml .= '<content:encoded>' . $rss_item['content'] . '</content:encoded>' . "\n";
            }*/
            $xml .= '</item>' . "\n";

        }

        $xml .= '</channel>';
        $xml .= '</rss>';
        return $xml;
    }
    }

?>
