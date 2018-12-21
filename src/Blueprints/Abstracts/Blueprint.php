<?php

namespace Linksderisar\Clay\Blueprints\Abstracts;

use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;

/**
 * Class Blueprint
 * Abstract Blueprint with base functionality
 *
 * @package Linksderisar\Clay\Blueprints\Abstracts
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
abstract class Blueprint implements \Linksderisar\Clay\Blueprints\Contracts\Blueprint
{
    /**
     * Unique identifier
     *
     * @var string
     */
    protected $id;

    /**
     * Type of the blueprint. For Example: div, span, my-component
     *
     * @var string
     */
    protected $type = '';

    /**
     * TextBlueprint constructor.
     */
    public function __construct()
    {
        $this->setId($this->generateId());
    }

    /**
     * Get new Blueprint instance
     *
     * @param array $attributes
     * @return $this
     */
    public static function create(...$attributes)
    {
        return new static();
    }

    /**
     * Set Identifier
     *
     * @param string $id
     * @return $this
     */
    protected function setId(string $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set Type
     *
     * @param string $type
     * @return $this
     */
    public function setType(string $type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get Identifier
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get Type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Generate an unique Id
     *
     * @return string
     */
    protected function generateId(): string
    {
        return str_random();
    }

    /**
     * Clone Blueprint with new Identifier
     *
     * @return $this
     */
    public function clone()
    {
        $clone = clone $this;
        return  $clone->setId($this->generateId());
    }

    /**
     * Convert Blueprint to Array
     *
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
     * Convert Blueprint to Json
     *
     * @return string
     * @throws RequiredBlueprintAttributeMissingException
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert Blueprint to Json
     *
     * @return string
     * @throws RequiredBlueprintAttributeMissingException
     */
    public function __toString(): string
    {
        return $this->toJson();
    }

    /**
     * Clone Blueprint with new Identifier
     */
    public function __clone()
    {
        $this->setId($this->generateId());
    }

}
