<?php
namespace Linksderisar\Clay\Blueprints\Contracts;

/**
 * Interface Blueprint
 *
 * @package Linksderisar\Clay\Blueprints\Contracts
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
interface Blueprint
{

    /**
     * Get new Blueprint instance
     *
     * @param mixed ...$attributes
     * @return mixed
     */
    public static function create(...$attributes);

    /**
     * Clone Blueprint
     *
     * @return $this
     */
    public function clone();

    /**
     * Convert to Array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Convert to Json
     *
     * @return string
     */
    public function toJson(): string;

    /**
     * Convert to Json
     *
     * @return string
     */
    public function __toString(): string;
}
