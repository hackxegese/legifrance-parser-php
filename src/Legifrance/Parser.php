<?php

namespace Legifrance;

class Parser
{

    public function getCodes()
    {
        static $codes = null;

        if (is_null($codes)) {
            $codes = array();
            $contents = $this->get('initRechCodeArticle.do');
            preg_match_all(
                '/<option value="(?<id>LEGITEXT\d+)" title="(?<title>[^"]+)"/',
                $contents,
                $matches
            );

            if (isset($matches[0])) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $codes[$matches['id'][$i]] = htmlspecialchars_decode(
                        $matches['title'][$i],
                        ENT_QUOTES
                    );
                }
            }
        }
        return $codes;
    }

    protected function get($page)
    {
        return file_get_contents($this->getUrl($page));
    }

    private function getUrl($page)
    {
        $baseUrl = 'http://www.legifrance.gouv.fr';
        return "$baseUrl/$page";
    }
}
