<?php

class GrepFeed {
 
    var $url = '';
    var $global = '';
    var $repeat = '';
    var $pointer = 0;
    var $homepage = '';
    var $blocks = array();
  
    var $rss_items = array();
    var $order = array();

    var $currTimeStr = '';
    var $currTime = null;

    var $config = array();

    var $contentConfig = '';

    function __construct($config){
        $this->config = $config;
        $this->url = $config['channel']['link'];
        $this->global = $config['global'];
        $this->repeat = $config['local'];
        //override order
        $this->order = $config['order'];
        if (isset($config['local_content'])){
            $this->contentConfig = $config['local_content'];
        }
        if (empty($this->url)){
            return;
        }
        $homepage = file_get_contents($this->url);
        if ($homepage == FALSE || strlen($homepage) == 0){
            return;
        }
        $feedUrl = parse_url($this->url);
        $domain = $feedUrl['scheme'] . '://' . $feedUrl['host'] . '/';
        $this->homepage = $this->replaceAbsoluteURL($homepage, $domain);

        $this->currTimeStr = date("D, j M G:i:s") . ' +0000';
        $this->currTime = time();

    }

    private function replaceAbsoluteURL($homepage, $domain){
        $url = str_replace("//", "/", $homepage);
        $url = preg_replace("/(href|src)\=\"([^(http)])(\/)?/", "$1=\"$domain$2", $homepage);
        //$url = str_replace("//", "/", $url);
        return $url;
    }

    private function globalSearch(){
        $pointer = 0;
        return $pointer;
    }

    private function formLocalSearchBlocks($repeat){
        $include = explode('{%}', $repeat);
        $blocks = array();
        for ($i = 0; $i < count($include) - 1; $i++){
            $start = '';
            $end = '';
            $pre_excl = explode('{*}', $include[$i]);
            $post_excl = explode('{*}', $include[$i+1]);
            if (count($pre_excl) == 2){
                $start = $pre_excl[1];
            } else {
                $start = $include[$i];
            }
            if (count($post_excl) == 2){
                $end = $post_excl[0];
            } else {
                $end = $include[$i+1];
            }
            $block = new stdClass();
            $block->start = $start;
            $block->end = $end;
            $blocks[] = $block;
            //echo $i . ': ' . ' precount: ' . count($pre_excl) . ' postcount: ' . count($post_excl) . ' ' . $start . '---' . $end . PHP_EOL;
        }
        return $blocks;
    }

    private function localSearch(){
        $this->blocks = $this->formLocalSearchBlocks($this->repeat);
    }

    private function grepContent($homepage, $block, $count){
        $s = strpos($homepage, $block->start, $this->pointer);
        if ($s == FALSE){
            $this->pointer = strlen($homepage);
            return false;
        }
        $e = strpos($homepage, $block->end, $s+strlen($block->start));// + $pointer;
/*        echo 'Range: ' . $s . '(' . $block->start . ')-'. $e . '('.$block->end.')' . PHP_EOL;
echo 'block start: ' . strlen($block->start). PHP_EOL;
echo 'block end: ' . strlen($block->end). PHP_EOL;
echo 'pointer: ' . $pointer . PHP_EOL;
echo 's: ' . $s . PHP_EOL;
echo 'e: ' . $e . PHP_EOL;
echo 'e-s: ' . ($e-$s-44) . PHP_EOL;*/
        $this->pointer = $e;
        return substr($homepage, $s+strlen($block->start) , ($e-$s-strlen($block->start))).PHP_EOL;//($e+strlen($block->end)) - ($s+strlen($block->start))).PHP_EOL;
//if ($pointer > 0)
//die();
    }

    private function getFeedContent(){
        $count = 0;
        $rss_item = array();
        foreach ($this->blocks as $block){
            if ($this->pointer < strlen($this->homepage)){
                if ($content = $this->grepContent($this->homepage, $block, $count)){
                    /*if ($this->order[$count] == 'link'){
                        //$content = urlencode($content);
                    }*/
                    $rss_item[$this->order[$count]] = html_entity_decode(trim($content));
                } else {
                    return;
                }
            }
            $count++;
        }
        if (!empty($this->contentConfig)){
            //2nd level grep content
            require_once('GrepFullContent.class.php');
            if (isset($rss_item['link']) && (!empty($rss_item['link']))){
                $feedContent = new GrepFullContent($rss_item['link'], $this->contentConfig);
                $rss_item['description'] = html_entity_decode(trim($feedContent->run()));
            }
        }

        $rss_item['pubDate'] = $this->currTimeStr;
        $rss_item['pubStamp'] = $this->currTime;

        $this->rss_items[] = $rss_item;
        if ($this->pointer < strlen($this->homepage)){
            $this->getFeedContent();
        }
    }

    public function run(){
        $this->pointer = $this->globalSearch();
        $this->localSearch();
        $this->getFeedContent();
        return $this->rss_items;
    }

}

?>
