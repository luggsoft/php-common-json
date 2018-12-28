<?php

namespace CrystalCode\Php\Common\Json;

use PHPUnit\Framework\TestCase;
use Exception;

class NameMapperBuilderTest extends TestCase
{

    /**
     * 
     * @return void
     */
    public function test1(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
              $nameMapperBuilder->withExternalNames([
                  'internal1' => 'external1',
                  'internal2' => 'external2',
                  'internal3' => 'external3',
              ]);
          });

        $this->assertTrue($nameMapper->hasExternalName('internal1'));
        $this->assertEquals('external1', $nameMapper->getExternalName('internal1'));

        $this->assertFalse($nameMapper->hasExternalName('nope'));
        $this->expectException(Exception::class);
        $nameMapper->getExternalName('nope');
    }

    /**
     * 
     * @return void
     */
    public function test2(): void
    {
        $nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
              $nameMapperBuilder->withInternalNames([
                  'external1' => 'internal1',
                  'external2' => 'internal2',
                  'external3' => 'internal3',
              ]);
          });

        $this->assertTrue($nameMapper->hasInternalName('external1'));
        $this->assertEquals('internal1', $nameMapper->getInternalName('external1'));

        $this->assertFalse($nameMapper->hasInternalName('nope'));
        $this->expectException(Exception::class);
        $nameMapper->getInternalName('nope');
    }

}
