<?php

namespace Linksderisar\Clay\Support;

use Linksderisar\Clay\Blueprints\ComponentBlueprint;
use Linksderisar\Clay\Blueprints\IfConditionBlueprint;
use Linksderisar\Clay\Blueprints\ShowConditionBlueprint;
use Linksderisar\Clay\Exceptions\ComponentException;

class Condition
{

    /**
     * @var string
     */
    protected $type;

    /** @var array */
    protected $types = [
        'if' => [
            'setter' => 'setIf',
            'class' => IfConditionBlueprint::class
        ],
        'show' => [
            'setter' => 'setShow',
            'class' => ShowConditionBlueprint::class
        ]
    ];

    /**
     * @var ComponentBlueprint
     */
    protected $blueprint;

    protected $arguments = [];

    protected $operators = [];

    /**
     * Condition constructor.
     * @param string $type
     * @param ComponentBlueprint $blueprint
     * @throws ComponentException
     */
    public function __construct(string $type, ComponentBlueprint $blueprint)
    {
        if (!array_key_exists($type, $this->types)) {
            throw new ComponentException($type . ' do not exist in' . static::class);
        }

        $this->type = $type;
        $this->blueprint = $blueprint;
    }

    public function __invoke($argument): self
    {
        return $this->argument($argument);
    }

    /**
     * @return ComponentBlueprint
     * @throws ComponentException
     */
    public function transformBlueprint()
    {
        $setter = $this->types[$this->type]['setter'];
        $class = $this->types[$this->type]['class'];

        if (count($this->arguments) > 2) {
            throw new ComponentException('Currently only two arguments for a condition allowed.');
        }

        if (count($this->operators) > 1) {
            throw new ComponentException('Currently only one operator is allowed for a condition.');
        }

        $this->blueprint->$setter(
            (new $class())
                ->setFirstArgument($this->arguments[0])
                ->setOperator($this->operators[0])
                ->setSecondArgument($this->arguments[1])
        );

        return $this->blueprint;
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function equal($argument): self
    {
        return $this->operator('==', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function exact($argument): self
    {
        return $this->operator('===', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function greater($argument): self
    {
        return $this->operator('>', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function greaterEqual($argument): self
    {
        return $this->operator('>=', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function less($argument): self
    {
        return $this->operator('<', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function lessEqual($argument): self
    {
        return $this->operator('<=', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function notEqual($argument): self
    {
        return $this->operator('!=', $argument);
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function notExact($argument): self
    {
        return $this->operator('!==', $argument);
    }

    /**
     * @param string $operator
     * @param $argument
     * @return Condition
     */
    public function operator(string $operator, $argument): self
    {
        $this->argument($argument);
        $this->addOperator($operator);
        return $this;
    }

    /**
     * @param $argument
     * @return Condition
     */
    public function argument($argument): self
    {
        $this->arguments[] = $argument;
        return $this;
    }

    /**
     * @param array $operator
     * @return $this
     */
    public function addOperator($operator): self
    {
        $this->operators[] = $operator;
        return $this;
    }
}
