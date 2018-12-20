<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;

class ShowConditionBlueprint extends ConditionBlueprint
{
    /**
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        return [
            'show' => parent::toArray(),
        ];
    }
}
