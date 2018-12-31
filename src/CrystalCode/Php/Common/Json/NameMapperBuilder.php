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
             * @param string $internalName
             * @return string
             */
            protected function getDefaultExternalName(string $internalName): string
            {
                return $internalName;
            }

            /**
             * 
             * @param string $externalName
             * @return string
             */
            protected function getDefaultInternalName(string $externalName): string
            {
                return $externalName;
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
