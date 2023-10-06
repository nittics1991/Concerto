<?php

/**
*   SessionCache online test
*
*   @version 190513
*/

declare(strict_types=1);

namespace test\Concerto\cache\online;

use Concerto\cache\SessionCache;

require(__DIR__ . '/../../bootstrap.php');

$result = (new SessionCacheCkecker())();
var_dump($result);

class SessionCacheCkecker
{
    private $namespace = 'test';
    private $keyName = 'counter';

    public function __invoke()
    {
        $cls = new \ReflectionClass(SessionCacheCkecker::class);
        $methods = $cls->getMethods();

        foreach ($methods as $method) {
            $exculudes = ['__invoke', 'init', 'clean', 'getSession'];
            $methodName = $method->getName();

            if (in_array($methodName, $exculudes)) {
                continue;
            }
            $result[$methodName] = $method->invoke($this);
            $errors[$methodName] = array_reduce(
                (array)$result[$methodName],
                function ($carry, $item) {
                    return !$item ? ++$carry : $carry;
                },
                0
            );
        }
        return $result + ['__errors' => $errors];
    }

    private function clean(): void
    {
        @session_write_close();
        @session_start();
        unset($_SESSION[$this->namespace]);
        @session_write_close();
    }

    private function init(bool $doClean = true): SessionCache
    {
        if ($doClean) {
            $this->clean();
        }
        return new SessionCache($this->namespace);
    }

    private function getSession()
    {
        @session_start();
        return $_SESSION[$this->namespace][$this->keyName];
    }

    /**
    *
    */
    public function test1()
    {
        $obj = $this->init();

        $result['has.null'] = false === $obj->has($this->keyName);
        $result['get.isNull'] = null === $obj->get($this->keyName);
        $result['get.default'] = 123 === $obj->get($this->keyName, 123);

        $obj->set($this->keyName, 987);
        $result['get.setNo'] = 987 === $obj->get($this->keyName, 123);
        $result['get.setNo.session'] = $this->getSession() === $obj->get($this->keyName, 123);
        $result['has.setNo'] = true === $obj->has($this->keyName);

        $obj->delete($this->keyName);
        $result['has.deleteNo'] = false === $obj->has($this->keyName);
        $result['get.deleteNo'] = null === $obj->get($this->keyName);
        $result['get.deleteNo.default'] = 123 === $obj->get($this->keyName, 123);
        $result['get.deleteNo.Cookie'] = $_SESSION[$this->namespace] === [];

        return $result;
    }

    /**
    *
    */
    public function test2()
    {
        $obj = $this->init();

        $result['has.null'] = false === $obj->has($this->keyName);

        $actual = [
            'aaa' => 123,
            'bbb' => 456,
            'ccc' => 789,
        ];

        $obj->setMultiple($actual);
        $result['has.setMultiple'] = $actual === $obj->getMultiple(array_keys($actual));
        $result['has.setMultiple.session'] = ['aaa' => 123, 'bbb' => 456, 'ccc' => 789,] ===
            $obj->getMultiple(array_keys($actual));

        $obj->deleteMultiple(['aaa', 'ccc']);
        $result['has.deleteMultiple'] = ['aaa' => null, 'bbb' => 456, 'ccc' => null] ===
            $obj->getMultiple(array_keys($actual));
        $result['has.deleteMultiple.session'] = ['aaa' => null, 'bbb' => 456, 'ccc' => null,] ===
            $obj->getMultiple(array_keys($actual));

        $obj->clear();
        $result['has.clear'] = ['aaa' => null, 'bbb' => null, 'ccc' => null] ===
            $obj->getMultiple(array_keys($actual));
        $result['has.clear.session'] = ['aaa' => null, 'bbb' => null, 'ccc' => null,] ===
            $obj->getMultiple(array_keys($actual));

        return $result;
    }
}
