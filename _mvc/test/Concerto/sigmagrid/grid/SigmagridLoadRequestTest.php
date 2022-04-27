<?php

declare(strict_types=1);

namespace test\Concerto\sigmagrid;

use test\Concerto\ConcertoTestCase;
use Concerto\sigmagrid\grid\SigmagridColumnInfo;
use Concerto\sigmagrid\grid\SigmagridColumnInfos;
use Concerto\sigmagrid\grid\SigmagridFilterInfo;
use Concerto\sigmagrid\grid\SigmagridFilterInfos;
use Concerto\sigmagrid\grid\SigmagridLoadRequest;
use Concerto\sigmagrid\grid\SigmagridPageInfo;
use Concerto\sigmagrid\grid\SigmagridPageInfos;
use Concerto\sigmagrid\grid\SigmagridSortInfo;
use Concerto\sigmagrid\grid\SigmagridSortInfos;
use Concerto\standard\DataContainerValidatable;

class TestAddParam extends DataContainerValidatable
{
    protected static $schema = ['id', 'section', 'name'];

    public function isValidId($val)
    {
        return is_int($val);
    }

    public function isValidSection($val)
    {
        return is_string($val);
    }

    public function isValidName($val)
    {
        return is_string($val);
    }
}

////////////////////////////////////////////////////////////////////////////////

class SigmagridLoadRequestTest extends ConcertoTestCase
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
                    'recordType' => 'array',
                    'action' => 'load',
                    'remotePaging' => true,
                    'exportType' => 'csv',
                    'exportFileName' => 'z:\\temp\\ext.csv',

                    'columnInfo' => [
                        [
                            'id' => 'cd_tanto',
                            'header' => '担当名',
                            'fieldName' => 'tanto',
                            'fieldIndex' => 3,
                            'sortOrder' => 'asc',
                            'hidden' => true,
                            'exportable' => false,
                            'printable' => true,
                        ],
                        [
                            'id' => 'cd_tanto2',
                            'header' => '担当名2',
                            'fieldName' => 'tanto2',
                            'fieldIndex' => 6,
                            'sortOrder' => 'desc',
                            'hidden' => false,
                            'exportable' => true,
                            'printable' => false,
                        ],
                    ],
                    'filterInfo' => [
                        [
                            'fieldName' => 'cd_tanto',
                            'value' => 12,
                            'logic' => 'lessEqual',
                        ],
                        [
                            'fieldName' => 'cd_tanto3',
                            'value' => 24,
                            'logic' => 'equal',
                        ],
                    ],
                    'pageInfo' => [
                        'pageSize' => 11,
                        'pageNum' => 3,
                        'totalRowNum' => 100,
                        'totalPageNum' => 10,
                        'startRowNum' => 11,
                        'endRowNum' => 20,
                    ],
                    'sortInfo' => [
                        [
                            'columnId' => 'cd_tanto',
                            'fieldName' => 'tanto',
                            'sortOrder' => 'asc',
                            'getSortValue' => 'abc',
                            'sortFn' => 'krsort',
                        ],
                        [
                            'columnId' => 'cd_tanto3',
                            'fieldName' => 'tanto3',
                            'sortOrder' => 'desc',
                            'getSortValue' => 'ZSE',
                            'sortFn' => 'sort',
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
            'recordType' => 'array',
            'action' => 'load',
            'remotePaging' => true,
            'exportType' => 'csv',
            'exportFileName' => 'z:\\temp\\ext.csv',

            'pageInfo' => new SigmagridPageInfo($params['pageInfo']),

            'columnInfo' => new SigmagridColumnInfos($params['columnInfo']),
            'filterInfo' => new SigmagridFilterInfos($params['filterInfo']),
            'sortInfo' => new SigmagridSortInfos($params['sortInfo']),
        ];

        $_POST['_gt_json'] = json_encode($params);
        $object = new SigmagridLoadRequest();

        $this->assertEquals(true, $object->isValid());
        $this->assertEquals($expect['recordType'], $object->recordType);
        $this->assertEquals($expect['action'], $object->action);
        $this->assertEquals($expect['remotePaging'], $object->remotePaging);
        $this->assertEquals($expect['exportType'], $object->exportType);
        $this->assertEquals($expect['exportFileName'], $object->exportFileName);

        $this->assertInstanceOf(SigmagridPageInfo::class, $object->pageInfo);
        $this->assertEquals($expect['pageInfo'], $object->pageInfo);

        $this->assertInstanceOf(SigmagridColumnInfos::class, $object->columnInfo);
        $this->assertEquals($expect['columnInfo'], $object->columnInfo);

        foreach ($object->columnInfo as $obj) {
            $this->assertEquals(current($params['columnInfo']), $obj->toArray());
            next($params['columnInfo']);
        }

        $this->assertInstanceOf(SigmagridFilterInfos::class, $object->filterInfo);
        $this->assertEquals($expect['filterInfo'], $object->filterInfo);

        foreach ($object->filterInfo as $obj) {
            $this->assertEquals(current($params['filterInfo']), $obj->toArray());
            next($params['filterInfo']);
        }

        $this->assertInstanceOf(SigmagridSortInfos::class, $object->sortInfo);
        $this->assertEquals($expect['sortInfo'], $object->sortInfo);

        foreach ($object->sortInfo as $obj) {
            $this->assertEquals(current($params['sortInfo']), $obj->toArray());
            next($params['sortInfo']);
        }
    }

    /**
    *   @test
    *   @dataProvider constructProvider
    */
    public function addParam($params)
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $params['parameters'] = [
            'id' => 12,
            'section' => 'aaa',
            'name' => 'bbbbb',
        ];

        $_POST['_gt_json'] = json_encode($params);
        $object = new SigmagridLoadRequest(new TestAddParam());
        $this->assertEquals(
            $params['parameters'],
            $object->parameters->toArray()
        );
    }
}
