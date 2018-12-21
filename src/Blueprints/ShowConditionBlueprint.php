<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;

/**
 * Class ShowConditionBlueprint
 *
 * @package Linksderisar\Clay\Blueprints
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
class ShowConditionBlueprint extends ConditionBlueprint
{
    /**
     * Convert to Array
     *
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        return [
            'show' => parent::toArray(),
        ];
    }
}
