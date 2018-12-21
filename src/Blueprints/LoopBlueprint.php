<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Exceptions\BlueprintException;
use Linksderisar\Clay\Blueprints\Abstracts\Blueprint;

/**
 * Class LoopBlueprint
 *
 * @package Linksderisar\Clay\Blueprints
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
class LoopBlueprint extends Blueprint
{

    /**
     * Ref of data that should be looped in the Frontend
     *
     * @var string
     */
    protected $iterable = '';

    /**
     * Set Iterable
     *
     * @param string $iterable
     * @return $this
     */
    public function setIterable(string $iterable): self
    {
        $this->iterable = $iterable;
        return $this;
    }

    /**
     * Get Iterable
     *
     * @return string
     */
    public function getIterable(): string
    {
        return $this->iterable;
    }

    /**
     * Convert Blueprint to Array
     *
     * @return array
     */
    public function toArray(): array
    {
        return empty($this->getIterable()) ? [] : ['loop' => $this->getIterable()];
    }

    /**
     * Get new LoopBlueprint instance. First Parameter must be the Iterable
     *
     * @param array $attributes [0] => iterable
     * @return $this
     * @throws BlueprintException
     */
    public static function create(...$attributes): self
    {
        if (!$attributes[0] ?? false) {
            throw new BlueprintException('First Parameter of ' . class_basename(static::class) . 'must be the iterable.');
        }

        return (new static())->setIterable($attributes[0]);
    }

}
