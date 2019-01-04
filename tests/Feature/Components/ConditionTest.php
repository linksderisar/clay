<?php

namespace Linksderisar\Clay\Tests\Feature\Components;

use Linksderisar\Clay\Components\Abstracts\Component;
use Linksderisar\Clay\Exceptions\ComponentException;
use Linksderisar\Clay\Support\Condition;
use Linksderisar\Clay\Tests\TestCase;

class ConditionTest extends TestCase
{

    /** @var Component */
    protected $component;

    protected function setUp()
    {
        parent::setUp();
        $this->component = $this->makeComponent();
    }

    /** @test */
    public function condition_syntax_works()
    {
        $this->component
            ->condition('show', function (Condition $condition) {
                return $condition('param1')->operator('===', 'param2');
            });

        $this->assertConditionJson('show', '===');
    }

    /** @test */
    public function equal_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->equal('param2');
            });

        $this->assertConditionJson('if', '==');
    }

    /** @test */
    public function exact_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->exact('param2');
            });

        $this->assertConditionJson('if', '===');
    }

    /** @test */
    public function greater_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->greater('param2');
            });

        $this->assertConditionJson('if', '>');
    }

    /** @test */
    public function greater_equal_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->greaterEqual('param2');
            });

        $this->assertConditionJson('if', '>=');
    }

    /** @test */
    public function less_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->less('param2');
            });

        $this->assertConditionJson('if', '<');
    }

    /** @test */
    public function less_equal_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->lessEqual('param2');
            });

        $this->assertConditionJson('if', '<=');
    }

    /** @test */
    public function not_equal_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->notEqual('param2');
            });

        $this->assertConditionJson('if', '!=');
    }

    /** @test */
    public function not_exact_condition_works()
    {
        $this->component
            ->condition('if', function (Condition $condition) {
                return $condition('param1')->notExact('param2');
            });

        $this->assertConditionJson('if', '!==');
    }

    /** @test */
    public function only_two_arguments_are_allowed()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage('Currently only two arguments for a condition allowed.');

        $this->component
            ->condition('show', function (Condition $condition) {
                return $condition('param1')->operator('===', 'param2')->argument('ToMuch');
            });
    }

    /** @test */
    public function only_one_operator_is_allowed()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage('Currently only one operator is allowed for a condition.');

        $this->component
            ->condition('show', function (Condition $condition) {
                return $condition('param1')->operator('===', 'param2')->addOperator('===');
            });
    }


    /**
     * @return Component
     */
    protected function makeComponent()
    {
        return new class extends Component
        {
            protected $type = 'div';
        };
    }

    protected function assertConditionJson(string $type, string $operator): void
    {
        $this->assertArrayHasKey($type, $this->component->toArray());
        $this->assertEquals(
            [$operator => ['first' => 'param1', 'second' => 'param2']],
            $this->component->toArray()[$type]
        );
    }
}
