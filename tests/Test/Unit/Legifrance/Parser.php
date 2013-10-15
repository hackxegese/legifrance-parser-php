<?php

namespace Test\Unit\Legifrance;

class Parser extends \atoum
{
    private $stream;
    private $parser;

    public function beforeTestMethod($testMethod)
    {
        $this->stream = new \mock\Legifrance\Stream();
        $this->stream->date = '20121221';
        $this->stream->getMockController()->get = function($page) {
            $result = null;

            switch ($page) {
                case 'initRechCodeArticle.do':
                   $result = <<<EOD
<span class="selectCode">
    <select name="cidTexte" id="champ1" class="textarea"><option value="*" class="optionImpaire">-- Choisir un code --</option>
        <option value="LEGITEXT000006074069" title="Code de l&#39;action sociale et des familles" class="optionPaire">Code de l&#39;action sociale et des familles</option>
        <option value="LEGITEXT000006075116" title="Code de l&#39;artisanat" class="optionImpaire">Code de l&#39;artisanat</option>
        <option value="LEGITEXT000006070721" title="Code civil" class="optionImpaire">Code civil</option>
    </select>
</span>
EOD;
                break;
                case 'initRechCodeArticle.do':
                    $result = <<<EOD
<span class="selectCode">
    <select name="cidTexte" id="champ1" class="textarea"><option value="*" class="optionImpaire">-- Choisir un code --</option>
        <option value="LEGITEXT000006074069" title="Code de l&#39;action sociale et des familles" class="optionPaire">Code de l&#39;action sociale et des familles</option>
        <option value="LEGITEXT000006075116" title="Code de l&#39;artisanat" class="optionImpaire">Code de l&#39;artisanat</option>
        <option value="LEGITEXT000006070721" title="Code civil" class="optionImpaire">Code civil</option>
    </select>
</span>
EOD;
                break;
                case 'affichCode.do?cidTexte=LEGITEXT000006070721':
                    $result = <<<EOD
<div id="titreTexte">
            Code civil


            <br/>
<span class="sousTitreTexte">
            Version consolidée au 2 juin 2012</span>
        </div>


        <div>
   <ul class="noType">
      <li class="noType">
         <span class="TM1Code" id="LEGISCTA000006089696">Titre préliminaire : De la publication, des effets et de l'application des lois en général</span>
         <span class="codeLienArt"> (<a href="affichCode.do;jsessionid=FD767F8BA043F5CEDD44BA5C6DF64494.tpdjo02v_2?idSectionTA=LEGISCTA000006089696&amp;cidTexte=LEGITEXT000006070721&amp;dateTexte=20121023">Articles 1 à 6</a>)</span>
      </li>
   </ul>
</div>
<div>
   <ul class="noType">
      <li class="noType">
         <span class="TM1Code" id="LEGISCTA000006089697">Livre Ier : Des personnes</span>
      </li>
   </ul>
</div>
EOD;
                break;
                case 'affichCode.do?idSectionTA=LEGITEXT000006070721&cidTexte=LEGITEXT000006070721':
                    $result = <<<EOD
<div class="titreSection">Chapitre IV : De l'utilisation des techniques d'imagerie cérébrale</div>
<a id="LEGIARTI000024324450"> </a>
<div class="article">
   <div class="titreArt">Article 16-14 <a href="affichCodeArticle.do;jsessionid=FD767F8BA043F5CEDD44BA5C6DF64494.tpdjo02v_2?idArticle=LEGIARTI000024324450&amp;cidTexte=LEGITEXT000006070721&amp;dateTexte=20121023"
         title="En savoir plus sur l'article 16-14">En savoir plus sur cet article...</a>
   </div>
   <div class="histoArt">Créé par <a class="liensArtResolu"
         href="affichTexteArticle.do;jsessionid=FD767F8BA043F5CEDD44BA5C6DF64494.tpdjo02v_2?cidTexte=JORFTEXT000024323102&amp;idArticle=LEGIARTI000024324031&amp;dateTexte=20110708">LOI n°2011-814
 du 7 juillet 2011 - art. 45</a>
   </div>
   <div class="corpsArt">Les techniques d'imagerie cérébrale ne peuvent être employées qu'à des fins médicales ou de recherche scientifique, ou dans le cadre d'expertises judiciaires. Le consentement exprès de la personne doit être recueilli par écrit préalablement à la réalisation de l'examen, après qu'elle a été dûment informée de sa nature et de sa finalité. Le consentement mentionne la finalité de l'examen. Il est révocable sans forme et à tout moment.</div>
</div>
EOD;
                break;
                case 'affichCodeArticle.do?idArticle=LEGIARTI000006419289&idSectionTA=LEGITEXT000006070721&cidTexte=LEGITEXT000006070721':
                    $result = <<<EOD
<div>


    <div class="titreArt">Article 10</div>
<div class="histoArt">
   <ul>
      <li>Créé par <span class="liensArtNonResolu">Loi 1803-03-08 promulguée le 18 mars 1803</span>
      </li>
      <li>Modifié par <span class="liensArtNonResolu">Loi 1927-08-10 art. 13</span>
      </li>
      <li>Modifié par <a class="liensArtResolu"
            href="affichTexteArticle.do;jsessionid=FD767F8BA043F5CEDD44BA5C6DF64494.tpdjo02v_2?cidTexte=JORFTEXT000000864834&amp;idArticle=LEGIARTI000006492460&amp;dateTexte=20121023&amp;categorieLien=id#LEGIARTI000006492460">Loi n°72-626 du 5 juillet 1972 - art. 12 JORF 9 juillet 1972 en vigueur le 16 septembre 1972</a>
      </li>
      <li>Modifié par <a class="liensArtResolu"
            href="affichTexteArticle.do;jsessionid=FD767F8BA043F5CEDD44BA5C6DF64494.tpdjo02v_2?cidTexte=JORFTEXT000000549619&amp;idArticle=LEGIARTI000006284445&amp;dateTexte=20121023&amp;categorieLien=id#LEGIARTI000006284445">Loi n°94-653 du 29 juillet 1994 - art. 1 JORF 30 juillet 1994</a>
      </li>
   </ul>
</div>
<div class="corpsArt">
   <p/>   Chacun est tenu d'apporter son concours à la justice en vue de la manifestation de la vérité.<p/>
   <p/>   Celui qui, sans motif légitime, se soustrait à cette obligation lorsqu'il en a été légalement requis, peut être contraint d'y satisfaire, au besoin à peine d'astreinte ou d'amende civile, sans préjudice de dommages et intérêts.<p/>
</div>
<br/>
<h3>Liens relatifs à cet article</h3>
<div class="liensArtCita">
                Codifié par:
                <div class="link_list">
      <span class="liensArtNonResolu">Loi 1803-03-08</span>
      <br/>
   </div>
   <br/>
</div>
</div>
EOD;
            }
            return $result;
        };

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
        $expected = array(
            'LEGITEXT000006074069' => "Code de l'action sociale et des familles",
            'LEGITEXT000006075116' => "Code de l'artisanat",
            'LEGITEXT000006070721' => "Code civil",
        );

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
        $expected = array(
            'LEGISCTA000006089696' => array(
                'level' => 0,
                'title' => "Titre préliminaire : De la publication, des effets et de l'application des lois en général",
            ),
            'LEGISCTA000006089697' => array(
                'level' => 0,
                'title' => 'Livre Ier : Des personnes',
            ),
        );

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
        $expected = array(
            'LEGIARTI000024324450' => 'Article 16-14',
        );

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
        $expected = array(
            'title' => 'Article 10',
            'created-by' => 'Loi 1803-03-08 promulguée le 18 mars 1803',
            'modified-by' => array(
                'Loi 1927-08-10 art. 13',
                'Loi n°72-626 du 5 juillet 1972 - art. 12 JORF 9 juillet 1972 en vigueur le 16 septembre 1972',
                'Loi n°94-653 du 29 juillet 1994 - art. 1 JORF 30 juillet 1994',
            ),
            'content' => "Chacun est tenu d'apporter son concours à la justice en vue de la manifestation de la vérité.\nCelui qui, sans motif légitime, se soustrait à cette obligation lorsqu'il en a été légalement requis, peut être contraint d'y satisfaire, au besoin à peine d'astreinte ou d'amende civile, sans préjudice de dommages et intérêts.",
        );

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
