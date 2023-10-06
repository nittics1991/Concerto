<?php

//namespace dev\Validator;

//use \InvalidArgumentException;
//use dev\Validator\RuleOperator;

class RuleLexicer
{
    /**
    *    operators
    *
    *    @var RuleOperators
    */
    private $operators;

    /**
    *    __construct
    *
    *    @param RuleOperators $operators;
    */
    public function __construct(RuleOperators $operators)
    {
        $this->operators = $operators;
    }

    /**
    *     analyze
    *
    *     @param string $policy
    *     @return array
    */
    public function analyze(string $policy): array
    {
        $regex = $this->makeRegex();
        $tokens = (array)mb_split($regex, $policy);
        $operators = $this->eregMatchAll($regex, $policy);

        return array_reduce(
            array_map(
                function ($token, $operator) {
                    return [$token, $operator];
                },
                $tokens,
                $operators
            ),
            function ($carry, $tokenAndOperator) {
                $excludes = [null, ''];

                if (in_array($tokenAndOperator[0], $excludes)) {
                    return in_array($tokenAndOperator[1], $excludes) ?
                        $carry : array_merge($carry, [$tokenAndOperator[1]]);
                }

                if (in_array($tokenAndOperator[1], $excludes)) {
                    return in_array($tokenAndOperator[0], $excludes) ?
                        $carry : array_merge($carry, [$tokenAndOperator[0]]);
                }
                return array_merge($carry, $tokenAndOperator);
            },
            []
        );
    }

    /**
    *     makeRegex
    *
    *     @return string
    */
    public function makeRegex(): string
    {
        $regex = '[';
        foreach ($this->operators as $operator) {
            $oparation = $operator->getOperation();
            $regex .= in_array($oparation, ['[', ']']) ?
                '\\' . $oparation :
                $oparation;
        }
        return "{$regex}]";
    }

    /**
    *     eregMatchAll
    *
    *     @param string $pattern
    *     @param string $string
    *     @return array
    */
    public function eregMatchAll(
        string $pattern,
        string $string
    ): array {
        mb_ereg_search_init($string);
        $counter = mb_strlen($string);
        $result = [];

        while (
            (($ans = mb_ereg_search_regs($pattern)) !== false)
            && ($counter >= 0)
        ) {
            $result[] = $ans[0];
            mb_ereg_search_setpos(mb_ereg_search_getpos());
            $counter--;
        }
        return $result;
    }
}
