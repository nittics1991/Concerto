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

namespace dev\validation;

use dev\template\CurlyBracketMessageGenerator;
use dev\validation\MessageGeneratorInterface;
use dev\validation\ValidationInterface;

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
    *   @inheritDoc
    *
    */
    public function create($message)
    {
        $generator = $this->messageGenerator->create($message);
        return new static($generator);
    }

    /**
    *   @inheritDoc
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
