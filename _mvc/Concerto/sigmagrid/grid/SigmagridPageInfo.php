<?php

/**
*   Sigmagrid PageInfo
*
*   @version 210608
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use InvalidArgumentException;
use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridPageInfo extends DataContainerValidatable
{
    /**
    *   Columns
    *
    *   @var string[]
    */
    protected static $schema = [
        'pageSize', 'pageNum', 'totalRowNum',
        'totalPageNum', 'startRowNum', 'endRowNum'
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(array $params = [])
    {
        $this->fromArray($params);
    }

    /**
    *   全データ数で再生成
    *
    *   @param int $totalRowNum
    *   @return self
    */
    public function rebuildByTotalRowNumber(int $totalRowNum)
    {
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

    public function isValidPageSize($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidPageNum($val)
    {
        return Validate::isInt($val, 1);
    }

    public function isValidTotalPageNum($val)
    {
        return Validate::isInt($val, -1);
    }

    public function isValidStartPageNum($val)
    {
        return Validate::isInt($val, 0);
    }

    public function isValidEndPageNum($val)
    {
        return Validate::isInt($val, -1);
    }
}
