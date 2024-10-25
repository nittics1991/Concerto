<?php

//namespace dev\@\Validator\test;

use PHPUnit\Framework\TestCase;

//use dev\@\Validator\RuleLexicer;
//use \BadMethodCallException;

require_once 'RuleOperators.php';
//include 'OperationLawTypeEnum.php';
//include 'Enum.php';


class RuleOperatorsTest extends TestCase
{
    /**
    * @test
    */
    public function basicSuccess()
    {
        //$this->markTestIncomplete();

        $obj = new RuleOperators();

        $i = 0;
        foreach ($obj as $operator) {
            $this->assertEquals(true, $operator instanceof RuleOperator);
            $i++;
        }
        $this->assertEquals(9, $i);

        $this->assertEquals(true, $obj->isOperation('*'));
        $this->assertEquals(false, $obj->isOperation('X'));

        $this->assertEquals(3, $obj->priority('*'));
        $this->assertEquals(1, $obj->priority(','));

        $this->assertEquals(OperationLawTypeEnum::LEFT, $obj->law('*'));
        $this->assertEquals(OperationLawTypeEnum::NON(), $obj->law(','));

        $this->assertEquals('actionAnd', $obj->action('*'));
        $this->assertEquals('actionSarg', $obj->action(','));
    }

    /**
    * @test
    */
    public function priorityException()
    {
        //$this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new RuleOperators();
        $obj->priority('X');
    }

    /**
    * @test
    */
    public function lawException()
    {
        //$this->markTestIncomplete();

        $this->expectException(InvalidArgumentException::class);
        $obj = new RuleOperators();
        $obj->law('X');
    }
}
