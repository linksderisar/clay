<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Blueprints\IfConditionBlueprint;
use Linksderisar\Clay\Tests\TestCase;

class IfConditionBlueprintTest extends TestCase
{
    /** @test */
    public function arguments_are_correct_wrapped_in_json()
    {
        $condition = IfConditionBlueprint::create()
            ->setFirstArgument('var1')
            ->setOperator('===')
            ->setSecondArgument('var2');

        $this->makeTestResponse($condition->toJson())
            ->assertJson([
                'if' => [
                    '===' => ['first' => 'var1', 'second' => 'var2']
                ]
            ]);
    }
}
