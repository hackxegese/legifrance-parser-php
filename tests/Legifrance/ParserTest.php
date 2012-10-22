<?php

class ParserTest extends PHPUnit_Framework_TestCase
{
    public function testGetCodes()
    {
        $initRechCodeArticle = <<<EOD
<span class="selectCode">
    <select name="cidTexte" id="champ1" class="textarea"><option value="*" class="optionImpaire">-- Choisir un code --</option>
        <option value="LEGITEXT000006074069" title="Code de l&#39;action sociale et des familles" class="optionPaire">Code de l&#39;action sociale et des familles</option>
        <option value="LEGITEXT000006075116" title="Code de l&#39;artisanat" class="optionImpaire">Code de l&#39;artisanat</option>
        <option value="LEGITEXT000006070721" title="Code civil" class="optionImpaire">Code civil</option>
    </select>
</span>
EOD;
        $expected = array(
            'LEGITEXT000006074069' => "Code de l'action sociale et des familles",
            'LEGITEXT000006075116' => "Code de l'artisanat",
            'LEGITEXT000006070721' => "Code civil",
        );

        $parser = $this->getMock('Legifrance\Parser', array('get'));
        $parser->expects($this->once())
            ->method('get')
            ->with('initRechCodeArticle.do')
            ->will($this->returnValue($initRechCodeArticle));

        $this->assertSame($expected, $parser->getCodes());
    }

    public function testGetSummary()
    {
        $affichCode = <<<EOD
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

        $parser = $this->getMock('Legifrance\Parser', array('get'));
        $parser->expects($this->once())
            ->method('get')
            ->with('affichCode.do?cidTexte=LEGITEXT000006070721')
            ->will($this->returnValue($affichCode));

        $this->assertSame($expected, $parser->getSummary('LEGITEXT000006070721'));
    }

    /**
     * @expectedException \DomainException
     * @expectedExceptionMessage Code inconnu '_invalid_'
     */
    public function testGetInvalidSummary()
    {
        $parser = new \Legifrance\Parser();
        $parser->getSummary('_invalid_');
    }
}
