<?php

require_once('GrepFeedBasic.class.php');

class GrepFeed {
 
    var $url = '';
    var $global = '';
    var $repeat = '';
    var $pointer = 0;
    var $blocks = array();
    var $rss_items = array();
    var $order = array();
    var $currTimeStr = '';
    var $currTime = null;
    var $config = array();
    var $contentConfig = '';
    var $grepfeedbasic = null;

    function __construct($config){
        $this->config = $config;
        $this->url = $config['channel']['link'];
        $this->global = $config['global'];
        $this->repeat = $config['local'];
        $this->item_title = $config['item_title'];
        $this->item_url = $config['item_url'];
        $this->item_description = $config['item_description'];
        if (isset($config['local_content'])){
            $this->contentConfig = $config['local_content'];
        }
        if (empty($this->url)){
            return;
        }
        $this->currTimeStr = date("D, j M G:i:s") . ' +0800';
        $this->currTime = time();
    }

    public function run(){
        $feed = new GrepFeedBasic($this->url, $this->repeat);
        $rss_feeds = $feed->run();
        $new_rss_feeds = array();
        foreach ($rss_feeds as $rss_feed){
            $rss_feed['title'] = str_replace($rss_feed['search'], $rss_feed['replace'], $this->item_title);
            $rss_feed['link'] = str_replace($rss_feed['search'], $rss_feed['replace'], $this->item_url);
            $rss_feed['description'] = '';
            if ($this->contentConfig != ''){
                require_once('GrepFullContent.class.php');
                $feed = new GrepFullContent($rss_feed['link'], $this->contentConfig);
                $rss_feed['description'] = $feed->run();
            } 
            if ($rss_feed['description'] == ''){
                $rss_feed['description'] = str_replace($rss_feed['search'], $rss_feed['replace'], $this->item_description);
            }
            $new_rss_feeds[] = $rss_feed;
        }
        return $new_rss_feeds;
    }
}

?>
