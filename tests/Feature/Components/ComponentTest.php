<?php

namespace Linksderisar\Clay\Tests\Feature\Components;

use Linksderisar\Clay\Blueprints\ComponentBlueprint;
use Linksderisar\Clay\Components\Abstracts\Component;
use Linksderisar\Clay\Exceptions\ComponentException;
use Linksderisar\Clay\Tests\TestCase;

class ComponentTest extends TestCase
{

    /** @var Component */
    protected $component;

    protected function setUp()
    {
        parent::setUp();
        $this->component = $this->makeComponent();
    }

    /** @test */
    public function component_type_must_not_be_empty()
    {
        $this->expectException(ComponentException::class);
        $this->expectExceptionMessage('Type in');
        $this->expectExceptionMessage('must not be empty');

        new class extends Component
        {
            protected $type = '';
        };
    }

    /** @test */
    public function initial_blueprint_is_been_set()
    {
        $this->assertInstanceOf(ComponentBlueprint::class, $this->component->getBlueprint());
    }

    /** @test */
    public function a_component_can_be_cloned_with_build_in_clone()
    {
        $clonedComponent = $this->component->clone();

        $this->assertEquals($this->component->getType(), $clonedComponent->getType());
        $this->assertNotEquals($this->component->getBlueprint(), $clonedComponent->getBlueprint());
    }

    /** @test */
    public function a_component_can_be_cloned_with_magic_operator()
    {
        $clonedComponent = clone $this->component;

        $this->assertEquals($this->component->getType(), $clonedComponent->getType());
        $this->assertNotEquals($this->component->getBlueprint(), $clonedComponent->getBlueprint());
    }

    /** @test */
    public function prop_can_be_set()
    {
        $this->component->prop('prop', 'value');
        $this->assertEquals(['prop' => 'value'], $this->component->getBlueprint()->getProps());
    }

    /** @test */
    public function props_can_be_set()
    {
        $this->component->props(['prop' => 'value', 'prop_2' => 'value_2']);
        $this->assertEquals(['prop' => 'value', 'prop_2' => 'value_2'], $this->component->getBlueprint()->getProps());
    }

    /** @test */
    public function attribute_can_be_set()
    {
        $this->component->attribute('attribute', 'value');
        $this->assertEquals(['attribute' => 'value'], $this->component->getBlueprint()->getAttributes());
    }

    /** @test */
    public function attributes_can_be_set()
    {
        $this->component->attributes(['attribute' => 'value', 'attribute_2' => 'value_2']);
        $this->assertEquals(['attribute' => 'value', 'attribute_2' => 'value_2'], $this->component->getBlueprint()->getAttributes());
    }

    /** @test */
    public function children_can_be_set()
    {
        $child = $this->makeComponent();

        $this->component->children($child);
        $this->assertEquals([$child], $this->component->getChildren());
        $this->assertEquals([$child->getBlueprint()], $this->component->getBlueprint()->getChildren());
    }

    /** @test */
    public function scopedSlots_can_be_set()
    {
        $slotChild = $this->makeComponent()->setType('slot');
        $slotChild2 = $this->makeComponent()->setType('slot2');

        $this->component->scopedSlots(function () use ($slotChild2, $slotChild) {
            return [$slotChild, $slotChild2];
        });

        $this->assertEquals(
            [$slotChild, $slotChild2],
            $this->component->getScopedSlots()
        );
        $this->assertEquals(
            [$slotChild->getBlueprint(), $slotChild2->getBlueprint()],
            $this->component->getBlueprint()->getScopedSlots()
        );
    }

    /** @test */
    public function scopedSlots_helper_works()
    {
        $slotChild = $this->makeComponent()->setType('slot');

        $this->component->scopedSlots(function ($slotProp) use ($slotChild) {
            return [$slotChild->prop('test', $slotProp('test'))];
        });

        $this->assertEquals([
            'test' => '$_slot_props.' . $this->component->getId() . '.test'
        ],
            $this->component->getScopedSlots()[0]->getBlueprint()->getProps()
        );

        $this->assertEquals([
            'test' => '$_slot_props.' . $this->component->getId() . '.test'
        ],
            $this->component->getBlueprint()->getScopedSlots()[0]->getProps()
        );
    }

    /** @test */
    public function classes_can_be_set()
    {
        $this->component->classes(['css-class']);
        $this->assertEquals(['class' => ['css-class']], $this->component->getBlueprint()->getClasses());

        $this->component->classes('css-class');
        $this->assertEquals(['class' => ['css-class']], $this->component->getBlueprint()->getClasses());

        $this->component->classes(['css-class' => true]);
        $this->assertEquals(['class' => ['css-class' => true]], $this->component->getBlueprint()->getClasses());
    }

    /** @test */
    public function key_can_be_set()
    {
        $this->component->key('key');
        $this->assertEquals('key', $this->component->getBlueprint()->getKey());
    }

    /** @test */
    public function ref_can_be_set()
    {
        $this->component->ref('ref');
        $this->assertEquals('ref', $this->component->getBlueprint()->getRef());
    }

    /** @test */
    public function refInFor_can_be_set()
    {
        $this->component->refInFor();
        $this->assertEquals(true, $this->component->getBlueprint()->getRefInFor());
    }

    /** @test */
    public function affect_can_be_set()
    {
        $this->component->affect('ref.to.affect');
        $this->assertEquals('ref.to.affect', $this->component->getBlueprint()->getAffect());
    }

    /** @test */
    public function events_can_be_set()
    {
        $this->component->on('event', 'method()');
        $this->assertEquals(['event' => 'method()'], $this->component->getBlueprint()->getOn());
    }

    /** @test */
    public function text_can_be_set()
    {
        $this->component->text('text');
        $this->assertEquals('text', $this->component->getBlueprint()->getText()->getContent());
    }

    /** @test */
    public function loop_can_be_set()
    {
        $this->component->loop('ref.to.data.source');
        $this->assertEquals('ref.to.data.source', $this->component->getBlueprint()->getLoop()->getIterable());
    }

    /** @test */
    public function all_attributes_can_be_binded()
    {
        $this->component->bind(function ($component) {
            return $component->props(['key' => 'value'])
                ->props(['sec' => 'value'])
                ->text('Laaa');
        })->prop('unbind', 'lala');
$this->fail();
        $this->assertArrayHasKey(':key', $this->component->toArray()['attributes']['props']);
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
