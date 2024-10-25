<?php

declare(strict_types=1);

namespace test\Concerto;

use PHPUnit\Framework\TestCase;
use test\Concerto\PrivateTestTrait;
// use Prophecy\PhpUnit\ProphecyTrait;
use test\Concerto\ConcertoProphecyTrait;

class ConcertoTestCase extends TestCase
{
    use PrivateTestTrait;
    // use ProphecyTrait;
    use ConcertoProphecyTrait;
}
