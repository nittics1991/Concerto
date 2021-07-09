<?php

/**
*   TemplateObject
*
*   @version 210610
*/

declare(strict_types=1);

namespace Concerto\template;

use Concerto\template\AbstractMessageGenerator;

class TemplateObject
{
    /**
    *   generator
    *
    *   @var AbstractMessageGenerator
   */
    protected AbstractMessageGenerator $generator;

    /**
    *   template
    *
    *   @var string
   */
    protected string $template = '';

    /**
    *   __construct
    *
    *   @param AbstractMessageGenerator $generator
    */
    public function __construct(AbstractMessageGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
    *   append
    *
    *   @param string $contents
    *   @return $this
    */
    public function append(string $contents)
    {
        $this->template .= $contents;
        return $this;
    }

    /**
    *   prepend
    *
    *   @param string $contents
    *   @return $this
    */
    public function prepend(string $contents)
    {
        $this->template = $contents . $this->template;
        return $this;
    }

    /**
    *   apply
    *
    *   @param array $dataset
    *   @return $this
    */
    public function apply(array $dataset)
    {
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
