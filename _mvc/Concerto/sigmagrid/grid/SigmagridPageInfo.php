<?php

/**
*   Sigmagrid PageInfo
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use InvalidArgumentException;
use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

/**
*   @extends DataContainerValidatable<int>
*/
class SigmagridPageInfo extends DataContainerValidatable
{
    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'pageSize', 'pageNum', 'totalRowNum',
        'totalPageNum', 'startRowNum', 'endRowNum'
    ];

    /**
    *   __construct
    *
    *   @param int[] $params
    */
    public function __construct(
        array $params = []
    ) {
        $this->fromArray($params);
    }

    /**
    *   全データ数で再生成
    *
    *   @param int $totalRowNum
    *   @return self
    */
    public function rebuildByTotalRowNumber(
        int $totalRowNum
    ): self {
        if ($totalRowNum < 0) {
            throw new InvalidArgumentException(
                "must be totalRowNumber >= 0"
            );
        }

        $data = $this->toArray();
        $data['totalRowNum'] = $totalRowNum;
        $data['pageNum'] = $this->pageNum ?? 1;
        $data['pageSize'] = $this->pageSize ?? -1;
        $data['startRowNum'] = $this->startRowNum ?? 1;
        $data['endRowNum'] = $this->endRowNum ?? -1;
        $data['totalPageNum'] =
            (int)($data['totalRowNum'] / $data['pageSize'])
            + (int)($data['totalRowNum'] % $data['pageSize']);

        return new self($data);
    }

    public function isValidPageSize(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidPageNum(
        mixed $val
    ): bool {
        return Validate::isInt($val, 1);
    }

    public function isValidTotalPageNum(
        mixed $val
    ): bool {
        return Validate::isInt($val, -1);
    }

    public function isValidStartPageNum(
        mixed $val
    ): bool {
        return Validate::isInt($val, 0);
    }

    public function isValidEndPageNum(
        mixed $val
    ): bool {
        return Validate::isInt($val, -1);
    }
}
