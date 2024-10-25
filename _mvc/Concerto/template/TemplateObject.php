<?php

/**
*   TemplateObject
*
*   @version 221226
*/

declare(strict_types=1);

namespace Concerto\template;

use Concerto\template\AbstractMessageGenerator;

class TemplateObject
{
    /**
    *   @var AbstractMessageGenerator
    */
    protected AbstractMessageGenerator $generator;

    /**
    *   @var string
    */
    protected string $template = '';

    /**
    *   __construct
    *
    *   @param AbstractMessageGenerator $generator
    */
    public function __construct(
        AbstractMessageGenerator $generator
    ) {
        $this->generator = $generator;
    }

    /**
    *   append
    *
    *   @param string $contents
    *   @return static
    */
    public function append(
        string $contents
    ): static {
        $this->template .= $contents;

        return $this;
    }

    /**
    *   prepend
    *
    *   @param string $contents
    *   @return static
    */
    public function prepend(
        string $contents
    ): static {
        $this->template = $contents . $this->template;

        return $this;
    }

    /**
    *   apply
    *
    *   @param mixed[] $dataset
    *   @return static
    */
    public function apply(
        array $dataset
    ): static {
        $generator = $this->generator::create($this->template);

        $this->template = $generator->generate($dataset);

        return $this;
    }

    /**
    *   toString
    *
    *   @return string
    */
    public function toString(): string
    {
        return $this->template;
    }
}
