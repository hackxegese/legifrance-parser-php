<?php

namespace Legifrance;

class Parser
{
    private $stream = null;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    public function getCodes()
    {
        static $codes = null;

        if (is_null($codes)) {
            $codes = [];
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

    public function getCodeTitle($codeId)
    {
        $codes = $this->getCodes();
        if (isset($codes[$codeId])) {
            return $codes[$codeId];
        }
        else {
            throw new \DomainException("Code inconnu '$codeId'");
        }
    }

    public function getSummary($codeId)
    {
        $sections = [];

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
                    $sections[$matches['id'][$i]] = [
                        'level' => $matches['level'][$i] - 1,
                        'title' => htmlspecialchars_decode($matches['title'][$i], ENT_QUOTES),
                    ];
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
        $articles = [];

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
        $article = [];

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
            $markdownify = new \Markdownify\ConverterExtra(false, false);
            $article['content'] = htmlspecialchars_decode(
                $markdownify->parseString($matches['content']),
                ENT_QUOTES
            );

            $article['content'] = preg_replace(
                '#<a href=".*?cidTexte=([^&]*?)&.*idArticle=([^&]*?)&.*".*?>(.*)</a>#ms',
                '[$3](../$1/$2.md)',
                $article['content']
            );
        }
        return $article;
    }

    public function setDate($date)
    {
        $this->stream->date = $date;
    }

    private function get($page)
    {
        return $this->stream->get($page);
    }
}
