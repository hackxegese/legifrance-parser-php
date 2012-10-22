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

    public function getSummary($codeId)
    {
        $sections = array();

        $codes = $this->getCodes();
        if (isset($codes[$codeId])) {
            $contents = $this->get("affichCode.do?cidTexte=$codeId");
            // @FIXME <span class="TM5Code" id="LEGISCTA000006165623">Paragraphe 1 : De la garantie en cas d'éviction.</span>
            preg_match_all(
                '/<span class="TM(?<level>\d+)Code" id="(?<id>LEGISCTA\d+)">(?<title>[^"]+)<\\/span>/',
                $contents,
                $matches
            );

            if (isset($matches[0])) {
                // @TODO Make a tree
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $sections[$matches['id'][$i]] = array(
                        'level' => $matches['level'][$i] - 1,
                        'title' => htmlspecialchars_decode($matches['title'][$i], ENT_QUOTES),
                    );
                }
            }
        }
        else {
            throw new \DomainException("Code inconnu '$codeId'");
        }
        return $sections;
    }

    public function getSection($codeId, $sectionId)
    {
        $articles = array();

        $codes = $this->getCodes();
        if (isset($codes[$codeId])) {
            $contents = $this->get("affichCode.do?idSectionTA=$sectionId&cidTexte=$codeId");
            preg_match_all(
                '/<div class="titreArt">(?<title>.+) <a href=".*idArticle=(?<id>LEGIARTI\d+)/',
                $contents,
                $matches
            );

            if (isset($matches[0])) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $articles[$matches['id'][$i]] = htmlspecialchars_decode($matches['title'][$i], ENT_QUOTES);
                }
            }
        }
        else {
            throw new \DomainException("Code inconnu '$codeId'");
        }
        return $articles;
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
