<?php

namespace dev\Validator;

class RuleLexicer
{
    protected $operatorPriorities = [
        '!' => 4,
        '*' => 3,
        '+' => 2,
        '^' => 2,
    ];

    /**
    *     analyze
    *
    *     @param array $tokens
    *     @return array [[callable, arg1, ...], ...]
    */
    public function analyze(array $tokens): array
    {
        $outputs = [];
        $stacks = [];
        $stackIndex = 0;


        //*+^!は関数に置換するか?


        foreach ($tokens as $token) {
            switch ($token) {
                case '(':
                    $stacks[] = [];
                    $stackIndex++;
                    break;
                case ')'
                    $outputs += $this->popInnerStack($stacks[$stackIndex]);
                    unset($stacks[$stackIndex]);
                    $stackIndex--;
                    break;
                case '*':
                case '+':
                case '^':
                    //繰り返し
                    if (isStackTopIsOperator($stacks[$stackIndex])) {
                        && compareOperator($stacks[$stackIndex][0], $token) !== 1
                        ) $outputs[] = pop($stacks[$stackIndex])
                    } else {
                        $outputs[] = $token;
                    }

                case '!':
            }
        }

        if (!empty($stacks)) {
            throw new RuntimeException(
                "shunting yard stacks error"
            );
        }
        return $output;
    }

    protected function popInnerStack(array $innerStacks): array
    {
    }

    protected function compareOperator(string $x, string $y): int
    {
        $priortyX = array_key_exists($x, $this->operatorPriorities);
        $priortyY = array_key_exists($y, $this->operatorPriorities);
        return $priortyX <=> $priortyY;
    }

    protected function isStackTopIsOperator(array $stack): bool
    {
        return isset($stack[0]) ??
            in_array($stack[0], array_keys($this->operatorPriorities)):
            false;
    }
}
