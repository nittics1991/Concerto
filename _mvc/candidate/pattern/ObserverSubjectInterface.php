<?php

/**
*   Observer Subject interface
*
*   @version 151215
*/

declare(strict_types=1);

namespace candidate\pattern;

interface ObserverSubjectInterface
{
    /**
    *   observerへデータ渡し
    *
    *   @return mixed データ
    */
    public function toObserver();

    /**
    *   observerからのデータ受取り
    *
    *   @param mixed $key キー
    *   @param mixed $data データ
    *   @return mixed
    */
    public function fromObserver($key, $data);
}
