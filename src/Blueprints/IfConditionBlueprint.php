<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;

/**
 * Class IfConditionBlueprint
 *
 * @package Linksderisar\Clay\Blueprints
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
class IfConditionBlueprint extends ConditionBlueprint
{
    /**
     * Convert to array
     *
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     */
    public function toArray(): array
    {
        return [
            'if' => parent::toArray(),
        ];
    }
}
