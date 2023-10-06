<?php

/**
*   Validator
*
*   @ver 180712
*/

declare(strict_types=1);

namespace dev\validation;

use dev\validation\ValidatorInterface;

class Validator implements ValidatorInterface
{
    /**
    *   resolver
    *
    *   @var RuleResolverInterface
    */
    private $resolver;

    /**
    *   values
    *
    *   @var array
    */
    private $values = [];

    /**
    *   rules
    *
    *   @var array
    */
    private $rules = [];

    /**
    *   messages
    *
    *   @var array
    */
    private $messages = [];

    /**
    *   validations
    *
    *   @var array [ValidationInterface, ...]
    */
    private $validations = [];

    /**
    *   errors
    *
    *   @var array [ValidationInterface, ...]
    */
    private $errors = [];

    /**
    *   immediately
    *
    *   @var bool
    */
    private $immediately = false;

    /**
    *   __construct
    *
    *   @param RuleResolverInterface
    *   @param array
    *   @param array
    *   @param array
    */
    public function __construct(
        RuleResolverInterface $resolver,
        array $values = [],
        array $rules = [],
        array $messages = []
    ) {
        $this->resolver = $resolver;
        $this->values = $values;
        $this->rules = $rules;
        $this->messages = $messages;
    }

    /**
    *   @inheritDoc
    *
    */
    public function isValid()
    {
        $this->errors = [];
        $result = true;
        $this->resolveRule();

        foreach ($this->validations as $validation) {
            $nearest = $validation->isValid();
            $result = $result && $nearest;

            if (!$nearest) {
                $this->errors[] = $validation;
            }

            if ($this->immediately && !$nearest) {
                return false;
            }
        }
        return $result;
    }

    /**
    *   resolveRule
    *
    */
    private function resolveRule()
    {
        $this->validations = [];

        foreach ($this->rules as $attribute => $rule) {
            $this->validations = array_merge(
                $this->validations,
                $this->resolver->resolve(
                    $attribute,
                    $this->values,
                    $rule,
                    $this->messages
                )
            );
        }
    }

    /**
    *   fails
    *
    *   @return bool
    */
    public function fails()
    {
        return !$this->isValid();
    }

    /**
    *   @inheritDoc
    *
    */
    public function errors()
    {
        return $this->errors;
    }

    /**
    *   messages
    *
    *   @return array
    */
    public function messages()
    {
        return array_map(
            function ($error) {
                return $error->message();
            },
            $this->errors
        );
    }

    /**
    *   immediately
    *
    *   @return $this
    */
    public function immediately()
    {
        $this->immediately = true;
        return $this;
    }
}
