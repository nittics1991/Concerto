<?php

/**
*   DeepCopyTrait
*
*   @version 210914
*/

declare(strict_types=1);

namespace dev\@\deepcopy;

use DateTimeInterface;
use DateTimeZone;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use RuntimeException;
use SplDoublyLinkedList;

trait DeepCopyTrait
{
    /**
    *   @var object[]
    */
    private array $copiedObject = [];

    /**
    *   @var bool
    */
    private bool $skipUncloneable = false;

    /**
    *   @var bool
    */
    private bool $useCloneMethod = false;

    /**
    *   __construct
    *
    *   @param ?bool $useCloneMethod
    */
    public function __construct(
        ?bool $useCloneMethod
    ) {
        $this->useCloneMethod = $useCloneMethod ?? false;
    }

    /**
    *   skipUncloneable
    *
    *   @param bool $skipUncloneable
    *   @return static
    */
    public function skipUncloneable(
        bool $skipUncloneable
    ): static {
        $this->skipUncloneable = $skipUncloneable;
        return $this;
    }

    /**
    *   copy
    *
    *   @param mixed $object
    *   @return mixed
    */
    public function copy(mixed $object): mixed
    {
        $this->copiedObject = [];
        return $this->recursiveCopy($object);
    }

    /**
    *   recursiveCopy
    *
    *   @param mixed $var
    *   @return mixed
    */
    private function recursiveCopy(mixed $var): mixed
    {
        if (is_resource($var)) {
            return $var;
        }

        if (is_array($var)) {
            return $this->copyArray($var);
        }

        if (! is_object($var)) {
            return $var;
        }

        return $this->copyObject($var);
    }

    /**
    *   copyArray
    *
    *   @param array $array
    *   @return array
    */
    private function copyArray(array $array): array
    {
        foreach ($array as $key => $value) {
            $array[$key] = $this->recursiveCopy($value);
        }
        return $array;
    }

    /**
    *   copyObject
    *
    *   @param object $object
    *   @return object
    */
    private function copyObject(object $object): object
    {
        $objectHash = spl_object_hash($object);

        if (isset($this->copiedObject[$objectHash])) {
            return $this->copiedObject[$objectHash];
        }

        $reflectedObject = new ReflectionObject($object);
        $isCloneable = $reflectedObject->isCloneable();

        if (false === $isCloneable) {
            if ($this->skipUncloneable) {
                $this->copiedObject[$objectHash] = $object;

                return $object;
            }

            throw new RuntimeException(
                sprintf(
                    'The class "%s" is not cloneable.',
                    $reflectedObject->getName()
                )
            );
        }

        $newObject = clone $object;
        $this->copiedObject[$objectHash] = $newObject;

        if (
            $this->useCloneMethod &&
            $reflectedObject->hasMethod('__clone')
        ) {
            return $newObject;
        }

        if (
            $newObject instanceof DateTimeInterface ||
            $newObject instanceof DateTimeZone
        ) {
            return $newObject;
        }

        foreach ($this->getProperties($reflectedObject) as $property) {
            $this->copyObjectProperty($newObject, $property);
        }

        return $newObject;
    }

    /**
    *   copyObjectProperty
    *
    *   @param object $object
    *   @param ReflectionProperty $property
    *   @return void
    */
    private function copyObjectProperty(
        object $object,
        ReflectionProperty $property
    ): void {
        // Ignore static properties
        if ($property->isStatic()) {
            return;
        }

        $property->setAccessible(true);

        if (
            method_exists($property, 'isInitialized') &&
            !$property->isInitialized($object)
        ) {
            return;
        }

        $propertyValue = $property->getValue($object);
        $property->setValue(
            $object,
            $this->recursiveCopy($propertyValue)
        );
    }

    /**
    *   getReflectionProperties
    *
    *   @param ReflectionClass $ref
    *   @return ReflectionProperty[]
    */
    private function getReflectionProperties(ReflectionClass $ref): array
    {
        $props = $ref->getProperties();
        $propsArr = [];

        foreach ($props as $prop) {
            $propertyName = $prop->getName();
            $propsArr[$propertyName] = $prop;
        }

        if ($parentClass = $ref->getParentClass()) {
            $parentPropsArr = $this->getReflectionProperties($parentClass);
            foreach ($propsArr as $key => $property) {
                $parentPropsArr[$key] = $property;
            }

            return $parentPropsArr;
        }

        return $propsArr;
    }
}
