<?php

/**
*   RequestHandler
*
*   @version 200520
*/

declare(strict_types=1);

namespace Concerto\task\curl;

use Concerto\task\curl\RequestData;
use Concerto\task\curl\ResponseData;

class RequestHandler
{
    /**
    *   送信
    *
    *   @param RequestData $requestData
    *   @return ResponseData
    */
    public function send(RequestData $requestData): ResponseData
    {
        $handle = $requestData->getHandle();
        $result = curl_exec($handle);

        return new ResponseData(
            $requestData,
            is_bool($result) ? '' : $result
        );
    }
}
