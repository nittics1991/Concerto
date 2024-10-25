<?php

//namespace dev\Validator;

//use \InvalidArgumentException;
//use dev\Validator\RuleOperator;

class RuleParser
{
    /**
    *    ruleOperators
    *
    *    @var RuleOperators
    */
    private $ruleOperators;

    /**
    *    __construct
    *
    *    @param RuleOperators $ruleOperators
    *    @param RuleFactory $factory
    */
    public function __construct(RuleOperators $ruleOperators, RuleFactory $factory)
    {
        $this->ruleOperators = $ruleOperators;
        $this->factory = $factory;
    }

    /**
    *     execute
    *
    *     @param array $tokens
    *     @return bool
    */
    public function execute(array $tokens): bool
    {
        $stacks = [];
        $position = 0;
        $stacks[$position] = [];

        foreach ($tokens as $token) {
            if ($this->ruleOperators->isOperation($token)) {
                $action = $this->ruleOperators->action($token);


                //どう実装するか?
                $this->$action($token, $stacks[$position]);
            } else {
                $stacks[$position][] = $token;
            }
        }

        if (count($stacks) > 0) {
            //stackから全て取り出す
        }
    }

    /**
    *     actionOr
    *
    *     @param string $token
    *     @param array $stack
    *     @return mixed
    */
    public function actionOr(string $token, array $stack)
    {
    }
}
