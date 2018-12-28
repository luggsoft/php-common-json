<?php

namespace CrystalCode\Php\Common\Json\JsonTest;

use CrystalCode\Php\Common\Json\JsonValuesGetterInterface;

class Qux implements JsonValuesGetterInterface
{

    /**
     * 
     * {@inheritdoc}
     */
    public function getJsonValues(): object
    {
        return (object) [
              'a' => 42,
              'b' => 'Hello world!',
        ];
    }

}
