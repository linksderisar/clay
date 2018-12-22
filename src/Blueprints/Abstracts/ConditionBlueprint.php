<?php

namespace Linksderisar\Clay\Blueprints\Abstracts;

use Linksderisar\Clay\Exceptions\BlueprintException;
use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;

/**
 * Class ConditionBlueprint
 *
 * @package Linksderisar\Clay\Blueprints\Abstracts
 * @author Tobias Hettler <tobias.hettler@linksderisar.com>
 */
abstract class ConditionBlueprint extends Blueprint
{
    /**
     * First condition argument
     *
     * @var array
     */
    protected $firstArgument = [];

    /**
     * Second condition argument
     *
     * @var array
     */
    protected $secondArgument = [];

    /**
     * Condition Operator
     *
     * @var array
     */
    protected $operator = [];

    /** @var array  */
    const ALLOWED_OPERATORS = ['==', '===', '>', '<', '>=', '<=', '!=', '!=='];

    /**
     * Get First Argument
     *
     * @return mixed
     */
    public function getFirstArgument(): array
    {
        return $this->firstArgument;
    }

    /**
     * Set first argument
     *
     * @param mixed $firstArgument
     * @return $this
     */
    public function setFirstArgument($firstArgument): self
    {
        $this->firstArgument = ['firstArgument' => $firstArgument];
        return $this;
    }

    /**
     * Set first bound Argument
     *
     * @param mixed $firstArgument
     * @return $this
     */
    public function setBindFirstArgument($firstArgument): self
    {
        $this->firstArgument = [':firstArgument' => $firstArgument];
        return $this;
    }

    /**
     * Get second argument
     *
     * @return mixed
     */
    public function getSecondArgument(): array
    {
        return $this->secondArgument;
    }

    /**
     * Set second argument
     *
     * @param mixed $secondArgument
     * @return $this
     */
    public function setSecondArgument($secondArgument): self
    {
        $this->secondArgument = ['secondArgument' => $secondArgument];
        return $this;
    }

    /**
     * Set second bound Argument
     *
     * @param mixed $secondArgument
     * @return $this
     */
    public function setBindSecondArgument($secondArgument): self
    {
        $this->secondArgument = [':secondArgument' => $secondArgument];
        return $this;
    }

    /**
     * Get Operator
     *
     * @return array
     */
    public function getOperator(): array
    {
        return $this->operator;
    }

    /**
     * Set Operator
     *
     * @param string $operator
     * @return $this
     */
    public function setOperator(string $operator): self
    {
        $this->operator = ['operator' => $operator];
        return $this;
    }

    /**
     * Set bound operator
     *
     * @param string $operator
     * @return $this
     */
    public function setBindOperator(string $operator): self
    {
        $this->operator = [':operator' => $operator];
        return $this;
    }

    /**
     * Get instance of Condition Blueprint
     *
     * @param mixed ...$attributes
     * @return $this
     */
    public static function create(...$attributes): self
    {
        return parent::create(...$attributes);
    }

    /**
     * Convert to array
     *
     * @return array
     * @throws RequiredBlueprintAttributeMissingException
     * @throws BlueprintException
     */
    public function toArray(): array
    {

        if (empty($this->getFirstArgument()) && $this->getSecondArgument() !== false) {
            throw new RequiredBlueprintAttributeMissingException('FirstArgument need to be set!');
        }

        if (empty($this->getSecondArgument()) && $this->getSecondArgument() !== false) {
            throw new RequiredBlueprintAttributeMissingException('SecondArgument need to be set!');
        }

        if (empty($this->getOperator())) {
            throw new RequiredBlueprintAttributeMissingException('Operator need to be set!');
        }

        if (!in_array(array_first($this->getOperator()), self::ALLOWED_OPERATORS)) {
            throw new BlueprintException(
                '"' . array_first($this->getOperator()) . '" Operator isn\'t allowed. Allowed Operator: [ ' . implode(', ', self::ALLOWED_OPERATORS) . ' ]'
            );
        }

        return array_merge(
            $this->getFirstArgument(),
            $this->getSecondArgument(),
            $this->getOperator()
        );
    }

}
