<?php

namespace Legifrance;

class Stream
{
    public $date = null;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_url' => ['http://www.legifrance.gouv.fr']
        ]);
    }

    public function get($page)
    {
        if(strpos($page, '?') === false) {
            $page .= '?';
        }
        if(!is_null($this->date)) {
            $page .= "&dateTexte={$this->date}";
        }

        $request = $this->client->get($page);
        $response = $request->send();

        return (string)$response;
    }
}
