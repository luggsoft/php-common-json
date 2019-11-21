<?php

namespace Luggsoft\Php\Common\Json;

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
    
    /**
     *
     * @param string $externalName
     * @param string $internalName
     * @return NameMapperInterface
     */
    function withInternalName(string $externalName, string $internalName): NameMapperInterface;
    
    /**
     *
     * @param string $internalName
     * @param string $externalName
     * @return NameMapperInterface
     */
    function withExternalName(string $internalName, string $externalName): NameMapperInterface;
    
}
