<?php

namespace CrystalCode\Php\Common\Json;

final class NameMapperBuilder
{

    /**
     * 
     * @param callable $callable
     * @return NameMapperInterface
     */
    public static function buildFromCallable(callable $callable): NameMapperInterface
    {
        $nameMapperBuilder = new NameMapperBuilder();
        call_user_func($callable, $nameMapperBuilder);
        return $nameMapperBuilder->build();
    }

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
     * @param iterable $internalNames
     * @return NameMapperBuilder
     */
    public function withInternalNames(iterable $internalNames): NameMapperBuilder
    {
        foreach ($internalNames as $externalName => $internalName) {
            $this->internalNames[(string) $externalName] = (string) $internalName;
        }

        return $this;
    }

    /**
     * 
     * @param iterable $externalNames
     * @return NameMapperBuilder
     */
    public function withExternalNames(iterable $externalNames): NameMapperBuilder
    {
        foreach ($externalNames as $internalName => $externalName) {
            $this->externalNames[(string) $internalName] = (string) $externalName;
        }

        return $this;
    }

    /**
     * 
     * @return NameMapperInterface
     */
    public function build(): NameMapperInterface
    {
        $nameMapper = new class extends NameMapperBase {

            /**
             * 
             * @param string $externalName
             * @param string $internalName
             * @return self
             */
            public function withInternalName(string $externalName, string $internalName): self
            {
                $clone = clone $this;
                $clone->setInternalName($externalName, $internalName);
                return $clone;
            }

            /**
             * 
             * @param string $externalName
             * @param string $internalName
             * @return self
             */
            public function withExternalName(string $internalName, string $externalName): self
            {
                $clone = clone $this;
                $clone->setExternalName($internalName, $externalName);
                return $clone;
            }

        };

        foreach ($this->internalNames as $externalName => $internalName) {
            $nameMapper = $nameMapper->withInternalName($externalName, $internalName);
        }

        foreach ($this->externalNames as $internalName => $externalName) {
            $nameMapper = $nameMapper->withExternalName($internalName, $externalName);
        }

        return $nameMapper;
    }

}
