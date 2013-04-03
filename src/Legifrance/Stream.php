<?php

namespace Legifrance;

class Stream
{
    public $date = null;

    public function get($page)
    {
        return file_get_contents($this->getUrl($page));
    }

    private function getUrl($page)
    {
        $url = "http://www.legifrance.gouv.fr/$page";
        if(strpos($url, '?') === false) {
            $url .= '?';
        }
        if(!is_null($this->date)) {
            $url .= "&dateTexte={$this->date}";
        }
        return $url;
    }
}
