<?php

namespace CrystalCode\Php\Common\Json;

use Exception;

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

        throw new Exception();
    }

    /**
     * 
     * {@inheritdoc}
     */
    final public function getInternalName(string $externalName): string
    {
        if (isset($this->internalNames[$externalName])) {
            return $this->internalNames[$externalName];
        }

        throw new Exception();
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
