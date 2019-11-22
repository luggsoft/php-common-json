<?php

namespace Luggsoft\Php\Common\Json;

final class JsonSerializerSettings
{
    
    /**
     *
     * @var NameMapperInterface
     */
    private $nameMapper;
    
    /**
     *
     * @var int
     */
    private $jsonOptions = 0;
    
    /**
     *
     * @var string
     */
    private $propertyName = '$id';
    
    /**
     *
     * @return NameMapperInterface
     */
    public function getNameMapper(): NameMapperInterface
    {
        return $this->nameMapper;
    }
    
    /**
     * @param NameMapperInterface $nameMapper
     * @return $this
     */
    public function setNameMapper(NameMapperInterface $nameMapper): self
    {
        $this->nameMapper = $nameMapper;
        return $this;
    }
    
    /**
     *
     * @return int
     */
    public function getJsonOptions(): int
    {
        return $this->jsonOptions;
    }
    
    /**
     *
     * @param int $jsonOptions
     * @return $this
     */
    public function setJsonOptions(int $jsonOptions): self
    {
        $this->jsonOptions = $jsonOptions;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
    }
    
    /**
     *
     * @param string $propertyName
     * @return $this
     */
    public function setPropertyName(string $propertyName): self
    {
        $this->propertyName = $propertyName;
        return $this;
    }
    
}
