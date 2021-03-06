<?php

/**
*   MessageGenerator
*
*   @ver 180619
*   @example message=
*           'attr={{attribute}} value={{value}} constraint={{constraint}}
*           params={{parameters0}},{{parameters2}},{{parameters3}} ..'
*/

declare(strict_types=1);

namespace Concerto\validation;

use Concerto\template\CurlyBracketMessageGenerator;
use Concerto\validation\MessageGeneratorInterface;
use Concerto\validation\ValidationInterface;

class MessageGenerator implements MessageGeneratorInterface
{
    /**
    *   messageGenerator
    *
    *   @var CurlyBracketMessageGenerator
    */
    protected $messageGenerator;

    /**
    *   __construct
    *
    *   @param CurlyBracketMessageGenerator
    */
    public function __construct(
        CurlyBracketMessageGenerator $messageGenerator
    ) {
        $this->messageGenerator = $messageGenerator;
    }

    /**
    *   {inherit}
    *
    */
    public function create($message)
    {
        $generator = $this->messageGenerator->create($message);
        return new static($generator);
    }

    /**
    *   {inherit}
    *
    */
    public function generate(ValidationInterface $validation)
    {
        $args = [
            'attribute' => $validation->attribute(),
            'constraint' => $validation->constraint(),
            'value' => $validation->value(),
        ];

        foreach ($validation->parameters() as $i => $val) {
            $args["parameters{$i}"] = $val;
        }
        return $this->messageGenerator->generate($args);
    }
}
