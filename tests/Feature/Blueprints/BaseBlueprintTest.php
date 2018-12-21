<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Tests\TestCase;
use Linksderisar\Clay\Blueprints\ComponentBlueprint;
use Linksderisar\Clay\Blueprints\LoopBlueprint;
use Linksderisar\Clay\Exceptions\BlueprintException;
use Linksderisar\Clay\Blueprints\IfConditionBlueprint;
use Linksderisar\Clay\Blueprints\ShowConditionBlueprint;
use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;

class BaseBlueprintTest extends TestCase
{

    /** @var ComponentBlueprint */
    protected $blueprint;

    /**
     * Setup Method
     */
    protected function setUp()
    {
        parent::setUp();
        $this->blueprint = ComponentBlueprint::create('test');
    }

    /** @test */
    public function empty_blueprint_to_json()
    {
        $blueprintJson = $this->blueprint->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint());
    }

    /** @test */
    public function type_in_blueprint_is_required()
    {
        $this->expectException(RequiredBlueprintAttributeMissingException::class);

        $blueprint = new ComponentBlueprint();
        $blueprint->toJson();
    }

    /** @test */
    public function ref_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setRef('$ref')
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        'ref' => '$ref'
                    ]
                ]
            ));
    }

    /** @test */
    public function key_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setKey('$key')
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        'key' => '$key'
                    ]
                ]
            ));
    }

    /** @test */
    public function refInFor_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setRefInFor(true)
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        'refInFor' => true
                    ]
                ]
            ));
    }

    /** @test */
    public function classes_can_be_set_in_blueprint()
    {
        $this->blueprint->setClasses(['css-class']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        'class' => ['css-class']
                    ]
                ]
            ));
    }

    /** @test */
    public function bind_classes_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setBindClasses('class.bind')
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        ':class' => 'class.bind'
                    ]
                ]
            ));
    }

    /** @test */
    public function styles_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setStyle(['color' => 'black'])
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        'style' => ['color' => 'black']
                    ]
                ]
            ));
    }

    /** @test */
    public function affect_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setAffect('affect.ref')
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'affect' => 'affect.ref'
                ]
            ));
    }

    /** @test */
    public function text_can_be_set_in_blueprint()
    {
        $blueprintJson = $this->blueprint
            ->setText('Dummy Text')
            ->toJson();

        $this->makeTestResponse($blueprintJson)
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "children" => [
                        'id' => $this->blueprint->getText()->getId(),
                        'type' => '$text',
                        'value' => 'Dummy Text'
                    ]
                ]
            ));
    }

    /** @test */
    public function props_can_be_set_in_blueprint()
    {
        $this->blueprint->setProps(['prop' => 'propValue']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "attributes" => [
                        'props' => [
                            'prop' => 'propValue',
                        ]
                    ]
                ]
            ));
        $this->blueprint->addProps(['addProp' => 'propValue']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "attributes" => [
                        'props' => [
                            'prop' => 'propValue',
                            'addProp' => 'propValue',
                        ]
                    ]
                ]
            ));
    }

    /** @test */
    public function bind_props_can_be_set_in_blueprint()
    {
        $this->blueprint->setBindProps(['prop' => 'prop.ref']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "attributes" => [
                        'props' => [
                            ':prop' => 'prop.ref',
                        ]
                    ]
                ]
            ));
        $this->blueprint->addBindProps(['addProp' => 'prop.ref']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "attributes" => [
                        'props' => [
                            ':prop' => 'prop.ref',
                            ':addProp' => 'prop.ref',
                        ]
                    ]
                ]
            ));
    }

    /** @test */
    public function attributes_can_be_set_in_blueprint()
    {
        $this->blueprint->setAttributes(['id' => 'ID']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "attributes" => [
                        'attrs' => [
                            'id' => 'ID',
                        ]
                    ]
                ]
            ));

        $this->blueprint->addAttributes(['type' => 'password']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "attributes" => [
                        'attrs' => [
                            'id' => 'ID',
                            'type' => 'password',
                        ]
                    ]
                ]
            ));
    }

    /** @test */
    public function scoped_slots_can_be_set_in_blueprint()
    {
        $slot = ComponentBlueprint::create('slot');
        $this->blueprint->setScopedSlots(function (\Closure $slotProps) use ($slot) {
            return [
                $slot,
            ];
        });

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "scopedSlots" => [
                        [
                            'id' => $slot->getId(),
                            'type' => 'slot'
                        ]
                    ]
                ]
            ));

        $slot_2 = ComponentBlueprint::create('slot_2');
        $this->blueprint->setScopedSlots(function (\Closure $slotProps) use ($slot_2, $slot) {
            return [
                $slot,
                $slot_2
            ];
        });

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "scopedSlots" => [
                        [
                            'id' => $slot->getId(),
                            'type' => 'slot'
                        ],
                        [
                            'id' => $slot_2->getId(),
                            'type' => 'slot_2'
                        ]
                    ]
                ]
            ));
    }

    /** @test */
    public function slotProps_helper_works()
    {
        $slot = ComponentBlueprint::create('slot');
        $this->blueprint->setScopedSlots(function (\Closure $slotProps) use ($slot) {
            return [
                $slot->addProps(['prop' => $slotProps('ref.prop')]),
            ];
        });

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "scopedSlots" => [
                        [
                            'id' => $slot->getId(),
                            'type' => 'slot',
                            'attributes' => [
                                'props' => [
                                    'prop' => '$_slot_props.' . $this->blueprint->getId() . '.ref.prop',
                                ]
                            ]
                        ]
                    ]
                ]
            ));

        $this->assertEquals(
            '$_slot_props.' . $this->blueprint->getId() . '.ref.for.prop',
            $this->blueprint->slotProp('ref.for.prop'));
    }

    /** @test */
    public function children_can_be_set_in_blueprint()
    {
        $this->blueprint->setChildren(
            $child = ComponentBlueprint::create('child'),
            $child_2 = ComponentBlueprint::create('child_2')
        );

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "children" => [
                        [
                            'id' => $child->getId(),
                            'type' => 'child',
                        ],
                        [
                            'id' => $child_2->getId(),
                            'type' => 'child_2',
                        ]
                    ]
                ]
            ));
    }

    /** @test */
    public function loops_can_be_set_in_blueprint()
    {
        $this->blueprint->setLoop(LoopBlueprint::create('loop.ref'));

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    "loop" => 'loop.ref'
                ]
            ));
    }

    /** @test */
    public function if_can_be_set_in_blueprint()
    {
        $this->blueprint->setIf(
            IfConditionBlueprint::create()
                ->setFirstArgument('firstArgument')
                ->setOperator('===')
                ->setSecondArgument('secondArgument')
        );

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertJson($this->makeAssertBlueprint(
                [
                    "if" => []
                ]
            ));
    }

    /** @test */
    public function show_can_be_set_in_blueprint()
    {
        $this->blueprint->setIf(
            ShowConditionBlueprint::create()
                ->setFirstArgument('firstArgument')
                ->setOperator('===')
                ->setSecondArgument('secondArgument')
        );

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertJson($this->makeAssertBlueprint(
                [
                    "show" => []
                ]
            ));
    }

    /** @test */
    public function events_can_be_set_in_blueprint()
    {
        $this->blueprint->setOn(['event' => 'method()']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        "on" => [
                            'event' => 'method()'
                        ]
                    ]
                ]
            ));

        $this->blueprint->addOn(['event_2' => 'method_2()']);

        $this->makeTestResponse($this->blueprint->toJson())
            ->assertExactJson($this->makeAssertBlueprint(
                [
                    'attributes' => [
                        "on" => [
                            'event' => 'method()',
                            'event_2' => 'method_2()'
                        ]
                    ]
                ]
            ));
    }

    /** @test */
    public function a_blueprint_can_not_have_children_and_text_at_once()
    {
        $this->expectException(BlueprintException::class);
        $this->expectExceptionMessage(class_basename(ComponentBlueprint::class) . ' can not have Children and Text at the same Time!');

        $this->blueprint->setChildren(ComponentBlueprint::create('child'))->setText('Text')->toJson();
    }

    /** @test */
    public function a_blueprint_can_be_cloned_with_magic_operator()
    {
        $this->blueprint->setText('Text');
        $clonedBlueprint = clone $this->blueprint;

        $this->assertEquals($this->blueprint->getType(), $clonedBlueprint->getType());
        $this->assertEquals($this->blueprint->getText(), $clonedBlueprint->getText());
        $this->assertNotEquals($this->blueprint->getId(), $clonedBlueprint->getId());
    }

    /** @test */
    public function a_blueprint_can_be_cloned_with_build_in_clone()
    {
        $clonedBlueprint = $this->blueprint->setText('Text')->clone();

        $this->assertEquals($this->blueprint->getType(), $clonedBlueprint->getType());
        $this->assertEquals($this->blueprint->getText(), $clonedBlueprint->getText());
        $this->assertNotEquals($this->blueprint->getId(), $clonedBlueprint->getId());
    }


    /**
     * @param array $blueprint
     * @return array
     */
    protected function makeAssertBlueprint(array $blueprint = []): array
    {
        return array_merge(['id' => $this->blueprint->getId(), 'type' => 'test'], $blueprint);
    }
}
