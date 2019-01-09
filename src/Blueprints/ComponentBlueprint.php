<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\Blueprint;
use Linksderisar\Clay\Blueprints\Abstracts\ConditionBlueprint;
use Linksderisar\Clay\Exceptions\BlueprintException;

/**
 * Class BaseBlueprint
 * This blueprint can be converted into an json which the ClayRenderer can be understand
 *
 * @package Linksderisar\Clay\Blueprints
 * @author Tobias Hettler <tobias.hettler@linksderisar.de>
 */
class ComponentBlueprint extends Blueprint
{
    /**
     * Constant for the SlotProp Key in the Json
     *
     * @var string
     */
    const SLOT_PROPS = '$_slot_props';

    /**
     * Constant for "this" in the Frontend
     *
     * @var string
     */
    const SELF = '$_self';

    /**
     * Constant for loop key in the Frontend
     *
     * @var string
     */
    const LOOP = 'loop';

    /**
     * Constant for the loop value in the Frontend
     *
     * @var string
     */
    const LOOP_VALUE = '$_loop_value';

    /**
     * Correspond to the Vue ref attribute
     * https://vuejs.org/v2/guide/components-edge-cases.html#Accessing-Child-Component-Instances-amp-Child-Elements
     *
     * @var string
     */
    protected $ref = '';

    /**
     * Correspond to the Vue key attribute
     * https://vuejs.org/v2/guide/list.html#key
     *
     * @var string
     */
    protected $key = '';

    /**
     * Vue:
     * If you are applying the same ref name to multiple
     * elements in the render function. This will make `$refs.myRef` become an array
     *
     * @var bool
     */
    protected $refInFor = false;

    /**
     * The ScopedSlots of the Blueprint
     * https://vuejs.org/v2/guide/components-slots.html#Scoped-Slots
     *
     * @var array<Blueprint>
     */
    protected $scopedSlots = [];

    /**
     * The HTML Attributes that the Tag/Component should have
     * https://developer.mozilla.org/de/docs/Web/HTML/Attributes
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Vue properties
     * https://vuejs.org/v2/guide/components-props.html#ad
     *
     * @var array
     */
    protected $props = [];

    /**
     * Events for Vue
     * https://vuejs.org/v2/guide/render-function.html#Event-amp-Key-Modifiers
     *
     * @var array
     */
    protected $on = [];

    /**
     * Child Components
     *
     * @var array
     */
    protected $children = [];

    /**
     * Css Classes you want to apply on the Tag/Component
     * Its possible to set a normal array ['class1', 'class2',...]
     * or to use a key value approach ['class1' => true, 'class2'=> false, ...]
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Inline Styles as key value pair
     * ['color' => '#ff0000', 'margin' => '10px 10px']
     *
     * @var array
     */
    protected $style = [];

    /**
     * Text inside the Tags.
     * <span>{{THE_TEXT}}</span>
     *
     * @var TextBlueprint
     */
    protected $text;

    /**
     * LoopBlueprint with the information witch data resource should be looped
     *
     * @var Blueprint
     */
    protected $loop;

    /**
     * It correspond to v-model in Vue
     * https://vuejs.org/v2/guide/components-custom-events.html#Customizing-Component-v-model
     *
     * @var string
     */
    protected $affect = '';

    /**
     * Bound attributes
     *
     * @var array
     */
    protected $bound = [];

    /**
     * Conditions
     *
     * @var ConditionBlueprint[]
     */
    protected $conditions = [];

    /**
     * @return array
     */
    protected function getBound(): array
    {
        return $this->bound;
    }

    /**
     * Set Bound
     *
     * @param array $bound
     * @return $this
     */
    protected function setBound(array $bound): self
    {
        $this->bound = $bound;
        return $this;
    }

    /**
     * Add bound
     *
     * @param string $bound
     * @return $this
     */
    protected function addBound(string $bound): self
    {
        $this->bound = array_merge($this->getBound(), [$bound]);
        return $this;
    }

    /**
     * Sets Ref
     *
     * @param string $ref
     * @return $this
     */
    public function setRef(string $ref): self
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * Sets Key
     *
     * @param string $key
     * @return $this
     */
    public function setKey(string $key): self
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Sets bound Key
     *
     * @param string $key
     * @return $this
     */
    public function setBindKey(string $key): self
    {
        $this->key = $key;
        return $this->addBound('key');
    }

    /**
     * Sets bound Ref
     *
     * @param string $ref
     * @return $this
     */
    public function setBindRef(string $ref): self
    {
        $this->ref = $ref;
        return $this->addBound('ref');
    }

    /**
     * Sets RefInFor
     *
     * @param bool $refInFor
     * @return $this
     */
    public function setRefInFor(bool $refInFor): self
    {
        $this->refInFor = $refInFor;
        return $this;
    }

    /**
     * Sets ScopedSlots
     * The Closure can be used to get access to the Slot props
     *
     * @param \Closure $scopedSlots
     * @return $this
     */
    public function setScopedSlots(\Closure $scopedSlots): self
    {
        $this->scopedSlots = array_wrap($scopedSlots(function (string $ref): string {
            return $this->slotProp($ref);
        }));
        return $this;
    }

    /**
     * Generates the prefix for slotProps
     *
     * @param string $ref
     * @return string
     */
    public function slotProp(string $ref): string
    {
        return static::SLOT_PROPS . '.' . $this->getId() . '.' . $ref;
    }

    /**
     * Set Attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;
        return $this;
    }

    /**
     * Set Attributes who will be bound to a data source in the frontend
     *
     * @param array $attributes
     * @return $this
     */
    public function setBindAttributes(array $attributes): self
    {
        $this->attributes = collect($attributes)->mapWithKeys(function ($value, $key) {
            return [':' . $key => $value];
        })->all();
        return $this;
    }

    /**
     * Add an Attribute who will be bound to a data source in the frontend without deleting the current props
     *
     * @param string $key
     * @param $value
     * @return $this
     */
    public function addBindAttribute(string $key, $value): self
    {
        return $this->addBindAttributes([$key => $value]);
    }

    /**
     * Add Attributes who will be bound to a data source in the frontend without deleting the current props
     *
     * @param array $attributes
     * @return $this
     */
    public function addBindAttributes(array $attributes): self
    {
        $this->attributes = array_merge(
            $this->getAttributes(),
            collect($attributes)->mapWithKeys(function ($value, $key) {
                return [':' . $key => $value];
            })->all()
        );
        return $this;
    }

    /**
     * Adds Attributes without deleting the current attributes
     *
     * @param array $attributes
     * @return $this
     */
    public function addAttributes(array $attributes): self
    {
        $this->attributes = array_merge($this->getAttributes(), $attributes);
        return $this;
    }

    /**
     * Set Props
     *
     * @param array $props
     * @return $this
     */
    public function setProps(array $props): self
    {
        $this->props = $props;
        return $this;
    }

    /**
     * Set props who will be bound to a data source in the frontend
     *
     * @param array $props
     * @return $this
     */
    public function setBindProps(array $props): self
    {
        $this->props = collect($props)->mapWithKeys(function ($value, $key) {
            return [':' . $key => $value];
        })->all();
        return $this;
    }

    /**
     * Set props without deleting the current props
     *
     * @param array $props
     * @return $this
     */
    public function addProps(array $props): self
    {
        $this->props = array_replace_recursive($this->getProps(), $props);
        return $this;
    }

    /**
     * Add props who will be bound to a data source in the frontend without deleting the current props
     *
     * @param array $props
     * @return $this
     */
    public function addBindProps(array $props): self
    {
        $this->props = array_merge(
            $this->getProps(),
            collect($props)->mapWithKeys(function ($value, $key) {
                return [':' . $key => $value];
            })->all()
        );
        return $this;
    }

    /**
     * Add a prop who will be bound to a data source in the frontend without deleting the current props
     *
     * @param string $key
     * @param $prop
     * @return $this
     */
    public function addBindProp(string $key, $prop): self
    {
        return $this->addBindProps([$key => $prop]);
    }

    /**
     * Set Events in the frontend
     *
     * @param array $on
     * @return $this
     */
    public function setOn(array $on): self
    {
        $this->on = $on;
        return $this;
    }

    /**
     * Adds an Event without deleting the current events
     *
     * @param array $on
     * @return $this
     */
    public function addOn(array $on): self
    {
        $this->on = array_replace_recursive($this->getOn(), $on);
        return $this;
    }

    /**
     * Set Children
     *
     * @param ComponentBlueprint ...$children
     * @return $this
     */
    public function setChildren(ComponentBlueprint ...$children): self
    {
        $this->children = $children;
        return $this;
    }

    /**
     * Set classes
     *
     * @param array $classes
     * @return $this
     */
    public function setClasses(array $classes): self
    {
        $this->classes = ['class' => $classes];
        return $this;
    }

    /**
     * Set bound Classes
     *
     * @param string $ref
     * @return $this
     */
    public function setBindClasses(string $ref): self
    {
        $this->classes = [':class' => $ref];
        return $this;
    }

    /**
     * Set Styles
     *
     * @param array $style
     * @return $this
     */
    public function setStyle(array $style): self
    {
        $this->style = $style;
        return $this;
    }

    /**
     * Set Loop
     *
     * @param Blueprint $loop
     * @return $this
     */
    public function setLoop(Blueprint $loop): self
    {
        $this->loop = $loop;
        return $this;
    }

    /**
     * Set If Condition
     *
     * @param ConditionBlueprint $condition
     * @return $this
     */
    public function setCondition(ConditionBlueprint $condition): self
    {
        $this->conditions = [$condition];
        return $this;
    }

    /**
     * Set If Condition
     *
     * @param ConditionBlueprint ...$condition
     * @return $this
     */
    public function addCondition(ConditionBlueprint ...$condition): self
    {
        $this->conditions = array_merge($this->getConditions(), $condition);
        return $this;
    }

    /**
     * Set affect/v-model
     *
     * @param string $affect
     * @return $this
     */
    public function setAffect(string $affect): self
    {
        $this->affect = $affect;
        return $this;
    }

    /**
     * Set Text between the Tags
     *
     * @param string $text
     * @return $this
     * @throws BlueprintException
     */
    public function setText(string $text): self
    {
        $this->text = TextBlueprint::create($text);
        return $this;
    }

    /**
     * Set text bound to a data source in the frontend
     *
     * @param string $text
     * @return $this
     * @throws BlueprintException
     */
    public function setBindText(string $text): self
    {
        $this->text = TextBlueprint::create($text)->bind();
        return $this;
    }

    /**
     * Get Ref
     *
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * Get Key
     *
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Get RefInFor
     *
     * @return bool
     */
    public function getRefInFor(): bool
    {
        return $this->refInFor;
    }

    /**
     * Get Scoped Slots
     *
     * @return array
     */
    public function getScopedSlots(): array
    {
        return $this->scopedSlots;
    }

    /**
     * Get Attributes
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Get Props
     *
     * @return array
     */
    public function getProps(): array
    {
        return $this->props;
    }

    /**
     * Get On Events
     *
     * @return array
     */
    public function getOn(): array
    {
        return $this->on;
    }

    /**
     * Get Children
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * Get Children
     *
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Get Styles
     *
     * @return array
     */
    public function getStyle(): array
    {
        return $this->style;
    }

    /**
     * Get Loop
     *
     * @return Blueprint
     */
    public function getLoop(): ?Blueprint
    {
        return $this->loop;
    }

    /**
     * Get Conditions
     *
     * @return ConditionBlueprint[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * Get affect
     *
     * @return string
     */
    public function getAffect(): string
    {
        return $this->affect;
    }

    /**
     * Get Text
     *
     * @return Blueprint
     */
    public function getText(): ?Blueprint
    {
        return $this->text;
    }

    /**
     * Check if key is bound
     *
     * @param string $key
     * @return bool
     */
    protected function isBound(string $key): bool
    {
        return in_array($key, $this->getBound());
    }

    /**
     * Create an instance of the Blueprint
     *
     * @param mixed ...$attributes [0] => Blueprint Type
     * @return $this
     * @throws BlueprintException
     */
    public static function create(...$attributes): self
    {
        if (!($attributes[0] ?? false)) {
            throw new BlueprintException('First Parameter of ' . class_basename(static::class) . 'must be the Type.');
        }

        return parent::create($attributes)->setType($attributes[0]);
    }

    /**
     * Convert Blueprint to Array
     *
     * @return array
     * @throws BlueprintException
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        if (!empty($this->getText()) && !empty($this->getChildren())) {
            throw new BlueprintException(class_basename($this) . ' can not have Children and Text at the same Time!');
        }

        if ($this->getKey()) {
            $array['attributes'][$this->isBound('key') ? ':key' : 'key'] = $this->getKey();
        }

        if ($this->getRef()) {
            $array['attributes'][$this->isBound('ref') ? ':ref' : 'ref'] = $this->getRef();
        }

        if ($this->getRefInFor()) {
            $array['attributes']['refInFor'] = $this->getRefInFor();
        }

        if ($this->getStyle()) {
            $array['attributes']['style'] = $this->getStyle();
        }

        if ($this->getProps()) {
            $array['attributes']['props'] = $this->getProps();
        }

        if ($this->getOn()) {
            $array['attributes']['on'] = $this->getOn();
        }

        if ($this->getClasses()) {
            $array['attributes'] = array_merge($array['attributes'] ?? [], $this->getClasses());
        }

        if ($this->getAttributes()) {
            $array['attributes']['attrs'] = array_merge($array['attributes'] ?? [], $this->getAttributes());
        }

        if ($this->getLoop()) {
            $array = array_merge($array, $this->getLoop()->toArray());
        }

        if ($this->getConditions()) {
            $array = array_merge(
                $array,
                collect($this->getConditions())->flatMap(
                    function (ConditionBlueprint $conditionBlueprint) {
                        return $conditionBlueprint->toArray();
                    }
                )
                    ->all()
            );
        }

        if (!empty($this->getText())) {
            $array['children'] = $this->getText()->toArray();
            return $array;
        }

        if ($this->getAffect()) {
            $array['affect'] = $this->getAffect();
        }

        if ($this->getScopedSlots()) {
            $array['scopedSlots'] = collect($this->getScopedSlots())->map->toArray();
        }

        if ($this->getChildren()) {
            $array['children'] = collect($this->getChildren())->map->toArray();
        }

        return $array;
    }
}
