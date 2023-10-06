<?php

/**
*   Raw Data Container
*
*   @ver 210813
**/

declare(strict_types=1);

namespace Concerto\container\rawdata;

interface RawDataContainerInterface
{
    /**
    *   raw
    *
    *   @param string $id
    *   @param mixed $concrete
    **/
    public function raw($id, $concrete);
}
