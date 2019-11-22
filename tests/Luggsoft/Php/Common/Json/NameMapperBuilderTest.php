<?php

namespace Luggsoft\Php\Common\Json;

use PHPUnit\Framework\TestCase;

class NameMapperBuilderTest extends TestCase
{
    
    /**
     *
     * @var NameMapperInterface
     */
    private $nameMapper;
    
    /**
     *
     * @return void
     */
    protected function setUp()
    {
        $this->nameMapper = NameMapperBuilder::buildFromCallable(function (NameMapperBuilder $nameMapperBuilder) {
            $nameMapperBuilder->withExternalNames([
                'internal1' => 'external1',
                'internal2' => 'external2',
                'internal3' => 'external3',
            ]);
        });
    }
    
    /**
     *
     * @return void
     */
    public function test1(): void
    {
        $this->assertTrue($this->nameMapper->hasExternalName('internal1'));
        $this->assertEquals('external1', $this->nameMapper->getExternalName('internal1'));
        
        $this->assertFalse($this->nameMapper->hasExternalName('nope'));
        $this->assertEquals('nope', $this->nameMapper->getExternalName('nope'));
    }
    
    /**
     *
     * @return void
     */
    public function test2(): void
    {
        $this->assertTrue($this->nameMapper->hasInternalName('external1'));
        $this->assertEquals('internal1', $this->nameMapper->getInternalName('external1'));
        
        $this->assertFalse($this->nameMapper->hasInternalName('nope'));
        $this->assertEquals('nope', $this->nameMapper->getInternalName('nope'));
    }
    
}
