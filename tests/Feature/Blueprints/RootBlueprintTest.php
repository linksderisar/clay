<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Blueprints\RootBlueprint;
use Linksderisar\Clay\Tests\TestCase;

class RootBlueprintTest extends TestCase
{

    /** @var RootBlueprint */
    protected $blueprint;

    /**
     * Setup Method
     */
    protected function setUp()
    {
        parent::setUp();
        $this->blueprint = RootBlueprint::create();
    }

    /** @test */
    public function empty_root_blueprint_to_json()
    {
        $this->assertEquals(
            [
                'store' => [],
                'componentTree' => null,
                'meta' => [],
                'head' => []
            ],
            $this->blueprint->toArray()
        );
    }

    /** @test */
    public function store_can_be_set_in_root_blueprint()
    {
        $this->assertEquals(
            [
                'store' => ['data' => 'value'],
                'componentTree' => null,
                'meta' => [],
                'head' => []
            ],
            $this->blueprint->setDataStore(['data' => 'value'])->toArray()
        );
    }

    /** @test */
    public function meta_can_be_set_in_root_blueprint()
    {
        $this->assertEquals(
            [
                'store' => [],
                'componentTree' => null,
                'meta' => ['meta' => 'meta-value'],
                'head' => []
            ],
            $this->blueprint->setMeta(['meta' => 'meta-value'])->toArray()
        );
    }

    /** @test */
    public function head_can_be_set_in_root_blueprint()
    {
        $this->assertEquals(
            [
                'store' => [],
                'componentTree' => null,
                'meta' => [],
                'head' => ['title' => 'Ich bin ein Titel']
            ],
            $this->blueprint->setHead(['title' => 'Ich bin ein Titel'])->toArray()
        );
    }

    /** @test */
    public function componentTree_can_be_set_in_root_blueprint()
    {
        $component = \Linksderisar\Clay\Components\Base\Component::make('div');

        $this->assertEquals(
            [
                'store' => [],
                'componentTree' => $component->toArray(),
                'meta' => [],
                'head' => []
            ],
            $this->blueprint->setComponentTree($component)->toArray()
        );
    }

    /** @test */
    public function componentTree_can_only_be_an_component_blueprint()
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Linksderisar\Clay\Components\Abstracts\Component');

        $this->blueprint->setComponentTree('a');
    }

    /** @test */
    public function componentTree_can_be_set_in_create_method_root_blueprint()
    {
        $component = \Linksderisar\Clay\Components\Base\Component::make('div');
        $blueprint = RootBlueprint::create($component);

        $this->assertEquals(
            [
                'store' => [],
                'componentTree' => $component->toArray(),
                'meta' => [],
                'head' => []
            ],
            $blueprint->toArray()
        );
    }
}
