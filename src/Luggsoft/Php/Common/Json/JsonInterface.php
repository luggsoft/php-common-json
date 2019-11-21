<?php

namespace Luggsoft\Php\Common\Json;

interface JsonInterface
{
    
    /**
     *
     * @param string $input
     * @return mixed
     */
    public function decode(string $input);
    
    /**
     *
     * @param mixed $value
     * @return string
     */
    public function encode($value): string;
    
}
