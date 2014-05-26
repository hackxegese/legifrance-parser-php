<?php

namespace Test\Unit\Legifrance;

class Stream extends Test
{
    public function testGet()
    {
        $this->string($this->stream->get('initRechCodeArticle.do'))
            ->isNotNull();
    }

    public function testSetDate()
    {
        $this->stream->date = '20121201';
        $this->string($this->stream->get('initRechCodeArticle.do'))
            ->isNotNull();
    }
}
