<?php

/**
*   メッセージテンプレート
*
*   @ver 180614
**/

namespace Concerto\template;

use Concerto\template\MessageGeneratorInterface;

abstract class AbstractMessageGenerator implements MessageGeneratorInterface
{
    /**
    *   template
    *
    *   @var string
    **/
    protected $template;
    
    /**
    *   {inherit}
    *
    **/
    abstract public function generate(array $parameters = []): string;
    
    /**
    *   __construct
    *
    *   @param string $template
    **/
    public function __construct(string $template = '')
    {
        $this->template = $template;
    }
    
    /**
    *   create
    *
    *   @param string $template
    *   @return MessageGeneratorInterface
    **/
    public function create(string $template = ''): MessageGeneratorInterface
    {
        return new static($template);
    }
}
