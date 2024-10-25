<?php

declare(strict_types=1);

namespace test\Concerto\standard;

use test\Concerto\ConcertoTestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\DataProvider;
use Concerto\standard\Session;

class SessionTest extends ConcertoTestCase
{
    public static function constructprParameterSuccessProvider()
    {
        return [
            [
                null,
                null,
                null
            ],
            [
                'test_session',
                null,
                null
            ],
            [
                null,
                [
                    'b_data' => true,
                    'i_data' => 11,
                    'f_data' => 12.3,
                    's_data' => 'S_DATA',
                    'a_data' => [1,2,3],
                    'o_data' => new \ArrayObject([11,12,13]),
                ],
                null
            ],
            [
                'test_session',
                [
                    'b_data' => true,
                    'i_data' => 11,
                    'f_data' => 12.3,
                    's_data' => 'S_DATA',
                    'a_data' => [1,2,3],
                    'o_data' => new \ArrayObject([11,12,13]),
                ],
                null
            ],
            [
                'test_session',
                [
                    'b_data' => true,
                    'i_data' => 11,
                    'f_data' => 12.3,
                    's_data' => 'S_DATA',
                    'a_data' => [1,2,3],
                    'o_data' => new \ArrayObject([11,12,13]),
                ],
                60
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('constructprParameterSuccessProvider')]
    public function constructprParameterSuccess(
        $namespace,
        $data,
        $max_life_time,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Session(
            $namespace,
            $data,
            $max_life_time,
        );

        $current_time = time();

        $obj_namespace = $this->getPrivateProperty($obj, 'namespace');
        $this->assertEquals(
            $namespace,
            $obj_namespace,
        );

        $obj_max_life_time = $this->getPrivateProperty($obj, 'max_life_time');
        $this->assertEquals(
            $max_life_time ?? 60 * 60 * 4,
            $obj_max_life_time,
        );

        $obj_start_time = $this->getPrivateProperty($obj, 'start_time');
        $this->assertEquals(
            true,
            is_int($obj_start_time) && $obj_start_time <= $current_time
        );

        $obj_data = $this->getPrivateProperty($obj, 'data');

        if (!$namespace) {
            $this->assertEquals(
                $_SESSION,
                $obj_data,
            );
        } else {
            $this->assertEquals(
                $_SESSION[$namespace],
                $obj_data,
            );
        }

        @session_start();
        $_SESSION = [];
        @session_write_close();
    }

    /**
    */
    #[Test]
    #[DataProvider('constructprParameterSuccessProvider')]
    public function keepStartTime(
        $namespace,
        $data,
        $max_life_time,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        static $call_count = 0;
        static $previous_start_time;

        $obj = new Session(
            $namespace,
            $data,
            $max_life_time,
        );

         $obj_start_time = $this->getPrivateProperty($obj, 'start_time');

        if ($call_count === 0) {
            $previous_start_time = $obj_start_time;
            $this->assertEquals(1, 1);
        } else {
            $this->assertEquals(
                $previous_start_time,
                $obj_start_time,
            );
        }

        $call_count++;
    }

    public static function propertyAccdssorProvider()
    {
        return [
            [
                'test_session',
                [
                    'b_data' => true,
                    'i_data' => 11,
                    'f_data' => 12.3,
                    's_data' => 'S_DATA',
                    'a_data' => [1,2,3],
                    'o_data' => new \ArrayObject([11,12,13]),
                ],
                60,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('propertyAccdssorProvider')]
    public function propertyAccdssor(
        $namespace,
        $data,
        $max_life_time,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Session(
            $namespace,
            $data,
            $max_life_time,
        );

        foreach ($data as $key => $val) {
            $this->assertEquals(true, isset($obj->$key));
            $this->assertEquals(true, isset($obj[$key]));

            $this->assertEquals($val, $obj[$key]);
            $this->assertEquals($val, $obj->$key);
        }

        $this->assertEquals(
            count($data),
            count(iterator_to_array($obj)),
        );

        $this->assertEquals(
            $data,
            iterator_to_array($obj),
        );


        $this->assertEquals(
            false,
            isset($obj->addProp),
        );

        $this->assertEquals(
            false,
            isset($obj['addProp']),
        );

        $this->assertEquals(
            null,
            $obj->addProp,
        );

        $this->assertEquals(
            null,
            $obj['addProp'],
        );


        $add_data = 111;

        $obj->addProp = $add_data;

        $this->assertEquals(
            $add_data,
            $obj->addProp,
        );

        $this->assertEquals(
            $add_data,
            $obj['addProp'],
        );

        $obj_data = $this->getPrivateProperty($obj, 'data');

        $this->assertEquals(
            $add_data,
            $obj_data['addProp'],
        );


        $add_data = 222;

        $obj['setProp'] = $add_data;

        $obj->setProp = $add_data;

        $this->assertEquals(
            $add_data,
            $obj->setProp,
        );

        $this->assertEquals(
            $add_data,
            $obj['setProp'],
        );

        $obj_data = $this->getPrivateProperty($obj, 'data');

        $this->assertEquals(
            $add_data,
            $obj_data['setProp'],
        );


        unset($obj->addProp);

        $this->assertEquals(
            false,
            isset($obj->addProp),
        );

        $this->assertEquals(
            false,
            isset($obj['addProp']),
        );


        unset($obj['setProp']);

        $this->assertEquals(
            false,
            isset($obj->setProp),
        );

        $this->assertEquals(
            false,
            isset($obj['setProp']),
        );


        $obj_data = $this->getPrivateProperty($obj, 'data');

        $this->assertEquals(
            true,
            count($obj_data) > 0,
        );

        $obj->unsetAll();

        $obj_data = $this->getPrivateProperty($obj, 'data');

        $this->assertEquals(
            true,
            count($obj_data) === 0,
        );

        @session_start();
        $_SESSION = [];
        @session_write_close();
    }

    public static function isNullAndEmptyProvider()
    {
        return [
            [
                null,
                null,
                null,
                true,
                true,
            ],
            [
                'test_session',
                [
                    'b_data' => true,
                    'i_data' => 11,
                    'f_data' => 12.3,
                    's_data' => 'S_DATA',
                    'a_data' => [1,2,3],
                    'o_data' => new \ArrayObject([11,12,13]),
                ],
                60,
                false,
                false,
            ],
            [
                'test_session',
                [
                    'a_data' => null,
                ],
                60,
                true,
                true,
            ],
            [
                'test_session',
                [
                    'a_data' => [],
                ],
                60,
                false,
                true,
            ],
            [
                'test_session',
                [
                    'a_data' => [null],
                ],
                60,
                false,
                false,
            ],
            [
                'test_session',
                [
                    'a_data' => [[]],
                ],
                60,
                false,
                false,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('isNullAndEmptyProvider')]
    public function isNullAndEmpty(
        $namespace,
        $data,
        $max_life_time,
        $isNullExpect,
        $isEmptyExpect
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Session(
            $namespace,
            $data,
            $max_life_time,
        );

        unset($_SESSION['session_create_time']);

        $this->assertEquals(
            $isNullExpect,
            $obj->isNull()
        );

        $this->assertEquals(
            $isEmptyExpect,
            $obj->isEmpty()
        );

        @session_start();
        $_SESSION = [];
        @session_write_close();
    }

    public static function inAndExportProvider()
    {
        return [
            [
                'test_session',
                [
                    'b_data' => true,
                    'i_data' => 11,
                    'f_data' => 12.3,
                    's_data' => 'S_DATA',
                    'a_data' => [1,2,3],
                    'o_data' => new \ArrayObject([11,12,13]),
                ],
                60,
            ],
        ];
    }

    /**
    */
    #[Test]
    #[DataProvider('inAndExportProvider')]
    public function inAndExport(
        $namespace,
        $data,
        $max_life_time,
    ) {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Session($namespace);

        $this->assertEquals(
            true,
            $obj->isNull()
        );

        $this->assertEquals(
            true,
            $obj->isNull()
        );

        $obj->fromArray($data);

        $obj_data = $this->getPrivateProperty($obj, 'data');

        $this->assertEquals(
            $data,
            $obj_data,
        );

        $this->assertEquals(
            $data,
            $obj->toArray(),
        );

        @session_start();
        $_SESSION = [];
        @session_write_close();
    }

    /**
    *   @not work test
    */
    public function changeID()
    {
        //cliではsession_startしないので動かない

        $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Session();
        $obj->data1 = 111;

        @session_start();
        $privious_id = session_id();

        $obj->changeID();

        @session_start();
        $current_id = session_id();

        $this->assertEquals(
            true,
            $privious_id != $current_id,
        );

        @session_start();
        $_SESSION = [];
        @session_write_close();
    }

    /**
    */
    #[Test]
    public function inLifeTime()
    {
        // $this->markTestIncomplete('--- markTestIncomplete ---');

        $obj = new Session();

        $this->assertEquals(
            true,
            $this->callPrivateMethod($obj, 'inLifeTime', []),
        );

        $max_life_time = $this->getPrivateProperty($obj, 'max_life_time');
        $current_start_time = $this->getPrivateProperty($obj, 'start_time');

        $this->setPrivateProperty(
            $obj,
            'start_time',
            $current_start_time - $max_life_time - 1
        );

        $changed_start_time = $this->getPrivateProperty($obj, 'start_time');

        $this->assertEquals(
            true,
            $changed_start_time < $current_start_time &&
            $changed_start_time < time()
        );

        $this->assertEquals(
            false,
            $this->callPrivateMethod($obj, 'inLifeTime', []),
        );

        try {
            $obj->toArray();
        } catch (\RuntimeException $e) {
            $this->assertEquals(1, 1);
        } catch (\Throwable $e) {
            $this->assertEquals(1, 0);
        }

        @session_start();
        $_SESSION = [];
        @session_write_close();
    }
}
