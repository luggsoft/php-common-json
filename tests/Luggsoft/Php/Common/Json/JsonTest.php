<?php

namespace Luggsoft\Php\Common\Json;

use Luggsoft\Php\Common\Json\JsonTest\Bar;
use Luggsoft\Php\Common\Json\JsonTest\Foo;
use Luggsoft\Php\Common\Json\JsonTest\Qux;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{
    
    /**
     *
     * @covers Luggsoft\Php\Common\Json\Json::encode
     * @covers Luggsoft\Php\Common\Json\Json::getJsonValues
     */
    public function test1(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                Foo::class => 'foo',
                Bar::class => 'bar',
                Qux::class => 'qux',
            ]);
        });
        
        $json = new Json($nameMapper, '$id', 0);
        $encoded = $json->encode(new Foo());
        $this->assertEquals('{"$id":"foo"}', $encoded);
    }
    
    /**
     *
     * @covers Luggsoft\Php\Common\Json\Json::decode
     * @covers Luggsoft\Php\Common\Json\Json::setJsonValues
     */
    public function test2(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                Foo::class => 'foo',
                Bar::class => 'bar',
                Qux::class => 'qux',
            ]);
        });
        
        $json = new Json($nameMapper, '$id', 0);
        $encoded = $json->encode(new Bar());
        $this->assertEquals('{"$id":"bar","qux":{"$id":"qux","a":42,"b":"Hello world!"}}', $encoded);
    }
    
    /**
     *
     * @return void
     */
    public function test3(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                Foo::class => 'foo',
                Bar::class => 'bar',
                Qux::class => 'qux',
            ]);
        });
        
        $json = new Json($nameMapper, '$id', 0);
        $encoded = $json->encode((object) ['a' => 42]);
        $this->assertEquals('{"a":42}', $encoded);
    }
    
    /**
     *
     * @return void
     */
    public function test4(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                Foo::class => 'foo',
                Bar::class => 'bar',
                Qux::class => 'qux',
            ]);
        });
        
        $json = new Json($nameMapper, '$id', 0);
        $encoded = $json->encode(new Qux());
        $this->assertEquals('{"$id":"qux","a":42,"b":"Hello world!"}', $encoded);
    }
    
    /**
     *
     * @return void
     */
    public function test5(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                Foo::class => 'foo',
                Bar::class => 'bar',
                Qux::class => 'qux',
            ]);
        });
        
        $json = new Json($nameMapper, '$id', 0);
        $encoded = $json->encode(['a', 'b', 'c']);
        $this->assertEquals('["a","b","c"]', $encoded);
    }
    
    /**
     *
     * @return void
     */
    public function test6(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                Foo::class => 'foo',
                Bar::class => 'bar',
                Qux::class => 'qux',
            ]);
        });
        
        $json = new Json($nameMapper, '$id', 0);
        $decoded = $json->decode('{"$id":"qux","a":42,"b":"Hello world!"}');
        $this->assertEquals(new Qux(), $decoded);
    }
    
}
