<?php

namespace Linksderisar\Clay\Components\Abstracts;

use Linksderisar\Clay\Blueprints\RootBlueprint;

abstract class Root
{
    /** @var RootBlueprint */
    protected $blueprint;

    /** @var string */
    protected $version = '1.0.0';

    public function __construct(Component $clayComponent)
    {
        $this->initialBlueprint($clayComponent);
    }

    /**
     * @param Component $clayComponent
     * @return $this
     */
    public static function create(Component $clayComponent)
    {
        return new static($clayComponent);
    }

    /**
     * @param Component $clayComponent
     */
    protected function initialBlueprint(Component $clayComponent)
    {
        $this->setBlueprint(RootBlueprint::create($clayComponent));
        $this->meta('version', $this->getVersion());
    }

    /**
     * @return RootBlueprint
     */
    public function getBlueprint(): RootBlueprint
    {
        return $this->blueprint;
    }

    /**
     * @param RootBlueprint $blueprint
     * @return $this
     */
    public function setBlueprint(RootBlueprint $blueprint): self
    {
        $this->blueprint = $blueprint;
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function store(array $data): self
    {
        $this->blueprint->setDataStore($data);
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function meta(string $key, $value): self
    {
        $this->blueprint->addMetas([$key => $value]);
        return $this;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function head(string $key, $value): self
    {
        $this->blueprint->addHeader([$key => $value]);
        return $this;
    }

    /**
     * @param array $metas
     * @return $this
     */
    public function metas(array $metas): self
    {
        $this->blueprint->addMetas($metas);
        return $this;
    }

    /**
     * @param array $header
     * @return $this
     */
    public function header(array $header): self
    {
        $this->blueprint->addHeader($header);
        return $this;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->blueprint->toArray();
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
