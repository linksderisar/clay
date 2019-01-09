<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Blueprints\ShowConditionBlueprint;
use Linksderisar\Clay\Tests\TestCase;

class ShowConditionBlueprintTest extends TestCase
{
    /** @test */
    public function arguments_are_correct_wrapped_in_json()
    {
        $condition = ShowConditionBlueprint::create()->setCondition('a === b');

        $this->makeTestResponse($condition->toJson())
            ->assertJson([
                'show' => 'a === b'
            ]);
    }
}
