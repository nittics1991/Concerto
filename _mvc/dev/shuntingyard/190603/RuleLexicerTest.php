<?php

//namespace dev\@\Validator\test;

use PHPUnit\Framework\TestCase;

//use dev\@\Validator\RuleLexicer;

require_once 'RuleLexicer.php';
require_once 'RuleOperator.php';
require_once 'RuleOperators.php';
require_once 'OperationLawTypeEnum.php';


class RuleLexicerTest extends TestCase
{
    public function analyzeSuccessProvider()
    {
        return [
            [
                'int*gt:3;+((float*lt:0;)^(!null*!nan))+all:(int*gt:-10;),(int*lt:10;);*!eq:0;',
                [
                    'int', '*',
                    'gt', ':', '3', ';',
                    '+', '(', '(',
                    'float', '*',
                    'lt', ':', '0', ';', ')',
                    '^', '(', '!',
                    'null', '*', '!', 'nan',
                    ')', ')', '+',
                    'all', ':',
                    '(', 'int', '*',
                    'gt', ':', '-10', ';', ')',
                    ',', '(', 'int', '*',
                    'lt', ':', '10', ';',
                    ')', ';', '*', '!',
                    'eq', ':', '0', ';',
                ],
            ],
        ];
    }

    /**
    * @test
    * @dataProvider analyzeSuccessProvider
    */
    public function analyzeSuccess($ruleset, $actual)
    {
        //$this->markTestIncomplete();

        $obj = new RuleLexicer(
            new RuleOperators()
        );
        $expect = $obj->analyze($ruleset);
        $this->assertEquals($actual, $expect);
    }
}
