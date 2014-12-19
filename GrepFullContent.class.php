<?php

require_once('GrepFeedBasic.class.php');

class GrepFullContent extends GrepFeedBasic {
 
    var $content = '';

    protected function getFeedContent(){
        $count = 0;
        $returnContent = '';
        foreach ($this->blocks as $block){
            if ($this->pointer < strlen($this->homepage)){
                if ($content = $this->grepContent($this->homepage, $block, $count)){
                     $returnContent .= $content;
                } else {
                    return;
                }
            }
            $count++;
        }
        $this->content = $returnContent;
        //if ($this->pointer < strlen($this->homepage)){
        //    $this->getFeedContent();
        //}
    }

    public function run(){
        if (!$this->checkContent()){
            return false;
        }
        $this->pointer = $this->globalSearch();
        $this->localSearch();
        $this->getFeedContent();
        return $this->content;
    }

    private function checkContent(){
        $search = explode("{%}", $this->repeat);
        $result = strpos($this->homepage, $search[0]);
        return $result;
    }

}

?>
