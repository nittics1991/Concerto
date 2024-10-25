<?php

/**
*   NumberInterface
*
*   @version 220219
*/

declare(strict_types=1);

namespace Concerto\wrapper\number;

interface NumberInterface
{
    /**
    *   add
    *
    *   @param NumberInterface $operand
    *   @param ?int $scale
    *   @return NumberInterface
    */
    public function add(
        NumberInterface $operand,
        ?int $scale,    
    ): NumberInterface;

    /**
    *   sub
    *
    *   @param NumberInterface $operand
    *   @param ?int $scale
    *   @return NumberInterface
    */
    public function sub(
        NumberInterface $operand,
        ?int $scale,
    ): NumberInterface;

    /**
    *   mul
    *
    *   @param NumberInterface $operand
    *   @param ?int $scale
    *   @return NumberInterface
    */
    public function mul(
        NumberInterface $operand,
        ?int $scale,
    ): NumberInterface;

    /**
    *   div
    *
    *   @param NumberInterface $operand
    *   @param ?int $scale
    *   @return NumberInterface
    */
    public function div(
        NumberInterface $operand,
        ?int $scale,
    ): NumberInterface;

    /**
    *   mod
    *
    *   @param NumberInterface $operand
    *   @param ?int $scale
    *   @return NumberInterface
    */
    public function mod(
        NumberInterface $operand,
        ?int $scale,
    ): NumberInterface;
}
