<?php

namespace Linksderisar\Clay\Blueprints\Abstracts;

use Linksderisar\Clay\Exceptions\BlueprintException;
use Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException;

abstract class ConditionBlueprint extends Blueprint
{
    /** @var array  */
    protected $firstArgument = [];

    /** @var array  */
    protected $secondArgument = [];

    /** @var array  */
    protected $operator = [];

    /** @var array  */
    const ALLOWED_OPERATORS = ['==', '===', '>', '<', '>=', '<=', '!=', '!=='];

    /**
     * @return mixed
     */
    public function getFirstArgument(): array
    {
        return $this->firstArgument;
    }

    /**
     * @param mixed $firstArgument
     * @return $this
     */
    public function setFirstArgument($firstArgument): self
    {
        $this->firstArgument = ['firstArgument' => $firstArgument];
        return $this;
    }

    /**
     * @param mixed $firstArgument
     * @return $this
     */
    public function setBindFirstArgument($firstArgument): self
    {
        $this->firstArgument = [':firstArgument' => $firstArgument];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecondArgument(): array
    {
        return $this->secondArgument;
    }

    /**
     * @param mixed $secondArgument
     * @return $this
     */
    public function setSecondArgument($secondArgument): self
    {
        $this->secondArgument = ['secondArgument' => $secondArgument];
        return $this;
    }

    /**
     * @param mixed $secondArgument
     * @return $this
     */
    public function setBindSecondArgument($secondArgument): self
    {
        $this->secondArgument = [':secondArgument' => $secondArgument];
        return $this;
    }

    /**
     * @return array
     */
    public function getOperator(): array
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     * @return $this
     */
    public function setOperator(string $operator): self
    {
        $this->operator = ['operator' => $operator];
        return $this;
    }

    /**
     * @param string $operator
     * @return $this
     */
    public function setBindOperator(string $operator): self
    {
        $this->operator = [':operator' => $operator];
        return $this;
    }

    /**
     * @param mixed ...$attributes
     * @return $this
     */
    public static function create(...$attributes): self
    {
        return parent::create(...$attributes);
    }

    /**
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
