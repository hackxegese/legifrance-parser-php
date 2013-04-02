<?php

namespace Test\Units\Legifrance;

class Stream extends \atoum
{
    private $stream;

    public function beforeTestMethod()
    {
        $this->stream = new \Legifrance\Stream();
    }

    public function testGet()
    {
        $this->string($this->stream->get('/'))
            ->isNotNull();
    }
}
