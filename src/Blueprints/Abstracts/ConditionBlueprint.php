<?php

namespace Linksderisar\Clay\Blueprints\Abstracts;

use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;

/**
 * Class ConditionBlueprint
 *
 * @package Linksderisar\Clay\Blueprints\Abstracts
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
abstract class ConditionBlueprint extends Blueprint
{

    protected $condition = '';

    /**
     * @param string $condition
     * @return $this
     */
    public function setCondition(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * Get instance of Condition Blueprint
     *
     * @param mixed ...$attributes
     * @return $this
     */
    public static function create(...$attributes): self
    {
        return parent::create(...$attributes);
    }

    /**
     * Convert to array
     *
     * @return array
     * @throws RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        if (empty($this->getType())) {
            throw new RequiredBlueprintAttributeMissingException('Type is required for Conditions.');
        }

        return [
            $this->getType() => $this->getCondition()
        ];
    }
}
