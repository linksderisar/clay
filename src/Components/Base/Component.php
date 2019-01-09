<?php

namespace Linksderisar\Clay\Components\Base;

class Component extends \Linksderisar\Clay\Components\Abstracts\Component
{
    /**
     * Component constructor.
     * @param mixed ...$options
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     * @throws \Linksderisar\Clay\Exceptions\ComponentException
     */
    public function __construct(... $options)
    {
        $this->setType($options[0]);
        parent::__construct(...$options);
    }
}
