<?php

namespace Test\Unit\Legifrance;

class Stream extends \atoum
{
    private $stream;

    public function beforeTestMethod($testMethod)
    {
        $this->stream = new \Legifrance\Stream();
    }

    public function testGet()
    {
        $this->string($this->stream->get('/'))
            ->isNotNull();
    }

    public function testSetDate()
    {
        $this->stream->date = '20121201';
        $this->string($this->stream->get('/'))
            ->isNotNull();
    }
}
