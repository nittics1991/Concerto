<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridRecordCollection;
use Concerto\sigmagrid\grid\SigmagridSaveRequest;
use Concerto\standard\DataContainerValidatable;

class TestSigmagridSaveRequest extends DataContainerValidatable
{
    protected static $schema = [
        'id',
        'name',
        'syohin',
        'user',
    ];

    public function isValidId($val)
    {
        return is_int($val);
    }

    public function isValidName($val)
    {
        return is_string($val) && mb_strlen($val) > 0;
    }

    public function isValidSyohin($val)
    {
        return is_string($val) && mb_strlen($val) >= 3;
    }

    public function isValidUser($val)
    {
        return is_string($val) && mb_strlen($val) <= 6;
    }
}

/////////////////////////////////////////////////////////////////////

class SigmagridSaveRequestTest extends ConcertoTestCase
{
    public function setUp(): void
    {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
    }

    public function constructProvider()
    {
        return [
            [
                [
                    'recordType' => 'object',
                    'action' => 'save',

                    'fieldsName' => [
                        'id',
                        'name',
                        'syohin',
                        'user',
                    ],

                    'insertedRecords' => [
                        [
                            'id' => 1,
                            'name' => 'aaa',
                            'syohin' => 'bbb',
                            'user' => 'ccc',
                        ],
                        [
                            'id' => 2,
                            'name' => 'aaa2',
                            'syohin' => 'bbb2',
                            'user' => 'ccc2',
                        ],
                    ],

                    'updatedRecords' => [
                        [
                            'id' => 11,
                            'name' => '1aaa',
                            'syohin' => '1bbb',
                            'user' => '1ccc',
                        ],
                        [
                            'id' => 12,
                            'name' => '1aaa2',
                            'syohin' => '1bbb2',
                            'user' => '1ccc2',
                        ],
                    ],

                    'deletedRecords' => [
                        [
                            'id' => 21,
                            'name' => '2aaa',
                            'syohin' => '2bbb',
                            'user' => '2ccc',
                        ],
                        [
                            'id' => 22,
                            'name' => '2aaa2',
                            'syohin' => '2bbb2',
                            'user' => '2ccc2',
                        ],
                    ],
                ],  //params
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function construct1($params)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $expect = [
            'recordType' => 'object',
            'action' => 'save',

            'fieldsName' => [
                'id',
                'name',
                'syohin',
                'user',
            ],

            'insertedRecords' => new SigmagridRecordCollection(
                $params['fieldsName'],
                $params['insertedRecords'],
                new TestSigmagridSaveRequest(),
                $params['recordType']
            ),

            'updatedRecords' => new SigmagridRecordCollection(
                $params['fieldsName'],
                $params['updatedRecords'],
                new TestSigmagridSaveRequest(),
                $params['recordType']
            ),

            'deletedRecords' => new SigmagridRecordCollection(
                $params['fieldsName'],
                $params['deletedRecords'],
                new TestSigmagridSaveRequest(),
                $params['recordType']
            ),
        ];

        $_POST['_gt_json'] = json_encode($params);
        $object = new SigmagridSaveRequest(new TestSigmagridSaveRequest());

        $this->assertEquals(true, $object->isValid());
        $this->assertEquals($expect['recordType'], $object->recordType);
        $this->assertEquals($expect['action'], $object->action);

        $this->assertInstanceOf(SigmagridRecordCollection::class, $object->insertedRecords);
        $this->assertEquals($expect['insertedRecords'], $object->insertedRecords);

        foreach ($object->insertedRecords as $obj) {
            $this->assertEquals(current($params['insertedRecords']), $obj->toArray());
            next($params['insertedRecords']);
        }

        $this->assertInstanceOf(SigmagridRecordCollection::class, $object->updatedRecords);
        $this->assertEquals($expect['updatedRecords'], $object->updatedRecords);

        foreach ($object->updatedRecords as $obj) {
            $this->assertEquals(current($params['updatedRecords']), $obj->toArray());
            next($params['updatedRecords']);
        }

        $this->assertInstanceOf(SigmagridRecordCollection::class, $object->deletedRecords);
        $this->assertEquals($expect['deletedRecords'], $object->deletedRecords);

        foreach ($object->deletedRecords as $obj) {
            $this->assertEquals(current($params['deletedRecords']), $obj->toArray());
            next($params['deletedRecords']);
        }
    }
}
