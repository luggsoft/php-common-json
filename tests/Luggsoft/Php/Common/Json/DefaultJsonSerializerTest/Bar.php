<?php

namespace Luggsoft\Php\Common\Json\DefaultJsonSerializerTest;

class Bar
{
    
    /**
     *
     * @var Qux
     */
    private $qux;
    
    public function __construct()
    {
        $this->qux = new Qux();
    }
    
}
