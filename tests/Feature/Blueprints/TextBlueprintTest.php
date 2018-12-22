<?php

namespace Linksderisar\Clay\Tests\Feature\Blueprints;

use Linksderisar\Clay\Blueprints\TextBlueprint;
use Linksderisar\Clay\Exceptions\BlueprintException;
use Linksderisar\Clay\Tests\TestCase;

class TextBlueprintTest extends TestCase
{
    /** @test */
    public function iterable_as_first_argument_in_create_blueprint_is_required()
    {
        $this->expectException(BlueprintException::class);
        $this->expectExceptionMessage('First Parameter of ' . class_basename(TextBlueprint::class) . 'must be the content of the Text.');
        TextBlueprint::create();
    }

    /** @test */
    public function text_can_be_converted_in_json()
    {
        $textBlueprint = TextBlueprint::create('Dummy Text');

        $this->makeTestResponse($textBlueprint->toJson())
            ->assertExactJson(
                [
                    'id' => $textBlueprint->getId(),
                    'type' => '$text',
                    'value' => 'Dummy Text'
                ]
            );
    }

    /** @test */
    public function bound_text_can_be_converted_in_json()
    {
        $textBlueprint = TextBlueprint::create('Dummy Text')->bind();

        $this->makeTestResponse($textBlueprint->toJson())
            ->assertExactJson(
                [
                    'id' => $textBlueprint->getId(),
                    'type' => '$text',
                    ':value' => 'Dummy Text'
                ]
            );
    }
}
