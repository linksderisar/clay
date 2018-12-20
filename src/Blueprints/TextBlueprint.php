<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Blueprints\Abstracts\Blueprint;

class TextBlueprint extends Blueprint
{

    /** @var string */
    protected $value = '';

    /** @var string  */
    protected $type = '$text';

    /**
     * @param string $value
     * @return $this
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param mixed ...$attributes [0] => value
     * @return $this
     */
    public static function create(...$attributes): self
    {
        return parent::create($attributes)->setValue($attributes[0]);
    }

    /**
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), ['value' => $this->getValue()]);
    }

}
