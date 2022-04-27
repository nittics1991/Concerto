<?php

/**
*   VIEW Controller
*
*   @version 180708
*/

declare(strict_types=1);

namespace cyokka_rituan2\view;

use dev\standard\ViewStandard;

class CyokkaRituanDispView extends ViewStandard
{
    /**
    *   __construct
    *
    *   @param array $data データ
    */
    public function __construct($data = [])
    {
        parent::__construct($data);

        $this->toHTML();
    }

    /**
    *   {inherit}
    *
    */
    public function __invoke(...$values): mixed
    {
        $this->render('template/cyokka_rituan_template.php');
    }
}
