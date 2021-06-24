<?php

namespace Ydm\Datatables\Support;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Str;

class Column
{
    public $class;
    public $column;
    public $text;

    public $blank = false;
    public $hidden = false;
    public $html = false;
    public $searchable = false;
    public $sortable = false;

    public $formatCallback;
    public $searchCallback;
    public $sortCallback;

    public static function blank(): Column
    {
        return new static(null, null);
    }

    public static function columnsFromBuilder($queryBuilder = null): ?array
    {
        if ($queryBuilder instanceof EloquentBuilder) {
            return $queryBuilder->getQuery()->columns;
        }

        if ($queryBuilder instanceof Builder) {
            return $queryBuilder->columns;
        }

        return null;
    }

    public static function hasMatch($column, $searchColumns): bool
    {
        return in_array($column, $searchColumns ?? [], true);
    }

    public static function hasRelation($column): bool
    {
        return Str::contains($column, '.');
    }

    public static function hasWildcardMatch($column, $searchColumns): bool
    {
        return count(array_filter($searchColumns ?? [], function ($searchColumn) use ($column) {
                $hasWildcard = Str::endsWith($searchColumn, '*');
                if (!$hasWildcard) {
                    return false;
                }

                if (!self::hasRelation($column)) {
                    return true;
                }

                $selectColumnPrefix = self::parseRelation($searchColumn);
                $columnPrefix = self::parseRelation($column);

                return $selectColumnPrefix === $columnPrefix;
            })) > 0;
    }

    public static function mapToSelected($column, $queryBuilder): ?string
    {
        $select = self::columnsFromBuilder($queryBuilder);
        if (is_null($select)) {
            return null;
        }

        // Search builder select for a match
        $hasMatch = self::hasMatch($column, $select);
        if ($hasMatch) {
            return $column;
        }

        // Search builder select for a wildcard match
        $hasWildcardMatch = self::hasWildcardMatch($column, $select);
        if ($hasWildcardMatch) {
            return $column;
        }

        // Split the relation and field
        $hasRelation = self::hasRelation($column);
        $relationName = self::parseRelation($column);
        $fieldName = self::parseField($column);
        if (!$hasRelation) {
            return null;
        }

        if ($queryBuilder instanceof EloquentBuilder) {
            $relation = $queryBuilder->getRelation($relationName);
            $possibleTable = $relation->getModel()->getTable();
        } elseif ($queryBuilder instanceof Builder) {
            $possibleTable = null;
        } else {
            $possibleTable = null;
        }

        if (!is_null($possibleTable)) {
            $possibleSelectColumn = $possibleTable . '.' . $fieldName;
            $possibleMatch = self::hasMatch($possibleSelectColumn, $select);
            if ($possibleMatch) {
                return $possibleSelectColumn;
            }

            $possibleWildcardMatch = self::hasWildcardMatch($possibleSelectColumn, $select);
            if ($possibleWildcardMatch) {
                return $possibleSelectColumn;
            }
        }

        return null;
    }

    public static function make(string $text = null, string $column = null): Column
    {
        return new static($text, $column);
    }

    public static function parseField($column): string
    {
        return Str::afterLast($column, '.');
    }

    public static function parseRelation($column): string
    {
        return Str::beforeLast($column, '.');
    }

    public function __construct(string $text = null, string $column = null)
    {
        $this->text = $text;

        if (!$column && $text) {
            $this->column = Str::snake($text);
        } else {
            $this->column = $column;
        }

        if (!$this->column && !$this->text) {
            $this->blank = true;
        }
    }

    public function addClass(string $class): Column
    {
        $this->class = $class;

        return $this;
    }

    public function class(): ?string
    {
        return $this->class;
    }

    public function column(): ?string
    {
        return $this->column;
    }

    public function format(callable $callable): Column
    {
        $this->formatCallback = $callable;

        return $this;
    }

    public function formatted($row, $column = null)
    {
        if ($column instanceof self) {
            $columnName = $column->column();
        } elseif (is_string($column)) {
            $columnName = $column;
        } else {
            $columnName = $this->column();
        }

        $value = data_get($row, $columnName);

        if ($this->formatCallback) {
            return app()->call($this->formatCallback, ['value' => $value, 'column' => $column, 'row' => $row]);
        }

        return $value;
    }

    public function getSearchCallback(): ?callable
    {
        return $this->searchCallback;
    }

    public function getSortCallback(): ?callable
    {
        return $this->sortCallback;
    }

    public function hasSearchCallback(): bool
    {
        return $this->searchCallback !== null;
    }

    public function hasSortCallback(): bool
    {
        return $this->sortCallback !== null;
    }

    public function hideIf(bool $condition): Column
    {
        $this->hidden = $condition;

        return $this;
    }

    public function html(): Column
    {
        $this->html = true;

        return $this;
    }

    public function isBlank(): bool
    {
        return $this->blank === true;
    }

    public function isSearchable(): bool
    {
        return $this->searchable === true;
    }

    public function isSortable(): bool
    {
        return $this->sortable === true;
    }

    public function isVisible(): bool
    {
        return $this->hidden !== true;
    }

    public function searchable(callable $callback = null): Column
    {
        $this->searchable = true;
        $this->searchCallback = $callback;

        return $this;
    }

    public function sortable($callback = null): Column
    {
        $this->sortable = true;
        $this->sortCallback = $callback;

        return $this;
    }

    public function text(): ?string
    {
        return $this->text;
    }
}
