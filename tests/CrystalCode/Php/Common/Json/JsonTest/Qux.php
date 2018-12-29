<?php

namespace CrystalCode\Php\Common\Json\JsonTest;

use CrystalCode\Php\Common\Json\JsonValuesGetterInterface;
use CrystalCode\Php\Common\Json\JsonValuesSetterInterface;

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
