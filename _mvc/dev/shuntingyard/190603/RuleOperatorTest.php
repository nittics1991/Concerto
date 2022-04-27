<?php

//namespace dev\@\Validator\test;

use PHPUnit\Framework\TestCase;

//use dev\@\Validator\RuleLexicer;
//use \BadMethodCallException;

require_once 'RuleOperator.php';
require_once 'OperationLawTypeEnum.php';
//include 'Enum.php';


class RuleOperatorTest extends TestCase
{
    public function getterSuccessProvider()
    {
        return [
            [
                [
                    'operation' => '+',
                    'priority' => 2,
                    'law' => new OperationLawTypeEnum(
                        OperationLawTypeEnum::RIGHT
                    ),
                    'action' => 'is_int',
                ],
                [
                    'operation' => '!',
                    'priority' => 4,
                    'law' => OperationLawTypeEnum::LEFT(),
                    'action' => 'is_int',
                ],
            ],
        ];
    }

    /**
    * @test
    * @dataProvider getterSuccessProvider
    */
    public function getterSuccess($data)
    {
        //$this->markTestIncomplete();

        $obj = new RuleOperator($data);

        $this->assertEquals($data['operation'], $obj->getOperation());
        $this->assertEquals($data['priority'], $obj->getPriority());
        $this->assertEquals($data['law'], $obj->getLaw());
        $this->assertEquals($data['action'], $obj->getAction());
    }

    /**
    * @test
    * @dataProvider getterSuccessProvider
    */
    public function getterException($data)
    {
        //$this->markTestIncomplete();

        $this->expectException(BadMethodCallException::class);
        $obj = new RuleOperator($data);
        $obj->getDUMMY();
    }
}
