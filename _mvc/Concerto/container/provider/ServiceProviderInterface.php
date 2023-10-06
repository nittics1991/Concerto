<?php

/**
*   ServiceProviderInterface
*
*   @version 220122
*   @see https://github.com/ecfectus/container
*/

declare(strict_types=1);

namespace Concerto\container\provider;

use Concerto\container\ContainerAwareInterface;

interface ServiceProviderInterface extends ContainerAwareInterface
{
    /**
    *   サービス名有無またはサービス名取得
    *
    *   @param ?string $service
    *   @return mixed
    *   @example $service == nullの場合、全サービス配列
    */
    public function provides(
        ?string $service
    ): mixed;

    /**
    *   サービスコンテナ登録
    */
    public function register();
}
