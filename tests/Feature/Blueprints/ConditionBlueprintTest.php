<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;
use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;
use Linksderisar\Clay\Tests\TestCase;

class ConditionBlueprintTest extends TestCase
{
    /** @var ConditionBlueprint */
    protected $condition;

    protected function setUp()
    {
        parent::setUp();
        $this->condition = new class extends ConditionBlueprint
        {
        };
    }

    /** @test */
    public function simple_condition_works()
    {
        $this->condition->setType('if')->setCondition('a === b');

        $this->makeTestResponse($this->condition->toJson())
            ->assertJson([
                'if' => 'a === b'
            ]);
    }

    /** @test */
    public function condition_fails_with_missing_type()
    {
        $this->expectException(RequiredBlueprintAttributeMissingException::class);
        $this->condition->setCondition('a === b')
            ->toJson();
    }

    /** @test */
    public function condition_works_with_empty_condition()
    {
        $this->condition->setType('if');

        $this->makeTestResponse($this->condition->toJson())
            ->assertJson([
                'if' => ''
            ]);
    }
}
