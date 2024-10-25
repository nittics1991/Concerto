<?php

/**
*   login_inf
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use DateInterval;
use DateTimeImmutable;
use PDO;
use InvalidArgumentException;
use RangeException;
use Concerto\standard\ModelDb;

class LoginInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.login_inf';

    /**
    *   指定日数前データ削除
    *
    *   @param int $days
    *   @return bool
    */
    public function deletePastDate(
        int $days = 7
    ): bool {
        if (!is_int($days)) {
            throw new InvalidArgumentException(
                "invalid type {$days}"
            );
        }

        if ($days < 0) {
            throw new RangeException(
                "less than the lower limit {$days}"
            );
        }

        $date = (new DateTimeImmutable('today'))
            ->sub(new DateInterval("P{$days}D"))
            ->format('Ymd His');

        $sql = "
            DELETE FROM {$this->schema} 
            WHERE ins_date < :ins_date
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':ins_date', $date, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
