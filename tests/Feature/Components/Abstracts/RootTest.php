<?php

namespace Linksderisar\Clay\Tests\Feature\Components\Abstracts;

use Linksderisar\Clay\Components\Abstracts\Root;
use Linksderisar\Clay\Components\Base\Component;
use Linksderisar\Clay\Tests\TestCase;

class RootTest extends TestCase
{

    /** @var Root */
    protected $root;

    /** @var Component */
    protected $rootComponent;

    protected function setUp()
    {
        parent::setUp();
        $this->rootComponent = Component::make('div');
        $this->root = new class($this->rootComponent) extends Root
        {
            protected $version = 'ver.1';
        };
    }

    /** @test */
    public function root_gets_correctly_converted_in_an_array()
    {
        $this->assertEquals([
            'store' => [],
            'componentTree' => $this->rootComponent->toArray(),
            'meta' => [
                'version' => 'ver.1'
            ],
            'head' => []
        ], $this->root->toArray());
    }

    /** @test */
    public function root_gets_correctly_converted_in_an_array_with_store()
    {
        $this->root->store(['store' => 'value']);

        $this->assertEquals([
            'store' => ['store' => 'value'],
            'componentTree' => $this->rootComponent->toArray(),
            'meta' => [
                'version' => 'ver.1'
            ],
            'head' => []
        ], $this->root->toArray());
    }

    /** @test */
    public function root_gets_correctly_converted_in_an_array_with_meta()
    {
        $this->root
            ->meta('meta', 'value')
            ->metas(['meta2' => 'value2']);

        $this->assertEquals([
            'store' => [],
            'componentTree' => $this->rootComponent->toArray(),
            'meta' => [
                'version' => 'ver.1',
                'meta' => 'value',
                'meta2' => 'value2'
            ],
            'head' => []
        ], $this->root->toArray());
    }

    /** @test */
    public function root_gets_correctly_converted_in_an_array_with_head()
    {
        $this->root
            ->head('head', 'value')
            ->header(['head2' => 'value2']);

        $this->assertEquals([
            'store' => [],
            'componentTree' => $this->rootComponent->toArray(),
            'meta' => [
                'version' => 'ver.1'
            ],
            'head' => ['head' => 'value', 'head2' => 'value2']
        ], $this->root->toArray());
    }
}
