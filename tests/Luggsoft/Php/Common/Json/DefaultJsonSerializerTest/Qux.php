<?php

namespace Luggsoft\Php\Common\Json\DefaultJsonSerializerTest;

use Luggsoft\Php\Common\Json\JsonValuesGetterInterface;
use Luggsoft\Php\Common\Json\JsonValuesSetterInterface;

class Qux implements JsonValuesGetterInterface, JsonValuesSetterInterface
{
    
    /**
     *
     * @var int
     */
    private $a;
    
    /**
     *
     * @var string
     */
    private $b;
    
    /**
     *
     *
     */
    public function __construct()
    {
        $this->a = 42;
        $this->b = 'Hello world!';
    }
    
    /**
     *
     * {@inheritdoc}
     */
    public function getJsonValues(): object
    {
        return (object) [
            'a' => $this->a,
            'b' => $this->b,
        ];
    }
    
    /**
     *
     * {@inheritdoc}
     */
    public function setJsonValues(object $jsonValues): void
    {
        $this->a = $jsonValues->a;
        $this->b = $jsonValues->b;
    }
    
}
