<?php

//namespace dev\@\Validator\test;

use PHPUnit\Framework\TestCase;

//use dev\@\Validator\RuleLexicer;
//use \BadMethodCallException;

require_once 'OperationLawTypeEnum.php';
//include 'Enum.php';

class OperationLawTypeEnumTest extends TestCase
{
    /**
    * @test
    */
    public function basicSuccess()
    {
        //$this->markTestIncomplete();

        $obj = new OperationLawTypeEnum('right');
        $this->assertEquals('right', $obj->getValue());
        $this->assertEquals(
            [
                'LEFT' => 'left',
                'RIGHT' => 'right',
                'NON' => 'non',
            ],
            $obj->getValues()
        );
        $this->assertEquals(
            [
                'LEFT',
                'RIGHT',
                'NON',
            ],
            $obj->getKeys()
        );

        $this->assertEquals($obj, OperationLawTypeEnum::RIGHT());
        $this->assertEquals(
            $obj,
            new OperationLawTypeEnum(OperationLawTypeEnum::RIGHT)
        );
    }
}
