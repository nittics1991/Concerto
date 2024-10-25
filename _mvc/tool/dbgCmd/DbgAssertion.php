<?php

/**
*   CLIデバッグヘルパAsserion
*   phpunitのAssertionをラップし、var_dumpする
*
*   @version 240821
*/

declare(strict_types=1);

namespace tool\dbgCmd;

use stdClass;

class DbgAssertion
{
    public function __call($name, $arguments)
    {
        return self::__callStatic($name, $arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $info = new stdClass();

        $info->name = $name;

        $info->arguments = $arguments;

        $trace = debug_backtrace(0);

        if (isset($trace[2])) {
            $info->trace = $trace[2];
            $info->trace['file'] = $trace[1]['file'];
            $info->trace['line'] = $trace[1]['line'];
        }

        echo PHP_EOL;

        var_dump($info);

        echo PHP_EOL;
    }
}
