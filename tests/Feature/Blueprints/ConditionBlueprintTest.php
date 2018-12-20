<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;


use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;
use Linksderisar\Clay\Exceptions\BlueprintException;
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
    public function condition_works_with_arguments_and_operator()
    {
        $this->condition
            ->setFirstArgument('firstArgument')
            ->setOperator('===')
            ->setSecondArgument('secondArgument');

        $this->makeTestResponse($this->condition->toJson())
            ->assertJson([
                'firstArgument' => 'firstArgument',
                'secondArgument' => 'secondArgument',
                'operator' => '===',
            ]);
    }

    /** @test */
    public function condition_fails_with_missing_first_argument()
    {
        $this->expectException(RequiredBlueprintAttributeMissingException::class);

        $this->condition
            ->setOperator('===')
            ->setSecondArgument('secondArgument')
            ->toJson();
    }

    /** @test */
    public function condition_fails_with_missing_second_argument()
    {
        $this->expectException(RequiredBlueprintAttributeMissingException::class);

        $this->condition
            ->setFirstArgument('firstArgument')
            ->setOperator('===')
            ->toJson();
    }

    /** @test */
    public function condition_fails_with_missing_operator()
    {
        $this->expectException(RequiredBlueprintAttributeMissingException::class);

        $this->condition
            ->setFirstArgument('firstArgument')
            ->setSecondArgument('secondArgument')
            ->toJson();
    }

    /** @test */
    public function condition_fails_with_operator_is_not_allowed()
    {
        $this->expectException(BlueprintException::class);

        $this->condition
            ->setFirstArgument('firstArgument')
            ->setOperator('not allowed operator')
            ->setSecondArgument('secondArgument')
            ->toJson();
    }

    /** @test */
    public function condition_works_with_bind_arguments_and_operator()
    {
        $this->condition
            ->setBindFirstArgument('firstArgument')
            ->setBindOperator('===')
            ->setBindSecondArgument('secondArgument');

        $this->makeTestResponse($this->condition->toJson())
            ->assertJson([
                ':firstArgument' => 'firstArgument',
                ':secondArgument' => 'secondArgument',
                ':operator' => '===',
            ]);
    }
}
