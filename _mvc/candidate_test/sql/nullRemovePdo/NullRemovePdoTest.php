<?php

declare(strict_types=1);

namespace candidate_test\sql\nullRemovePdo;

use test\Concerto\{
    AbstractSqliteTestCase,
    RecordsetTestTrait,
};
use candidate\sql\nullRemovePdo\NullRemovePdo;
use PDO;
use League\Csv\Reader;

class NullRemovePdoTest extends AbstractSqliteTestCase
{
    use RecordsetTestTrait;

    protected string $tablename = 'null_remove_pdo';

    protected array $columns = [
        'id' => 'integer',
        'prefecture' => 'text',
        'city' => 'text',
        'town' => 'text',
    ];

    protected Reader $csv;

    protected function setUp(): void
    {
        $this->pdo = $this->initPdo();

        $this->pdo = $this->createTable(
            $this->tablename,
            $this->columns,
            $this->pdo,
        );

        $this->csv = Reader::createFromPath(
            implode(
                DIRECTORY_SEPARATOR,
                [
                    __DIR__,
                    'data',
                    "{$this->tablename}.csv",
                ],
            ),
        );

        $this->csv->setHeaderOffset(0);

        $this->importData(
            $this->tablename,
            $this->itaratorsToTable($this->csv),
            $this->pdo,
        );
    }

    /**
    *   @test
    */
    public function checkRowCount()
    {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $this->assertEquals(
            4,
            $this->rowCount(
                $this->tablename,
                $this->pdo,
            ),
        );
    }

    public function queryProvider()
    {
        $params0 = [
        ];

        $params1 = [
            'prefecture' => 'tokyo',
        ];

        $params2 = [
            'prefecture' => 'tokyo',
            'city' => 'shinjyuku',
        ];

        $params1null = [
            'prefecture' => null,
        ];

        $params2null1st = [
            'prefecture' => null,
            'city' => 'fuchu',
        ];

        $params2null2nd = [
            'prefecture' => 'tokyo',
            'city' => null,
        ];

        $numparams1 = [
            'tokyo',
        ];

        $numparams2null2nd = [
            'tokyo',
            null,
        ];

        $numparams2null1st = [
            null,
            'tokyo',
        ];

        $params3 = [
            'prefecture' => 'tokyo',
            'city' => 'chiyoda',
            'town' => 'oote',
        ];

        $params3null1st = [
            'prefecture' => null,
            'city' => 'chiyoda',
            'town' => 'oote',
        ];

        $params3null2nd = [
            'prefecture' => 'tokyo',
            'city' => null,
            'town' => 'oote',
        ];

        $params3null3rd = [
            'prefecture' => 'tokyo',
            'city' => 'chiyoda',
            'town' => null,
        ];

        return [
            //0
            [
                "
                    SELECT * FROM {$this->tablename}
                ",
                $params0,
                4,
                "no params no Enclodure",
            ],

            //1
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE prefecture = :prefecture
                ",
                $params1,
                3,
                "1 param match no Enclodure",
            ],

            //2
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE prefecture = :prefecture
                        AND city = :city
                ",
                $params2,
                1,
                "2 param matche next line no Enclodure",
            ],

            //3
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE prefecture = :prefecture AND city = :city
                ",
                $params2,
                1,
                "2 param match same line no Enclodure",
            ],

            //4
            [
                "
                    SELECT * FROM {$this->tablename}
                    @@@ WHERE prefecture = :prefecture @@@
                ",
                $params1,
                3,
                "1 param match",
            ],

            //5
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                ",
                $params2,
                1,
                "2 param matche next line",
            ],

            //6
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@
                ",
                $params2,
                1,
                "2 param match same line",
            ],

            //7
            [
                "
                    SELECT * FROM {$this->tablename}
                    @@@ WHERE prefecture = :prefecture @@@
                ",
                $params1null,
                4,
                "1 param no match",
            ],

            //8
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                ",
                $params2null1st,
                1,
                "2 param 1st no matche next line",
            ],

            //9
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@
                ",
                $params2null1st,
                1,
                "2 param 1st no match same line",
            ],

            //10
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                ",
                $params2null2nd,
                3,
                "2 param 2nd no matche next line",
            ],

            //11
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@
                ",
                $params2null2nd,
                3,
                "2 param 2nd no match same line",
            ],

            //12
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@ AND prefecture IN (:prefecture) @@@
                ",
                $params1,
                3,
                "1 param no match in clause",
            ],

            //13
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@ AND prefecture IN (:prefecture) @@@
                ",
                $params1null,
                4,
                "1 param match in clause",
            ],

            //14
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (
                            @@@ :0 @@@
                        )
                ",
                $numparams1,
                3,
                "num 1 param matche next line",
            ],

            //15
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (@@@ :0 @@@)
                ",
                $numparams1,
                3,
                "num 1 param matche same line",
            ],

            //16
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (
                            @@@ :0 @@@
                            @@@ ,:1 @@@
                        )
                ",
                $numparams2null2nd,
                3,
                "num 2 param matche next line",
            ],

            //17
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (@@@ :0 @@@ @@@ ,:1 @@@)
                ",
                $numparams2null2nd,
                3,
                "num 1 param matche same line",
            ],

            //18
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (
                            @@@ :0, @@@
                            @@@ :1 @@@
                        )
                ",
                $numparams2null1st,
                3,
                "num 2 param matche next line",
            ],

            //18
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (@@@ :0, @@@ @@@ :1 @@@)
                ",
                $numparams2null1st,
                3,
                "num 1 param matche same line",
            ],

            //19
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                        @@@ AND town = :town @@@
                ",
                $params3,
                1,
                "3 param matche next line",
            ],

            //20
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@ @@@ AND town = :town@@@
                ",
                $params3,
                1,
                "3 param match same line",
            ],

            //21
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                        @@@ AND town = :town @@@
                ",
                $params3null2nd,
                1,
                "3 param 2nd no matche next line",
            ],

            //22
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@ @@@ AND town = :town@@@
                ",
                $params3null1st,
                1,
                "3 param 1st no match same line",
            ],

            //23
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                        @@@ AND town = :town @@@
                ",
                $params3null1st,
                1,
                "3 param 1st no matche next line",
            ],

            //24
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@ @@@ AND town = :town@@@
                ",
                $params3null2nd,
                1,
                "3 param 2nd no match same line",
            ],

            //25
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        @@@AND prefecture = :prefecture@@@
                        @@@ AND city = :city @@@
                        @@@ AND town = :town @@@
                ",
                $params3null3rd,
                1,
                "3 param 3rd no matche next line",
            ],

            //26
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 @@@AND prefecture = :prefecture@@@ @@@ AND city = :city @@@ @@@ AND town = :town@@@
                ",
                $params3null3rd,
                1,
                "3 param 3rd no match same line",
            ],

            //27
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 
                        AND prefecture = :prefecture
                        @@@ AND city = :city @@@
                        AND town = :town 
                ",
                $params3null2nd,
                1,
                "3 param 2nd no matche next line target only",
            ],

            //28
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1 AND prefecture = :prefecture @@@ AND city = :city @@@  AND town = :town
                ",
                $params3null2nd,
                1,
                "3 param 2nd no match same line target only",
            ],
        ];
    }

    /**
    *   @test
    *   @dataProvider queryProvider
    */
    public function query(
        string $sql,
        array $params,
        int $expect,
        string $message,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        try {
            $obj = (new NullRemovePdo($this->pdo))
            ->sql($sql)
            ->bind($params)
            ->execute();

            $dataset = $obj->fetchAll();
        } catch (\Throwable $e) {
            var_dump($e);
            throw $e;
        }


        $this->assertEquals(
            $expect,
            count($dataset),
            $message,
        );

        foreach ($dataset as $row) {
            foreach ($this->csv as $data) {
                if ($row == $data) {
                    //match
                    $this->assertEquals($data, $row);
                    continue 2;
                }
            }
            //unmatch
            $this->assertEquals($data, $row, $message);
        }
    }

    public function statementProvider()
    {
        return [
            [
                "
                    SELECT * FROM {$this->tablename}
                    WHERE 1 = 1
                        AND prefecture IN (@@@ :0, @@@ @@@ :1 @@@)
                ",
                [null, 'tokyo'],
            ],
        ];
    }

        /**
    *   @test
    *   @dataProvider statementProvider
    */
    public function statement(
        string $sql,
        array $params,
    ) {
//      $this->markTestIncomplete('--- markTestIncomplete ---');

        $stmt = (new NullRemovePdo($this->pdo))
            ->sql($sql)
            ->bind($params)
            ->execute()
            ->statement();

        foreach ($stmt as $row) {
            foreach ($this->csv as $data) {
                if ($row == $data) {
                    //match
                    $this->assertEquals($data, $row);
                    continue 2;
                }
            }
            //unmatch
            $this->assertEquals($data, $row, $message);
        }
    }
}
