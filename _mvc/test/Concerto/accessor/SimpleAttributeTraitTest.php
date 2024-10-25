<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use stdClass;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\accessor\SimpleAttributeTrait;

class SimpleAttributeTraitTestClass
{
    use SimpleAttributeTrait;
}

class SimpleAttributeTraitTest extends ConcertoTestCase
{
    public static function allMethodsProvider()
    {
        $dummy_obj1 = new stdClass();
        $dummy_obj1->dummy = 123;

        return [
            //int data
            [
                'prop_i',
                12,
            ],
            //int data
            [
                'prop_o',
                $dummy_obj1,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('allMethodsProvider')]
    public function allMethods($name, $value)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new SimpleAttributeTraitTestClass();

        //non data
        unset($obj->$name);

        $this->assertEquals(
            false,
            isset($obj->$name),
        );

        $this->assertEquals(
            null,
            $obj->$name,
        );

        //set data
        $obj->$name = $value;

        $this->assertEquals(
            true,
            isset($obj->$name),
        );

        $this->assertEquals(
            $value,
            $obj->$name,
        );

        //unset data
        unset($obj->$name);

        $this->assertEquals(
            false,
            isset($obj->$name),
        );

        $this->assertEquals(
            null,
            $obj->$name,
        );
    }
}
