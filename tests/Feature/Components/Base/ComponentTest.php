<?php

namespace Linksderisar\Clay\Tests\Feature\Components\Base;

use Linksderisar\Clay\Components\Base\Component;
use Linksderisar\Clay\Tests\TestCase;

class ComponentTest extends TestCase
{
    /** @test */
    public function base_component_can_be_instiated()
    {
        $component = Component::make('div');
        $this->assertEquals('div', $component->getType());
    }
}
