<?php

namespace CrystalCode\Php\Common\Json;

interface NameMapperInterface
{

    /**
     * 
     * @param string $internalName
     * @return bool
     */
    function hasExternalName(string $internalName): bool;

    /**
     * 
     * @param string $externalName
     * @return bool
     */
    function hasInternalName(string $externalName): bool;

    /**
     * 
     * @param string $internalName
     * @return string
     */
    function getExternalName(string $internalName): string;

    /**
     * 
     * @param string $externalName
     * @return string
     */
    function getInternalName(string $externalName): string;

}
