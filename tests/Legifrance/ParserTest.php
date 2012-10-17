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
}
