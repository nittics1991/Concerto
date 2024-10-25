<?php

declare(strict_types=1);

namespace test\Concerto\reflection\tester;

use Countable;
use DateTimeInterface;
use IteratorAggregate;
use stdClass;

class ReflectionDataTypeTester1
{
    public bool $bool;
    public int $int;
    public float $float;
    public string $string;
    public array $array;
    public object $object;
    public iterable $iterable;
    public mixed $mixed;
    public stdClass $class_stdClass;
    public DateTimeInterface $interface_dateTimeInterface;
    public ?bool $nullable_bool;
    public int|float $union_int_float;
    public int|float|null $union_int_float_null;
    public int|false $union_int_false;
    public IteratorAggregate & Countable $intersect_iterator_countable;
    public ReflectionDataTypeTester1 $tester1;
    public test\Concerto\reflection\tester\ReflectionDataTypeTester1 $namespace_tester1;

    public function __construct(
        ?string $property_name = null,
        mixed $value = null,
    ) {
        if (isset($property_name)) {
            $this->$property_name = $value;
        }
    }

    public function retrunCallable(): callable
    {
        return 'is_callable';
    }

    public function retrunVoid(): void
    {
        return;
    }

    public function retrunNever(): never
    {
        exit;
    }

    public function papameterIteratorCountable(
        IteratorAggregate&Countable $intersect_iterator_countable
    ): void {
    }
}
