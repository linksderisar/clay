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
            ->setFirstArgument('firstArgument')
            ->setOperator('===')
            ->setSecondArgument('secondArgument');

        $this->makeTestResponse($condition->toJson())
            ->assertJson([
                'if' => [
                    'firstArgument' => 'firstArgument',
                    'secondArgument' => 'secondArgument',
                    'operator' => '===',
                ]
            ]);
    }
}
