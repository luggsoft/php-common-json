<?php

namespace Luggsoft\Php\Common\Json;

use Exception;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

abstract class JsonSerializerBase implements JsonSerializerInterface
{
    
    /**
     *
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     *
     * @var JsonSerializerSettings
     * s     */
    private $jsonSerializerSettings;
    
    /**
     *
     * @param LoggerInterface $logger
     * @param JsonSerializerSettings $jsonSerializerSettings
     */
    public function __construct(LoggerInterface $logger = null, JsonSerializerSettings $jsonSerializerSettings = null)
    {
        $this->logger = $logger ?: new class extends AbstractLogger
        {
            
            /**
             *
             * {@inheritDoc}
             */
            public function log($level, $message, array $context = [])
            {
                echo vsprintf('> %s' . PHP_EOL, [
                    json_encode([
                        'level' => $level,
                        'message' => $message,
                        'context' => $context,
                    ]),
                ]);
            }
            
        };
        
        $this->jsonSerializerSettings = $jsonSerializerSettings ?: (new JsonSerializerSettings())
            // ->setNameMapper()
            ->setPropertyName('$id')
            ->setJsonOptions(0);
    }
    
    /**
     *
     * {@inheritDoc}
     * @throws JsonSerializerException
     */
    final public function encode($value): string
    {
        try {
            $jsonValues = $this->processGetJsonValues($value);
            return json_encode($jsonValues, $this->jsonSerializerSettings->getJsonOptions() | JSON_THROW_ON_ERROR);
        }
        catch (Exception $exception) {
            $this->logger->error('Failed to encode', [
                'value' => $value,
                'exception' => $exception,
            ]);
            throw new JsonSerializerException('Failed to encode', null, $exception);
        }
    }
    
    /**
     *
     * {@inheritDoc}
     * @throws JsonSerializerException
     */
    final public function decode(string $input)
    {
        try {
            $jsonValues = json_decode($input, false, 512, $this->jsonSerializerSettings->getJsonOptions() | JSON_THROW_ON_ERROR);
            return $this->processSetJsonValues($jsonValues);
        }
        catch (Exception $exception) {
            $this->logger->error('Failed to decode', [
                'input' => $input,
                'exception' => $exception,
            ]);
            throw new JsonSerializerException('Failed to decode', null, $exception);
        }
    }
    
    /**
     *
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }
    
    /**
     *
     * @return JsonSerializerSettings
     */
    public function getJsonSerializerSettings(): JsonSerializerSettings
    {
        return $this->jsonSerializerSettings;
    }
    
    /**
     *
     * @param mixed $source
     * @return mixed
     */
    abstract protected function processGetJsonValues($source);
    
    /**
     *
     * @param mixed $source
     * @return mixed
     */
    abstract protected function processSetJsonValues($source);
    
}
