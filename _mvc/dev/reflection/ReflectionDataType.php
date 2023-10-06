<?php

/**
*   ReflectionDataType
*
*   @version
*/

declare(strict_types=1);

namespace Concerto\reflection;

use Countable;
use Generator;
use IteratorAggregate;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;
use Concerto\reflection\DataTypeException;

class ReflectionDataType implements
    IteratorAggregate,
    Countable
{
    /**
    *   @var ReflectionType
    */
    protected ?ReflectionType $reflectionType;

    /**
    *   @var string[]
    */
    protected array $dataTypes = [];

    /**
    *   __construct
    *
    *   @param ?ReflectionType $reflectionType
    */
    public function __construct(
        ?ReflectionType $reflectionType,
    ) {
        $this->reflectionType = $reflectionType;
        $this->extractFrom($this->reflectionType);
    }

    /**
    *   create
    *
    *   @param ?ReflectionType $reflectionType
    *   @return static
    */
    public static function create(
        ?ReflectionType $reflectionType,
    ): static {
        return new static($reflectionType);
    }

    /**
    *   extractFrom
    *
    *   @param ?ReflectionType $reflectionType
    *   @return void
    */
    protected function extractFrom(
        ?ReflectionType $reflectionType,
    ): void {
        if (is_null($reflectionType)) {
            return;
        }

        if ($reflectionType instanceof ReflectionNamedType) {
            $this->dataTypes[] = $reflectionType->getName();
            return;
        }

        foreach ($reflectionType->getTypes() as $dataType) {
            $this->extractFrom($dataType);
        }
    }

    /**
    *   {inherit}
    *
    *   @return Generator
    */
    public function getIterator(): Generator
    {
        foreach ($this->dataTypes as $type) {
            yield $type;
        }
    }

    /**
    *   {inherit}
    */
    public function count(): int
    {
        return count($this->dataTypes);
    }

    /**
    *   dataTypes
    *
    *   @return string[]
    */
    public function dataTypes(): array
    {
        return $this->dataTypes;
    }

    /**
    *   definedType
    *
    *   @return bool
    */
    public function definedType(): bool
    {
        return !is_null($this->reflectionType);
    }

    /**
    *   isNamedType
    *
    *   @return bool
    */
    public function isNamedType(): bool
    {
        return $this->reflectionType instanceof ReflectionNamedType;
    }

    /**
    *   isUnionType
    *
    *   @return bool
    */
    public function isUnionType(): bool
    {
        return $this->reflectionType instanceof ReflectionUnionType;
    }

    /**
    *   isIntersectionType
    *
    *   @return bool
    */
    public function isIntersectionType(): bool
    {
        return $this->reflectionType instanceof ReflectionIntersectionType;
    }

    /**
    *   allowsNull
    *
    *   @return bool
    */
    public function allowsNull(): bool
    {
        return ! is_null($this->reflectionType) &&
            $this->reflectionType->allowsNull();
    }

    /**
    *   has
    *
    *   @param string $dataType
    *   @return bool
    */
    public function has(
        string $dataType,
    ): bool {
        return in_array($dataType, $this->dataTypes);
    }

    /**
    *   hasBool
    *
    *   @return bool
    */
    public function hasBool(): bool
    {
        return $this->has('bool');
    }

    /**
    *   hasInt
    *
    *   @return bool
    */
    public function hasInt(): bool
    {
        return $this->has('int');
    }

    /**
    *   hasFloat
    *
    *   @return bool
    */
    public function hasFloat(): bool
    {
        return $this->has('float');
    }

    /**
    *   hasString
    *
    *   @return bool
    */
    public function hasString(): bool
    {
        return $this->has('string');
    }

    /**
    *   hasScalar
    *
    *   @return bool
    */
    public function hasScalar(): bool
    {
        return $this->hasBool() ||
            $this->hasInt() ||
            $this->hasFloat() ||
            $this->hasString();
    }

    /**
    *   hasNull
    *
    *   @return bool
    */
    public function hasNull(): bool
    {
        return $this->allowsNull();
    }

    /**
    *   hasObject
    *
    *   @return bool
    */
    public function hasObject(): bool
    {
        return $this->has('object');
    }

    /**
    *   hasArray
    *
    *   @return bool
    */
    public function hasArray(): bool
    {
        return $this->has('array');
    }

    /**
    *   hasMixed
    *
    *   @return bool
    */
    public function hasMixed(): bool
    {
        return $this->has('mixed');
    }

    /**
    *   hasIterable
    *
    *   @return bool
    */
    public function hasIterable(): bool
    {
        return $this->has('iterable');
    }

    /**
    *   hasSelf
    *
    *   @return bool
    */
    public function hasSelf(): bool
    {
        return $this->has('self');
    }

    /**
    *   hasParent
    *
    *   @return bool
    */
    public function hasParent(): bool
    {
        return $this->has('parent');
    }

    /**
    *   hasCallable
    *
    *   @return bool
    */
    public function hasCallable(): bool
    {
        return $this->has('callable');
    }

    /**
    *   hasVoid
    *
    *   @return bool
    */
    public function hasVoid(): bool
    {
        return $this->has('void');
    }

    /**
    *   hasNever
    *
    *   @return bool
    */
    public function hasNever(): bool
    {
        return $this->has('never');
    }

    /**
    *   hasStatic
    *
    *   @return bool
    */
    public function hasStatic(): bool
    {
        return $this->has('static');
    }

    /**
    *   hasFalse
    *
    *   @return bool
    */
    public function hasFalse(): bool
    {
        return $this->has('false');
    }

    /**
    *   satisfied
    *
    *   @param mixed $value
    *   @return bool
    */
    public function satisfied(
        mixed $value,
    ): bool {
        $type = gettype($value);

        if ($type === 'unknown type') {
            throw new DataTypeException(
                "unknown type"
            );
        }

        if ($type === 'NULL') {
            return $this->allowsNull();
        }

        if ($this->hasMixed()) {
            return true;
        }

        if ($type === 'boolean') {
            return $this->hasBool();
        }

        if ($type === 'integer') {
            return $this->hasInt();
        }

        if ($type === 'double') {
            return $this->hasFloat();
        }

        if ($type === 'object') {
            return $this->satisfiedObject($value, $type);
        }

        return $this->has($type);
    }

    /**
    *   satisfiedObject
    *
    *   @param object $value
    *   @param string $type
    *   @return bool
    */
    protected function satisfiedObject(
        object $value,
        string $type,
    ): bool {
        if ($this->isIntersectionType()) {
            return $this->satisfiedIntersectionType($value);
        }

        if (in_array($type, $this->dataTypes)) {
            return true;
        }

        if (in_array(get_class($value), $this->dataTypes)) {
            return true;
        }

        foreach ($this as $type) {
            if (is_a($value, $type)) {
                return true;
            }
        }
        return false;
    }

    /**
    *   satisfiedIntersectionType
    *
    *   @param object $value
    *   @return bool
    */
    public function satisfiedIntersectionType(
        object $value,
    ): bool {
        foreach ($this as $type) {
            if ($type === 'object') {
                continue;
            }

            if (is_a($value, $type)) {
                continue;
            }

            return false;
        }
        return true;
    }
}
