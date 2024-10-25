<?php

declare(strict_types=1);

namespace dev_test\standard\argv;

use dev\standard\Argv;

require_once(__DIR__ . '\\..\\..\\bootstrap.php');

class Argv1 extends Argv
{
    protected static $definedShortOptions = ['o::', 'm:', 'f'];
    protected static $definedLongOptions = ['user:', 'email::', 'mark'];
    protected static $definedOptindOptions = ['text', 'meaningless::', 'necessary:'];

    // protected static $definedShortOptions = ['a:', 'b:',];
    // protected static $definedOptindOptions = ['test:', ];

    public function isValidM($val)
    {
        return isset($val);
    }
}

$obj = new Argv1();

// var_dump($obj->getInfo());echo "<hr>";
var_dump($obj->toArray());
echo "<hr>";
