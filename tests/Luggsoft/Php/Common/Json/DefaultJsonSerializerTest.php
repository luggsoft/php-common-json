<?php

namespace Luggsoft\Php\Common\Json;

use Exception;
use Luggsoft\Php\Common\Json\DefaultJsonSerializerTest\Bar;
use Luggsoft\Php\Common\Json\DefaultJsonSerializerTest\Foo;
use Luggsoft\Php\Common\Json\DefaultJsonSerializerTest\Qux;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;

class DefaultJsonSerializerTest extends TestCase
{
    
    /**
     *
     * @var DefaultJsonSerializer
     */
    private $jsonSerializer;
    
    /**
     *
     * @return void
     */
    protected function setUp(): void
    {
        $logger = new class extends AbstractLogger
        {
            
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
        
        $jsonSerializerSettings = (new JsonSerializerSettings())
            ->setNameMapper(NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
                $nameMapperBuilder->withExternalNames([
                    Foo::class => 'foo',
                    Bar::class => 'bar',
                    Qux::class => 'qux',
                ]);
            }))
            ->setPropertyName('$id');
        
        $this->jsonSerializer = new DefaultJsonSerializer($logger, $jsonSerializerSettings);
    }
    
    /**
     *
     * @covers Luggsoft\Php\Common\Json\DefaultJsonSerializer::encode
     * @covers Luggsoft\Php\Common\Json\DefaultJsonSerializer::getJsonValues
     * @throws JsonSerializerException
     */
    public function test1(): void
    {
        $encoded = $this->jsonSerializer->encode(new Foo());
        $this->assertEquals('{"$id":"foo"}', $encoded);
    }
    
    /**
     *
     * @covers Luggsoft\Php\Common\Json\DefaultJsonSerializer::decode
     * @covers Luggsoft\Php\Common\Json\DefaultJsonSerializer::setJsonValues
     * @throws JsonSerializerException
     */
    public function test2(): void
    {
        $encoded = $this->jsonSerializer->encode(new Bar());
        $this->assertEquals('{"$id":"bar","qux":{"$id":"qux","a":42,"b":"Hello world!"}}', $encoded);
    }
    
    /**
     *
     * @return void
     * @throws JsonSerializerException
     */
    public function test3(): void
    {
        $encoded = $this->jsonSerializer->encode((object) ['a' => 42]);
        $this->assertEquals('{"a":42}', $encoded);
    }
    
    /**
     *
     * @return void
     * @throws JsonSerializerException
     */
    public function test4(): void
    {
        $encoded = $this->jsonSerializer->encode(new Qux());
        $this->assertEquals('{"$id":"qux","a":42,"b":"Hello world!"}', $encoded);
    }
    
    /**
     *
     * @return void
     * @throws JsonSerializerException
     */
    public function test5(): void
    {
        $encoded = $this->jsonSerializer->encode(['a', 'b', 'c']);
        $this->assertEquals('["a","b","c"]', $encoded);
    }
    
    /**
     *
     * @return void
     * @throws JsonSerializerException
     */
    public function test6(): void
    {
        $decoded = $this->jsonSerializer->decode('{"$id":"qux","a":42,"b":"Hello world!"}');
        $this->assertEquals(new Qux(), $decoded);
    }
    
    /**
     *
     * @return void
     */
    public function test7(): void
    {
        try {
            $decoded = $this->jsonSerializer->decode('{"$id":"nope"}');
        }
        catch (Exception $exception) {
            $this->assertInstanceOf(JsonSerializerException::class, $exception);
        }
    }
    
}
