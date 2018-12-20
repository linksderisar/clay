<?php

namespace Linksderisar\Clay\Blueprints;


use Linksderisar\Clay\Blueprints\Abstracts\Blueprint;

class LoopBlueprint extends Blueprint
{

    /** @var string  */
    protected $iterable = '';

    /**
     * @param string $iterable
     * @return $this
     */
    public function setIterable(string $iterable): self
    {
        $this->iterable = $iterable;
        return $this;
    }

    /**
     * @return string
     */
    public function getIterable(): string
    {
        return $this->iterable;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return empty($this->getIterable()) ? [] : ['loop' => $this->getIterable()];
    }

    /**
     * @param array $attributes [0] => iterable
     * @return $this
     */
    public static function create(...$attributes): self
    {
        return (new static())->setIterable($attributes[0]);
    }

}
