<?php

namespace Ydm\Datatables\Support;

class Filter
{
    public const TYPE_SELECT = 'select';

    public $name;
    public $options = [];
    public $type;

    public static function make(string $name): Filter
    {
        return new static($name);
    }

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function isSelect(): bool
    {
        return $this->type === self::TYPE_SELECT;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function options(): array
    {
        return $this->options;
    }

    public function select(array $options = []): Filter
    {
        $this->type = self::TYPE_SELECT;
        $this->options = $options;

        return $this;
    }
}
