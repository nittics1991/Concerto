<?php

// namespace test3;

use Concerto\cache\MemcacheCache;

function report($name, $result)
{
    if (false === $result) {
        echo "ERROR={$name}\n";
    }
}

///////////////////////////////////

$obj = new MemcacheCache();
$id = 'xxx';

$result = $obj->has($id) === false;
report('has.false', $result);

$result = $obj->get($id) === null;
report('get.null', $result);

$result = $obj->get($id, 123) === 123;
report('get.default', $result);

$result = $obj->set($id, 999, 10) === true;
report('set.true', $result);

$result = $obj->has($id) === true;
report('has.true', $result);

$result = $obj->get($id, 123) === 999;
report('get.999', $result);

$result = $obj->delete($id) === true;
report('delete.true', $result);

$result = $obj->get($id) === null;
report('get.null', $result);

$keys = ['aaa', 'bbb', 'ccc'];
$values = ['aaa' => 123, 'bbb' => 456, 'ccc' => 789];
$nulls = ['aaa' => null, 'bbb' => null, 'ccc' => null];
$deletes = ['bbb', 'ccc'];
$deleteds = ['aaa' => 123, 'bbb' => null, 'ccc' => null];

$result = $obj->getMultiple($keys) === $nulls;
report('getMultiple.null', $result);

$result = $obj->setMultiple($values, 5) === true;
report('setMultiple.true', $result);

$result = $obj->getMultiple($keys) === $values;
report('getMultiple.values', $result);

$result = $obj->deleteMultiple($deletes) === true;
report('deleteMultiple.true', $result);

$result = $obj->getMultiple($keys) === $deleteds;
report('deleteds.ok', $result);

$result = $obj->clear() === true;
report('clear.true', $result);

$result = $obj->getMultiple($keys) === $nulls;
report('clear.null', $result);
