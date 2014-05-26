<?php

namespace Test\Unit\Legifrance;

class Parser extends Test
{
    private $parser;

    public function beforeTestMethod($testMethod)
    {
        parent::beforeTestMethod($testMethod);

        $this->parser = new \Legifrance\Parser($this->stream);
    }

    public function testCreation()
    {
        $stream = new \Legifrance\Stream();
        $this->object(new \Legifrance\Parser($stream))
            ->isInstanceOf('\Legifrance\Parser');
    }

    public function testGetCodes()
    {
        $expected = [
            'LEGITEXT000006074069' => "Code de l'action sociale et des familles",
            'LEGITEXT000006075116' => "Code de l'artisanat",
            'LEGITEXT000006070721' => "Code civil",
        ];

        $this->array($this->parser->getCodes())
            ->isIdenticalTo($expected);
    }

    public function testGetCodeTitle()
    {
        $this->string($this->parser->getCodeTitle('LEGITEXT000006070721'))
            ->isIdenticalTo('Code civil');
    }

    public function testGetInvalidCodeTitle()
    {
        $this
            ->exception(function() {
                $this->parser->getCodeTitle('_invalid_');
            })
            ->hasMessage("Code inconnu '_invalid_'")
            ->isInstanceOf('\DomainException');
    }

    public function testGetSummary()
    {
        $expected = [
            'LEGISCTA000006089696' => [
                'level' => 0,
                'title' => "Titre préliminaire : De la publication, des effets et de l'application des lois en général",
            ],
            'LEGISCTA000006089697' => [
                'level' => 0,
                'title' => 'Livre Ier : Des personnes',
            ],
        ];

        $this->array($this->parser->getSummary('LEGITEXT000006070721'))
            ->isIdenticalTo($expected);
    }

    public function testGetInvalidSummary()
    {
        $this
            ->exception(function() {
                $this->parser->getSummary('_invalid_');
            })
            ->hasMessage("Code inconnu '_invalid_'")
            ->isInstanceOf('\DomainException');
    }

    public function testGetSection()
    {
        $expected = [
            'LEGIARTI000024324450' => 'Article 16-14',
        ];

        $this->array($this->parser->getSection('LEGITEXT000006070721', 'LEGITEXT000006070721'))
            ->isIdenticalTo($expected);
    }

    public function testGetInvalidSection()
    {
        $this
            ->exception(function() {
                $this->parser->getSection('_invalid_', '_invalid_');
            })
            ->hasMessage("Code inconnu '_invalid_'")
            ->isInstanceOf('\DomainException');
    }

    public function testGetArticle()
    {
        $expected = [
            'title' => 'Article 10',
            'created-by' => 'Loi 1803-03-08 promulguée le 18 mars 1803',
            'modified-by' => [
                'Loi 1927-08-10 art. 13',
                'Loi n°72-626 du 5 juillet 1972 - art. 12 JORF 9 juillet 1972 en vigueur le 16 septembre 1972',
                'Loi n°94-653 du 29 juillet 1994 - art. 1 JORF 30 juillet 1994',
            ],
            'content' => <<<EOD
Chacun est tenu d'apporter son concours à la justice en vue de la manifestation de la vérité.
Celui qui, sans motif légitime, se soustrait à cette obligation lorsqu'il en a été légalement requis, peut être contraint d'y satisfaire, au besoin à peine d'astreinte ou d'amende civile, sans préjudice de dommages et intérêts.
Les deux premiers alinéas de [l'article 132-23](../LEGITEXT000006070719/LEGIARTI000006417401.md) relatif à la période de sûreté sont applicables à l'infraction prévue par le présent article.
EOD
        ];

        $this->array($this->parser->getArticle('LEGITEXT000006070721', 'LEGITEXT000006070721', 'LEGIARTI000006419289'))
            ->isIdenticalTo($expected);
    }

    public function testSetDate()
    {
        $this->parser->setDate('20130101');
        $this->string($this->stream->date)
            ->isIdenticalTo('20130101');
    }
}
