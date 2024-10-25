<?php

namespace dev\Validator;

class RuleLexicer
{
    /**
    *     analyze
    *
    *     @param string $policy
    *     @return array
    */
    public function analyze(string $policy): array
    {
        $regex = '[()*+!^]';
        $tokens = (array)mb_split($regex, $policy);

        mb_ereg_search_init($policy, $regex);
        $operators = (array)mb_ereg_search_regs($regex);

        return array_reduce(
            array_map(
                function ($token, $operator) {
                    return
                },
                $tokens,
                $operators
            ),
            function ($carry, $tokenAndOperator) {
                return $carry + $tokenAndOperator
            },
            []
        );
    }
}
