<?php
namespace Linksderisar\Clay\Blueprints\Contracts;

interface Blueprint
{

    /**
     * @param mixed ...$attributes
     * @return mixed
     */
    public static function create(...$attributes);

    /**
     * @return $this
     */
    public function clone();

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @return string
     */
    public function toJson(): string;

    /**
     * @return string
     */
    public function __toString(): string;
}
