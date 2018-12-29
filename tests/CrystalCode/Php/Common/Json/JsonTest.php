<?php

namespace CrystalCode\Php\Common\Json;

use CrystalCode\Php\Common\Json\JsonTest\Bar;
use CrystalCode\Php\Common\Json\JsonTest\Foo;
use CrystalCode\Php\Common\Json\JsonTest\Qux;
use PHPUnit\Framework\TestCase;

class JsonTest extends TestCase
{

    /**
     * 
     * @covers CrystalCode\Php\Common\Json\Json::encode
     * @covers CrystalCode\Php\Common\Json\Json::getJsonValues
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

        $json = new Json($nameMapper, 0);
        $encoded = $json->encode(new Foo());
        $this->assertEquals('{"$id":"foo"}', $encoded);
    }

    /**
     * 
     * @covers CrystalCode\Php\Common\Json\Json::decode
     * @covers CrystalCode\Php\Common\Json\Json::setJsonValues
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

        $json = new Json($nameMapper, 0);
        $encoded = $json->encode(new Bar());
        $this->assertEquals('{"qux":{"a":42,"b":"Hello world!","$id":"qux"},"$id":"bar"}', $encoded);
    }

    public function test3(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
              $nameMapperBuilder->withExternalNames([
                  Foo::class => 'foo',
                  Bar::class => 'bar',
                  Qux::class => 'qux',
              ]);
          });

        $json = new Json($nameMapper, 0);
        $encoded = $json->encode((object) ['a' => 42]);
        $this->assertEquals('{"a":42}', $encoded);
    }

    public function test4(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
              $nameMapperBuilder->withExternalNames([
                  Foo::class => 'foo',
                  Bar::class => 'bar',
                  Qux::class => 'qux',
              ]);
          });

        $json = new Json($nameMapper, 0);
        $encoded = $json->encode(new Qux());
        $this->assertEquals('{"a":42,"b":"Hello world!","$id":"qux"}', $encoded);
    }

    public function test5(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
              $nameMapperBuilder->withExternalNames([
                  Foo::class => 'foo',
                  Bar::class => 'bar',
                  Qux::class => 'qux',
              ]);
          });

        $json = new Json($nameMapper, 0);
        $encoded = $json->encode(['a', 'b', 'c']);
        $this->assertEquals('["a","b","c"]', $encoded);
    }

    public function test6(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
              $nameMapperBuilder->withExternalNames([
                  Foo::class => 'foo',
                  Bar::class => 'bar',
                  Qux::class => 'qux',
              ]);
          });

        $json = new Json($nameMapper, 0);
        $decoded = $json->decode('{"a":42,"b":"Hello world!","$id":"qux"}');
        $this->assertEquals(new Qux(), $decoded);
    }

}
