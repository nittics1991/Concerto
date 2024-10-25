<?php

declare(strict_types=1);

require_once(__DIR__ . '/../../../_bootstrap/paths.php');

require_once(__DIR__ . '/../../../_bootstrap/autoload.php');

// PHPUnitのassertionメソッドを自作のメソッドに置き換える
class_alias(
    'tool\dbgCmd\DbgAssertion',
    'PHPUnit\Framework\Assert',
);
