<?php

namespace Luggsoft\Php\Common\Json;

abstract class NameMapperBase implements NameMapperInterface
{
    
    /**
     *
     * @var array|string[]
     */
    private $internalNames = [];
    
    /**
     *
     * @var array|string[]
     */
    private $externalNames = [];
    
    /**
     *
     * {@inheritdoc}
     */
    final public function hasExternalName(string $internalName): bool
    {
        return isset($this->externalNames[$internalName]);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    final public function hasInternalName(string $externalName): bool
    {
        return isset($this->internalNames[$externalName]);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    final public function getExternalName(string $internalName): string
    {
        if (isset($this->externalNames[$internalName])) {
            return $this->externalNames[$internalName];
        }
        
        return $this->getDefaultExternalName($internalName);
    }
    
    /**
     *
     * @param string $internalName
     * @return string
     */
    abstract protected function getDefaultExternalName(string $internalName): string;
    
    /**
     *
     * {@inheritdoc}
     */
    final public function getInternalName(string $externalName): string
    {
        if (isset($this->internalNames[$externalName])) {
            return $this->internalNames[$externalName];
        }
        
        return $this->getDefaultInternalName($externalName);
    }
    
    /**
     *
     * @param string $externalName
     * @return string
     */
    abstract protected function getDefaultInternalName(string $externalName): string;
    
    /**
     *
     * {@inheritdoc}
     */
    final public function withInternalName(string $externalName, string $internalName): NameMapperInterface
    {
        $clone = clone $this;
        $clone->setInternalName($externalName, $internalName);
        return $clone;
    }
    
    /**
     *
     * @param string $externalName
     * @param string $internalName
     * @return void
     */
    final protected function setInternalName(string $externalName, string $internalName): void
    {
        $this->internalNames[$externalName] = $internalName;
        $this->externalNames[$internalName] = $externalName;
    }
    
    /**
     *
     * {@inheritdoc}
     */
    final public function withExternalName(string $internalName, string $externalName): NameMapperInterface
    {
        $clone = clone $this;
        $clone->setExternalName($internalName, $externalName);
        return $clone;
    }
    
    /**
     *
     * @param string $internalName
     * @param string $externalName
     * @return void
     */
    final protected function setExternalName(string $internalName, string $externalName): void
    {
        $this->externalNames[$internalName] = $externalName;
        $this->internalNames[$externalName] = $internalName;
    }
    
}
