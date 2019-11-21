<?php

namespace Luggsoft\Php\Common\Json;

use Luggsoft\Php\Common\ValuesObject;
use ReflectionClass as ClassReflection;
use stdClass;

interface JsonService
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

final class DefaultJsonService implements JsonService
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
    private $options;
    
    /**
     *
     * @var string
     */
    private $propertyName;
    
    /**
     *
     * @param NameMapperInterface $nameMapper
     * @param string $propertyName
     * @param int $options
     */
    public function __construct(NameMapperInterface $nameMapper, string $propertyName = '$class', int $options = JSON_PRETTY_PRINT)
    {
        $this->nameMapper = $nameMapper;
        $this->options = $options;
        $this->propertyName = $propertyName;
    }
    
    /**
     *
     * {@inheritDoc}
     */
    public function encode($value): string
    {
        $jsonValues = $this->processGetJsonValues($value);
        return json_encode($jsonValues, $this->options);
    }
    
    /**
     *
     * @param mixed $source
     * @return mixed
     */
    private function processGetJsonValues($source)
    {
        if (is_object($source)) {
            if ($source instanceof stdClass) {
                return $source;
            }
            
            $internalName = get_class($source);
            
            if ($source instanceof JsonValuesGetterInterface) {
                $result = [];
                $jsonValues = $source->getJsonValues();
                
                foreach (get_object_vars($jsonValues) as $key => $value) {
                    $result[$key] = $this->processGetJsonValues($value);
                }
                
                return (object) (['$id' => $this->nameMapper->getExternalName($internalName)] + $result);
            }
            
            $result = [];
            $classReflection = new ClassReflection($source);
            
            foreach ($classReflection->getProperties() as $propertyReflection) {
                $propertyReflection->setAccessible(true);
                $key = $propertyReflection->getName();
                $value = $propertyReflection->getValue($source);
                $result[$key] = $this->processGetJsonValues($value);
            }
            
            return (object) (['$id' => $this->nameMapper->getExternalName($internalName)] + $result);
        }
        
        if (is_array($source)) {
            $result = [];
            
            foreach ($source as $key => $value) {
                $result[$key] = $this->processGetJsonValues($value);
            }
            
            return $result;
        }
        
        return $source;
    }
    
    /**
     *
     * {@inheritDoc}
     */
    public function decode(string $input)
    {
        $jsonValues = json_decode($input);
        return $this->processSetJsonValues($jsonValues);
    }
    
    /**
     *
     * @param mixed $source
     * @return mixed
     */
    private function processSetJsonValues($source)
    {
        if (is_object($source)) {
            if (isset($source->{'$id'})) {
                $internalName = $this->nameMapper->getInternalName($source->{'$id'});
                $classReflection = new ClassReflection($internalName);
                $result = $classReflection->newInstanceWithoutConstructor();
                $jsonValues = [];
                
                foreach (get_object_vars($source) as $key => $value) {
                    $jsonValues[$key] = $this->processSetJsonValues($value);
                }
                
                if ($result instanceof JsonValuesSetterInterface) {
                    $jsonValuesObject = ValuesObject::create($jsonValues);
                    $result->setJsonValues($jsonValuesObject);
                    return $result;
                }
                
                foreach ($jsonValues as $key => $value) {
                    $propertyReflection = $classReflection->getProperty($key);
                    $propertyReflection->setAccessible(true);
                    $propertyReflection->setValue($result, $value);
                }
                
                return $result;
            }
            
            return $source;
        }
        
        if (is_array($source)) {
            $result = [];
            
            foreach ($source as $key => $value) {
                $result[$key] = $this->processSetJsonValues($value);
            }
            
            return $result;
        }
        
        return $source;
    }
    
    /**
     *
     * @return string
     */
    public function getPropertyName(): string
    {
        return $this->propertyName;
        return $this;
    }
    
    /**
     *
     * @param string $propertyName
     * @return self
     */
    public function setPropertyName(string $propertyName): void
    {
        $this->propertyName = $propertyName;
    }
    
}
