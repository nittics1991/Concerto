<?php

declare(strict_types=1);

namespace Concerto\test;

use PHPUnit\Framework\TestCase;
use Concerto\test\PrivateTestTrait;
use Prophecy\PhpUnit\ProphecyTrait;

class ConcertoTestCase extends TestCase
{
    use PrivateTestTrait;
    use ProphecyTrait;
}
