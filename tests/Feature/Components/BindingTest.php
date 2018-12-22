<?php

namespace Linksderisar\Clay\Tests\Feature\Components;


use Linksderisar\Clay\Components\Abstracts\Component;
use Linksderisar\Clay\Exceptions\BindException;
use Linksderisar\Clay\Tests\TestCase;

class BindingTest extends TestCase
{
    /** @var Component */
    protected $component;

    protected function setUp()
    {
        parent::setUp();
        $this->component = $this->makeComponent();
    }

    /** @test */
    public function binding_attributes_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component
                ->attributes(['id' => 'identifier'])
                ->attribute('atr', 'value');
        });

        $this->assertArrayHasKey(':id', $this->component->toArray()['attributes']['attrs']);
        $this->assertArrayHasKey(':atr', $this->component->toArray()['attributes']['attrs']);
    }

    /** @test */
    public function binding_props_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component
                ->props(['props' => 'value'])
                ->prop('prop', 'value');
        });

        $this->assertArrayHasKey(':props', $this->component->toArray()['attributes']['props']);
        $this->assertArrayHasKey(':prop', $this->component->toArray()['attributes']['props']);
    }

    /** @test */
    public function binding_classes_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component
                ->classes('css-class');
        });

        $this->assertArrayHasKey(':class', $this->component->toArray()['attributes']);
    }

    /** @test */
    public function binding_text_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component
                ->text('text');
        });

        $this->assertArrayHasKey(':value', $this->component->toArray()['children']);
    }

    /** @test */
    public function binding_key_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component->key('key');
        });

        $this->assertArrayHasKey(':key', $this->component->toArray()['attributes']);
    }

    /** @test */
    public function binding_ref_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component->ref('ref');
        });

        $this->assertArrayHasKey(':ref', $this->component->toArray()['attributes']);
    }

    /** @test */
    public function chaining_while_binding_is_possible()
    {
        $this->component->bind(function ($component) {
            return $component
                ->prop('prop', 'value')
                ->key('key');
        });

        $this->assertArrayHasKey(':prop', $this->component->toArray()['attributes']['props']);
        $this->assertArrayHasKey(':key', $this->component->toArray()['attributes']);

    }

    /** @test */
    public function binding_is_not_possible_if_deactivated()
    {
        $this->expectException(BindException::class);
        $this->expectExceptionMessage('Binding is deactivated for this Class.');

        $component = new class extends Component
        {
            protected $type = 'div';
            protected $bindable = false;
        };

        $component->bind(function ($component) {
            return $component;
        });
    }

    /** @test */
    public function binding_is_possible_if_no_special_cases_are_defined()
    {
        $component = new class extends Component
        {
            protected $type = 'div';
            protected $bindable = [];
        };

        $component->bind(function ($component) {
            return $component;
        });

        $this->assertTrue(true);
    }

    /** @test */
    public function binding_is_not_possible_if_another_method_in_blueprint_do_not_exists()
    {
        $this->expectException(BindException::class);
        $this->expectExceptionMessage('thisMethodDosentExist do not exist in Blueprint.');

        $component = new class extends Component
        {
            protected $type = 'div';
            protected $bindable = [
                'prop' => 'thisMethodDosentExist'
            ];
        };

        $component->bind(function ($component) {
            return $component->prop();
        });
    }

    /** @test */
    public function binding_is_possible_if_another_method_in_component_is_defined()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('It works!');

        $component = new class extends Component
        {
            protected $type = 'div';
            protected $bindable = [
                'prop' => '$this::test'
            ];

            public function test()
            {
                throw new \Exception('It works!');
            }
        };

        $component->bind(function ($component) {
            return $component->prop();
        });
    }

    /** @test */
    public function binding_is_not_possible_if_another_method_in_component_do_not_exists()
    {
        $this->expectException(BindException::class);
        $this->expectExceptionMessage('dontExist do not exist in Component.');

        $component = new class extends Component
        {
            protected $type = 'div';
            protected $bindable = [
                'prop' => '$this::dontExist'
            ];
        };

        $component->bind(function ($component) {
            return $component->prop();
        });
    }

    /** @test */
    public function binding_is_not_possible_if_some_class_do_not_exists()
    {
        $this->expectException(BindException::class);
        $this->expectExceptionMessage('ClassDontExist do not exist.');

        $component = new class extends Component
        {
            protected $type = 'div';
            protected $bindable = [
                'prop' => 'ClassDontExist::dontExist'
            ];
        };

        $component->bind(function ($component) {
            return $component->prop();
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
}
