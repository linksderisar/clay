<?php

namespace Linksderisar\Clay\Components\Contracts;

interface Component
{

    /**
     * @return $this
     */
    public static function make();

    /**
     * Clone Component
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
