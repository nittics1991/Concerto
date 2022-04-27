<?php

/**
*   メッセージテンプレート
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\template;

use Concerto\template\MessageGeneratorInterface;

abstract class AbstractMessageGenerator implements MessageGeneratorInterface
{
    /**
    *   template
    *
    *   @var string
    */
    protected $template;

    /**
    *   {inherit}
    *
    */
    abstract public function generate(array $parameters = []): string;

    /**
    *   create
    *
    *   @param string $template
    *   @return MessageGeneratorInterface
    */
    public static function create(
        string $template = ''
    ): MessageGeneratorInterface {
        return new static($template);
    }

    /**
    *   __construct
    *
    *   @param string $template
    */
    public function __construct(string $template = '')
    {
        $this->template = $template;
    }
}
