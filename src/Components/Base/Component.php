<?php

namespace linksderisar\Clay\Components;

use Linksderisar\Clay\Exceptions\ComponentException;

class Component extends \Linksderisar\Clay\Components\Abstracts\Component
{
    /**
     * Component constructor.
     *
     * @param mixed ...$options
     * @throws ComponentException
     * @throws \Linksderisar\Clay\Exceptions\BlueprintException
     */
    public function __construct(...$options)
    {
        if ($options[0] ?? false) {
            throw new ComponentException('The First argument must be the type.');
        }

        $this->type = $options[0];
        parent::__construct($options);
    }
}
