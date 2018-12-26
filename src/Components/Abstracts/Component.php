<?php

namespace Linksderisar\Clay\Components\Abstracts;

use Linksderisar\Clay\Blueprints\ComponentBlueprint;
use Linksderisar\Clay\Blueprints\LoopBlueprint;
use Linksderisar\Clay\Components\BindProxy;
use Linksderisar\Clay\Exceptions\ComponentException;

abstract class Component implements \Linksderisar\Clay\Components\Contracts\Component
{
    /** @var string */
    protected $type = '';

    /** @var ComponentBlueprint */
    protected $blueprint;

    /** @var array <Component> */
    protected $children;

    /** @var array <Component> */
    protected $scopedSlots;

    protected $bindable = [
        'prop' => 'addBindProp',
        'props' => 'addBindProps',
        'attribute' => 'addBindAttribute',
        'attributes' => 'addBindAttributes',
    ];

    /**
     * ClayBlueprint constructor.
     * @param array $options
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     * @throws ComponentException
     */
    public function __construct(...$options)
    {
        if (empty($this->getType())) {
            throw new ComponentException('Type in ' . class_basename($this) . ' must not be empty');
        }

        $this->initialBlueprint();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->getBlueprint()->getId();
    }

    /**
     * @return ComponentBlueprint
     */
    public function getBlueprint(): ComponentBlueprint
    {
        return $this->blueprint;
    }

    /**
     * @param ComponentBlueprint $blueprint
     * @return $this
     */
    public function setBlueprint(ComponentBlueprint $blueprint)
    {
        $this->blueprint = $blueprint;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }


    /**
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * @param array $children
     * @return $this
     */
    public function setChildren(array $children): self
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return array
     */
    public function getScopedSlots(): array
    {
        return $this->scopedSlots;
    }

    /**
     * @param array $scopedSlots
     * @return $this
     */
    public function setScopedSlots(array $scopedSlots): self
    {
        $this->scopedSlots = $scopedSlots;
        return $this;
    }

    /**
     * @return void
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     */
    protected function initialBlueprint(): void
    {
        $this->setBlueprint(ComponentBlueprint::create($this->getType()));
    }

    /**
     * Add a single Prop
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function prop(string $key, $value): self
    {
        $this->getBlueprint()->addProps([$key => $value]);
        return $this;
    }

    /**
     * Add many props as array [$key => $value]
     *
     * @param array $props
     * @return $this
     */
    public function props(array $props): self
    {
        $this->getBlueprint()->addProps($props);
        return $this;
    }

    /**
     * Add a single Attribute
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function attribute(string $key, $value): self
    {
        $this->getBlueprint()->addAttributes([$key => $value]);
        return $this;
    }

    /**
     * Add many attributes as array [$key => $value]
     *
     * @param array $props
     * @return $this
     */
    public function attributes(array $props): self
    {
        $this->getBlueprint()->addAttributes($props);
        return $this;
    }

    /**
     * @param Component ...$children
     * @return $this
     */
    public function children(Component ...$children): self
    {
        $this->setChildren($children);
        $this->blueprint->setChildren(...collect($this->children)->map->getBlueprint()->all());
        return $this;
    }

    /**
     * @param \Closure $scopes
     * @return $this
     */
    public function scopedSlots(\Closure $scopes): self
    {
        $this->setScopedSlots($scopes([$this->getBlueprint(), 'slotProp']));

        $this->blueprint->setScopedSlots(function ($scope) use ($scopes) {
            return collect($scopes($scope))->map->getBlueprint()->all();
        });

        return $this;
    }

    /**
     * @param array|string $classes
     * @return $this
     */
    public function classes($classes): self
    {
        $this->getBlueprint()->setClasses(array_wrap($classes));
        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function key(string $key): self
    {
        $this->blueprint->setKey($key);
        return $this;
    }

    /**
     * @param string $ref
     * @return $this
     */
    public function ref(string $ref): self
    {
        $this->blueprint->setRef($ref);
        return $this;
    }

    /**
     * @param bool $refInFor
     * @return $this
     */
    public function refInFor(bool $refInFor = true): self
    {
        $this->blueprint->setRefInFor($refInFor);
        return $this;
    }

    /**
     * @param string $affect
     * @return $this
     */
    public function affect(string $affect): self
    {
        $this->blueprint->setAffect($affect);
        return $this;
    }

    /**
     * @param string $event
     * @param string $method
     * @return $this
     */
    public function on(string $event, string $method): self
    {
        $this->blueprint->addOn([$event => $method]);
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     */
    public function text(string $text): self
    {
        $this->blueprint->setText($text);
        return $this;
    }

    /**
     * @param string $iterable
     * @return $this
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     */
    public function loop(string $iterable): self
    {
        $this->blueprint->setLoop(LoopBlueprint::create($iterable));
        return $this;
    }

    /**
     * @param callable $bind
     * @return Component
     * @throws \Linksderisar\Clay\Exceptions\BindException
     */
    public function bind(callable $bind): self
    {
        $bind(new BindProxy($this));
        return $this;
    }

    /**
     * @return array|bool
     */
    public function getBindable()
    {
        return $this->bindable;
    }

    /**
     * Clone Component
     *
     * @return $this
     */
    public function clone()
    {
        return clone $this;
    }

    /**
     *
     */
    public function __clone()
    {
        $this->setBlueprint(clone $this->blueprint);
    }

    /**
     * Convert to Array
     *
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     */
    public function toArray(): array
    {
        return $this->getBlueprint()->toArray();
    }

    /**
     * Convert to Json
     *
     * @return string
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function toJson(): string
    {
        return $this->getBlueprint()->toJson();
    }

    /**
     * Convert to Json
     *
     * @return string
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
