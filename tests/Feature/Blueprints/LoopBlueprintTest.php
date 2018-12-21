<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Tests\TestCase;
use Linksderisar\Clay\Blueprints\LoopBlueprint;
use Linksderisar\Clay\Exceptions\BlueprintException;

class LoopBlueprintTest extends TestCase
{
    /** @test */
    public function iterable_as_first_argument_in_create_blueprint_is_required()
    {
        $this->expectException(BlueprintException::class);
        $this->expectExceptionMessage('First Parameter of ' . class_basename(LoopBlueprint::class) . 'must be the iterable.');
        LoopBlueprint::create();
    }

    /** @test */
    public function loopBlueprint_can_be_converted_to_json()
    {
        $loopBlueprint = LoopBlueprint::create('loop.ref');

        $this->makeTestResponse($loopBlueprint->toJson())
            ->assertExactJson(["loop" => 'loop.ref']);
    }
}
