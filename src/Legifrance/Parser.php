<?php

namespace Legifrance;

class Parser
{
    private $date = null;

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getCodes()
    {
        static $codes = null;

        if (is_null($codes)) {
            $codes = array();
            $contents = $this->get('initRechCodeArticle.do');
            preg_match_all(
                '#<option value="(?<id>LEGITEXT\d+)" title="(?<title>[^"]+)"#',
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
                '#<span class="TM(?<level>\d+)Code" id="(?<id>LEGISCTA\d+)">(?<title>[^"]+)</span>#',
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
                '#<div class="titreArt">(?<title>.+) <a href=".*idArticle=(?<id>LEGIARTI\d+)#',
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

    public function getArticle($codeId, $sectionId, $articleId)
    {
        $article = array();

        $contents = $this->get("affichCodeArticle.do?idArticle=$articleId&idSectionTA=$sectionId&cidTexte=$codeId");

        preg_match(
            '#<div class="titreArt">(?<title>.+)</div>#',
            $contents,
            $matches
        );
        if (isset($matches['title'])) {
            $article['title'] = $matches['title'];
        }

        preg_match(
            '#<li>Créé par <span class=".*">(?P<create>.*)</span>#',
            $contents,
            $matches
        );
        if (isset($matches['create'])) {
            $article['created-by'] = $matches['create'];
        }

        preg_match_all(
            '#<li>Modifié par <.*?>(?P<modify>.*?)</#ms',
            $contents,
            $matches
        );
        if (isset($matches[0])) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $article['modified-by'][] = $matches['modify'][$i];
            }
        }

        preg_match(
            '#<div class="corpsArt">(?<content>.*?)</div>#ms',
            $contents,
            $matches
        );
        if (isset($matches['content'])) {
            $markdownify = new \Markdownify(false, false);
            $article['content'] = htmlspecialchars_decode(
                $markdownify->parseString($matches['content']),
                ENT_QUOTES
            );
        }
        return $article;
    }

    protected function get($page)
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
