<?php

namespace Linksderisar\Clay\Blueprints\Abstracts;


use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;

abstract class Blueprint implements \Linksderisar\Clay\Blueprints\Contracts\Blueprint
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $type = '';

    /**
     * TextBlueprint constructor.
     */
    public function __construct()
    {
        $this->setId($this->generateId());
    }

    /**
     * @param array $attributes
     * @return $this
     */
    public static function create(...$attributes)
    {
        return new static();
    }

    /**
     * @param string $id
     * @return $this
     */
    protected function setId(string $id)
    {
        $this->id = $id;
        return $this;
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
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    protected function generateId(): string
    {
        return str_random();
    }

    /**
     * @return $this
     */
    public function clone()
    {
        $clone = clone $this;
        return  $clone->setId($this->generateId());
    }

    /**
     * @return array
     * @throws RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        if (empty($this->getId())) {
            throw new RequiredBlueprintAttributeMissingException('"Id" need to be set!');
        }

        if (empty($this->getType())) {
            throw new RequiredBlueprintAttributeMissingException('"Type" need to be set!');
        }

        return [
            'id' => $this->getId(),
            'type' => $this->getType()
        ];
    }

    /**
     * @return string
     * @throws RequiredBlueprintAttributeMissingException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @return string
     * @throws RequiredBlueprintAttributeMissingException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     *
     */
    public function __clone()
    {
        $this->setId($this->generateId());
    }

}
