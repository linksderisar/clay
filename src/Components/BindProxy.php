<?php

namespace Linksderisar\Clay\Components;


use Linksderisar\Clay\Components\Abstracts\Component;
use Linksderisar\Clay\Exceptions\ComponentException;

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
            throw new ComponentException('Binding is deactivated for this Class.');
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
     * @throws ComponentException
     */
    protected function callOnBlueprint($name, array $arguments)
    {
        if (!method_exists($this->component->getBlueprint(), $name)) {
            throw new ComponentException($name . ' can not be binded.');
        }

        $this->component->getBlueprint()->$name(...$arguments);
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return $this
     * @throws ComponentException
     */
    protected function callOnThis($name, array $arguments)
    {
        if (!method_exists($this->component, $name)) {
            throw new ComponentException($name . ' can not be binded.');
        }

        $this->component->$name(...$arguments);
        return $this;
    }

    /**
     * @param string $class
     * @param $name
     * @param array $arguments
     * @return $this
     * @throws ComponentException
     */
    protected function callOnOtherClass(string $class, $name, array $arguments)
    {
        if (!class_exists($class)) {
            throw new ComponentException($class . ' Do not exist.');
        }

        if (!method_exists($class, $name)) {
            throw new ComponentException($name . ' can not be binded.');
        }

        (new $class())->$name(...$arguments);

        return $this;
    }

    /**
     * @param string $name
     * @param $arguments
     * @return BindProxy
     * @throws ComponentException
     */
    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this->component, $name)) {
            throw new ComponentException($name . ' can not be binded.');
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
