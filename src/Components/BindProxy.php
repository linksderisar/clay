<?php

namespace Linksderisar\Clay\Components;

use Linksderisar\Clay\Exceptions\BindException;
use Linksderisar\Clay\Exceptions\ComponentException;
use Linksderisar\Clay\Components\Abstracts\Component;

class BindProxy
{
    /**
     * @var Component
     */
    protected $component;

    const BIND_METHOD_PREFIX = 'setBind';

    public function __construct(Component $component)
    {
        $this->component = $component;

        if ($this->component->getBindable() === false) {
            throw new BindException('Binding is deactivated for this Class.');
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasSpecialBindMethod(string $name): bool
    {
        return array_key_exists($name, $this->component->getBindable());
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws BindException
     */
    protected function callOnBlueprint($name, array $arguments)
    {
        if (!method_exists($this->component->getBlueprint(), $name)) {
            throw new BindException($name . ' do not exist in Blueprint.');
        }

        $this->component->getBlueprint()->$name(...$arguments);
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws BindException
     */
    protected function callOnThis($name, array $arguments)
    {
        if (!method_exists($this->component, $name)) {
            throw new BindException($name . ' do not exist in Component.');
        }

        $this->component->$name(...$arguments);
        return $this;
    }

    /**
     * @param string $class
     * @param $name
     * @param array $arguments
     * @return $this
     * @throws BindException
     */
    protected function callOnOtherClass(string $class, $name, array $arguments)
    {
        if (!class_exists($class)) {
            throw new BindException($class . ' do not exist.');
        }

        if (!method_exists($class, $name)) {
            throw new BindException($name . ' can not be bound.');
        }

        (new $class())->$name(...$arguments);

        return $this;
    }

    /**
     * @param string $name
     * @param $arguments
     * @return BindProxy
     * @throws BindException
     */
    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this->component, $name)) {
            throw new BindException($name . ' can not be bound.');
        }

        if (!$this->hasSpecialBindMethod($name)) {
            return $this->callOnBlueprint(static::BIND_METHOD_PREFIX . ucfirst($name), $arguments);
        }

        $method = $this->component->getBindable()[$name];

        if (!str_contains($method, '::')) {
            return $this->callOnBlueprint($method, $arguments);
        }

        [$class, $method] = explode('::', $method);

        if ($class === '$this') {
            return $this->callOnThis($method, $arguments);
        }

        return $this->callOnOtherClass($class, $method, $arguments);
    }


}
