<?php

/**
*   jyukyu_inf
*
*   @version 240823
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use RuntimeException;
use Concerto\standard\ModelDb;

class JyukyuInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.jyukyu_inf';

    /**
    *   新規採番
    *
    *   @param string $code
    *   @return string
    */
    public function generateNo(
        string $code
    ): string {
        $code = mb_substr($code, 0, 3);

        if (mb_strlen($code) !== 3) {
            throw new RuntimeException(
                "argv length must be 3 characters:{$code}"
            );
        }

        $sql = "
            SELECT MAX(no_jyukyu)
            FROM {$this->schema}
            WHERE no_jyukyu LIKE SUBSTR(:code, 1, 4) || '%'
            GROUP BY SUBSTR(no_jyukyu, 1, 4)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':code', "J{$code}", PDO::PARAM_STR);
        $stmt->execute();
        $no_jyukyu = $stmt->fetchColumn();

        if (empty($no_jyukyu)) {
            return "J{$code}00001";
        }

        $no = (int)mb_substr((string)$no_jyukyu, 5) + 1;
        return "J{$code}" . sprintf('%05s', $no);
    }

    /**
    *   年度リスト
    *
    *   @return mixed[]
    */
    public function getNendoList(): array
    {
        $sql = "
            WITH nengetu AS (
                SELECT SUBSTR(dt_pjyukyu, 1, 4) AS yyyy
                    , SUBSTR(dt_pjyukyu, 5, 2) AS mm
                FROM public.jyukyu_inf
            ), nendo AS (
                SELECT CASE
                    WHEN mm >= '04' AND mm <= '09' THEN yyyy || 'K'
                    ELSE yyyy || 'S' END AS kb_nendo
                FROM nengetu
            )
            SELECT DISTINCT kb_nendo
            FROM nendo
            ORDER BY kb_nendo DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
