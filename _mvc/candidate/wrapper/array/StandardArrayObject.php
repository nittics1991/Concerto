<?php

/**
*   StandardArrayObject
*
*   @version 210726
*/

declare(strict_types=1);

namespace candidate\wrapper\array;

use BadMethodCallException;
use candidate\wrapper\array\{
    BasicFunction,
    DefineMethodTrait,
    NotHaveArrayArgumentFunction,
    ReferToFunction,
    ValueReturnFunction,
    ValueToFunction,
};

class StandardArrayObject
{
    use DefineMethodTrait;

    /**
    *   @val array
    */
    protected array $functions = [
        NotHaveArrayArgumentFunction::class,
        ReferToFunction::class,
        ValueReturnFunction::class,
        ValueToFunction::class,
    ];

    /**
    *   @val array
    */
    protected array $dataset;

    /**
    *   @val array
    */
    protected array $delegates = [];

    /**
    *   @val mixed
    */
    protected mixed $related_value = null;

    /**
    *   __construct
    *
    *   @param iterable $dataset
    */
    public function __construct(
        iterable $dataset,
        mixed $related_value = null,
    ) {
        $this->dataset = is_array($dataset) ?
            $dataset : iterator_to_array($dataset);
        $this->related_value = $related_value;
    }

    /**
    *   toArray
    *
    *   @return array
    */
    public function toArray(): array
    {
        return $this->dataset;
    }

    /**
    *   @inheritDoc
    *
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {

        foreach (['', 'array_'] as $prefix) {
            $function_name = $prefix . $this->studyToSnaKe($name);

            if ($this->hasInDeligate($function_name)) {
                   $delegated = $this->resolveDeligate($function_name);
                   $return = $delegated->execute(
                       $this->toArray(),
                       $function_name,
                       $arguments,
                   );

                   return is_array($return) ?
                        new static(
                            $return,
                            $delegated->relatedValue(),
                        ) :
                        $return;
            }
        }

        throw new BadMethodCallException(
            "not defined method:{$name}"
        );
    }

    /**
    *   studyToSnaKe
    *
    *   @param string $study_string
    *   @return string
    */
    protected function studyToSnaKe(
        string $study_string,
    ): string {
        $replaced = (string)mb_ereg_replace_callback(
            '[A-Z]',
            function ($matches) {
                return '_'  . mb_strtolower($matches[0]);
            },
            $study_string
        );

        if (
            mb_substr($replaced, 0, 1) == '_' &&
            mb_substr($study_string, 0, 1) != '_'
        ) {
            return mb_substr($replaced, 1);
        }
        return $replaced;
    }

    /**
    *   hasInDeligate
    *
    *   @param string $function_name
    *   @return bool
    */
    protected function hasInDeligate(
        string $function_name,
    ): bool {
        $this->delegate();

        foreach ($this->delegates as $object) {
            if ($object->has($function_name)) {
                return true;
            }
        }
        return false;
    }

    /**
    *   delegate
    *
    *   @return static
    */
    protected function delegate(): static
    {
        if ($this->delegates !== []) {
            return $this;
        }

        foreach ($this->functions as $object_name) {
            $this->delegates[] = new $object_name();
        }
        return $this;
    }

    /**
    *   resolveDeligate
    *
    *   @param string $function_name
    *   @return BasicFunction
    */
    protected function resolveDeligate(
        string $function_name,
    ): BasicFunction {
        $this->delegate();

        foreach ($this->delegates as $object) {
            if ($object->has($function_name)) {
                return $object;
            }
        }
        throw new BadMethodCallException(
            "not defined function name:{$function_name}"
        );
    }

    /**
    *   relatedValue
    *
    *   @return mixed
    */
    public function relatedValue(): mixed
    {
        return $this->related_value;
    }
}
