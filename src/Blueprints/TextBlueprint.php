<?php

namespace Linksderisar\Clay\Blueprints;

use Linksderisar\Clay\Exceptions\BlueprintException;
use Linksderisar\Clay\Blueprints\Abstracts\Blueprint;

/**
 * Class TextBlueprint
 * This Blueprint is used to generate the json for an simple Text output in the Frontend
 *
 * @author Tobias Hettler <tobias.hettler@linksderisar.de>
 * @package Linksderisar\Clay\Blueprints
 */
class TextBlueprint extends Blueprint
{

    /**
     * The Content of the text
     *
     * @var string
     */
    protected $content = '';

    /**
     * Blueprint Type: In this case it must bei '$text'
     *
     * @var string
     */
    protected $type = '$text';

    /**
     * Set Content
     *
     * @param string $value
     * @return $this
     */
    public function setContent(string $value): self
    {
        $this->content = $value;
        return $this;
    }

    /**
     * Get Content
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Create TextBlueprint. The first parameter must be the content of the text
     *
     * @param mixed ...$attributes [0] => content
     * @return $this
     * @throws BlueprintException
     */
    public static function create(...$attributes): self
    {
        if (!($attributes[0] ?? false)) {
            throw new BlueprintException('First Parameter of ' . class_basename(static::class) . 'must be the content of the Text.');
        }

        return parent::create($attributes)->setContent($attributes[0]);
    }

    /**
     * Convert blueprint to Array
     *
     * @return array
     * @throws \Linksderisar\Clay\Exceptions\RequiredBlueprintAttributeMissingException
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), ['value' => $this->getContent()]);
    }

}
