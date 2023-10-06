<?php

/**
*   メッセージテンプレート
*
*   @version 230116
*/

declare(strict_types=1);

namespace Concerto\template;

use Concerto\template\MessageGeneratorInterface;

abstract class AbstractMessageGenerator implements MessageGeneratorInterface
{
    /**
    *   @var string
    */
    protected string $template;

    /**
    *   @inheritDoc
    *
    */
    abstract public function generate(
        array $parameters = []
    ): string;

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
    public function __construct(
        string $template = ''
    ) {
        $this->template = $template;
    }
}
