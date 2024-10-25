<?php

declare(strict_types=1);

namespace test\Concerto\accessor;

use DomainException;
use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
// use Concerto\accessor\AttributeInterface;
use Concerto\accessor\AttributeTrait;

// class TestAttributeTrait1 implements
    // AttributeInterface
class TestAttributeTrait1
{
    use AttributeTrait;

    protected $propertyDefinitions = [
        'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
    ];
}

class TestAttributeTrait2
{
    use AttributeTrait;
}

class AttributeTraitTest extends ConcertoTestCase
{
    /**
    */
    #[Test]
    public function checkPropertyDefinitions()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        try {
            $obj = new TestAttributeTrait2();

            $this->callPrivateMethod(
                $obj,
                'checkPropertyDefinitions',
                [],
            );
            $this->assertEquals(1, 0);
        } catch (DomainException $e) {
            $this->assertEquals(1, 1);
        }
    }

    /**
    */
    #[Test]
    public function initialStateSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestAttributeTrait1();

        $this->assertEquals(true, $obj->definedProperty('prop_i'));
        $this->assertEquals(false, $obj->definedProperty('DUMMY'));
        $actual = [
            'prop_b', 'prop_i', 'prop_f', 'prop_s', 'prop_a', 'prop_o',
        ];
        $this->assertEquals($actual, $obj->getDefinedProperty());
        $this->assertEquals(false, $obj->has('prop_i'));
    }

    /**
    */
    #[Test]
    public function methodUnitTestSuccess()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new TestAttributeTrait1();
        //set
        $this->callPrivateMethod($obj, 'setDataToContainer', ['prop_i', 123]);
        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(['prop_i' => 123], $expect);

        //get
        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', ['prop_i' ]);
        $this->assertEquals(123, $expect);

        $this->assertEquals(true, $obj->has('prop_i'));

        //set 2件目
        $this->callPrivateMethod($obj, 'setDataToContainer', ['prop_f', 999.9]);
        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', ['prop_f' ]);
        $this->assertEquals(999.9, $expect);

        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(['prop_i' => 123, 'prop_f' => 999.9], $expect);

        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', []);
        $this->assertEquals(['prop_i' => 123, 'prop_f' => 999.9], $expect);

        //unset
        $expect = $this->callPrivateMethod($obj, 'unsetDataFromContainer', ['prop_i' ]);

        $expect = $this->callPrivateMethod($obj, 'getDataFromContainer', ['prop_i' ]);
        $this->assertEquals(null, $expect);

        $this->assertEquals(false, $obj->has('prop_i'));

        $expect = $this->getPrivateProperty($obj, 'dataContainer');
        $this->assertEquals(['prop_f' => 999.9], $expect);
    }

    /**
    */
    #[Test]
    public function checkPropertyNameException()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('not defined property:DUMMY');

        $obj = new TestAttributeTrait1();
        $expect = $this->callPrivateMethod($obj, 'checkPropertyName', ['DUMMY' ]);
    }
}
