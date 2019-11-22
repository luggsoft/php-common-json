<?php

namespace Luggsoft\Php\Common\Json;

use Luggsoft\Php\Common\ArgumentException;
use Luggsoft\Php\Common\ValuesObject;
use Psr\Log\LoggerInterface;
use ReflectionClass as ClassReflection;
use ReflectionException;
use stdClass;

final class DefaultJsonSerializer extends JsonSerializerBase
{
    
    /**
     *
     * @param LoggerInterface $logger
     * @param JsonSerializerSettings $jsonSerializerSettings
     */
    public function __construct(LoggerInterface $logger, JsonSerializerSettings $jsonSerializerSettings)
    {
        parent::__construct($logger, $jsonSerializerSettings);
    }
    
    /**
     *
     * @param mixed $source
     * @return mixed
     * @throws ReflectionException
     */
    protected function processGetJsonValues($source)
    {
        $nameMapper = $this->getJsonSerializerSettings()->getNameMapper();
        $propertyName = $this->getJsonSerializerSettings()->getPropertyName();
        
        if (is_object($source)) {
            if ($source instanceof stdClass) {
                return $source;
            }
            
            $internalName = get_class($source);
            $this->getLogger()->debug("Getting JSON values for ${internalName}");
            
            if ($source instanceof JsonValuesGetterInterface) {
                $result = [];
                $jsonValues = $source->getJsonValues();
                
                foreach (get_object_vars($jsonValues) as $key => $value) {
                    $result[$key] = $this->processGetJsonValues($value);
                }
                
                return (object) (['$id' => $nameMapper->getExternalName($internalName)] + $result);
            }
            
            $result = [];
            $classReflection = new ClassReflection($source);
            
            foreach ($classReflection->getProperties() as $propertyReflection) {
                $propertyReflection->setAccessible(true);
                $key = $propertyReflection->getName();
                $value = $propertyReflection->getValue($source);
                $result[$key] = $this->processGetJsonValues($value);
            }
            
            return (object) ([$propertyName => $nameMapper->getExternalName($internalName)] + $result);
        }
        
        if (is_array($source)) {
            $this->getLogger()->debug("Getting JSON values for array");
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
     * @param mixed $source
     * @return mixed
     * @throws ArgumentException
     * @throws ReflectionException
     */
    protected function processSetJsonValues($source)
    {
        $nameMapper = $this->getJsonSerializerSettings()->getNameMapper();
        $propertyName = $this->getJsonSerializerSettings()->getPropertyName();
        
        if (is_object($source)) {
            if (isset($source->{"$propertyName"})) {
                $internalName = $nameMapper->getInternalName($source->{"$propertyName"});
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
    
}
