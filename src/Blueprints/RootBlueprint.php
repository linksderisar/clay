<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Contracts\Blueprint;
use Linksderisar\Clay\Components\Abstracts\Component;

class RootBlueprint implements Blueprint
{
    protected $store = [];

    protected $meta = [];

    protected $head = [];

    protected $componentTree = null;

    /**
     * @return array
     */
    public function getDataStore(): array
    {
        return $this->store;
    }

    /**
     * @param array $dataStore
     * @return $this
     */
    public function setDataStore(array $dataStore): self
    {
        $this->store = $dataStore;
        return $this;
    }

    /**
     * @param array $meta
     * @return $this
     */
    public function setMeta(array $meta): self
    {
        $this->meta = $meta;
        return $this;
    }

    /**
     * @param array $metas
     * @return $this
     */
    public function addMetas(array $metas): self
    {
        return $this->setMeta(array_merge($this->getMeta(), $metas));
    }

    /**
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     * @param array $head
     * @return $this
     */
    public function setHead(array $head): self
    {
        $this->head = $head;
        return $this;
    }

    /**
     * @param array $header
     * @return $this
     */
    public function addHeader(array $header): self
    {
        return $this->setHead(array_merge($this->getHead(), $header));
    }

    /**
     * @return array
     */
    public function getHead(): array
    {
        return $this->head;
    }

    /**
     * @param Component $componentTree
     * @return $this
     */
    public function setComponentTree(Component $componentTree): self
    {
        $this->componentTree = $componentTree;
        return $this;
    }

    /**
     * @return Component
     */
    public function getComponentTree(): ?Component
    {
        return $this->componentTree;
    }

    /**
     * Clone Blueprint with new Identifier
     *
     * @return $this
     */
    public function clone()
    {
        return clone $this;
    }

    /**
     * Create an instance of the Blueprint
     *
     * @param mixed ...$attributes [0] => Blueprint Type
     * @return $this
     */
    public static function create(...$attributes): self
    {
        $self = new static(...$attributes);

        if (!isset($attributes[0])) {
            return $self;
        }

        return $self->setComponentTree($attributes[0]);
    }

    /**
     * Convert Blueprint to Array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'store' => $this->getDataStore(),
            'componentTree' => optional($this->getComponentTree(), null)->toArray(),
            'meta' => $this->getMeta(),
            'head' => $this->getHead()
        ];
    }

    /**
     * Convert to Json
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert to Json
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
