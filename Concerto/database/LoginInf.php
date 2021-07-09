<?php

/**
*   login_inf
*
*   @version @version
*/

declare(strict_types=1);

namespace Concerto\database;

use InvalidArgumentException;
use PDO;
use RangeException;
use Concerto\standard\ModelDb;

class LoginInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.login_inf';

    /**
    *   指定日数前データ削除
    *
    *   @param int $days 日数
    *   @return bool 結果
    *   @throws RangeException
    */
    public function deletePastDate($days = 7)
    {
        if (!is_int($days)) {
            throw new InvalidArgumentException("invalid type {$days}");
        } elseif ($days < 0) {
            throw new RangeException("less than the lower limit {$days}");
        }

        $date = date('Ymd His', strtotime("-{$days} day"));

        $sql = "DELETE FROM {$this->schema} WHERE ins_date < :ins_date";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ins_date', $date, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
