<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;

class IfConditionBlueprint extends ConditionBlueprint
{
    /**
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        return [
            'if' => parent::toArray(),
        ];
    }
}
