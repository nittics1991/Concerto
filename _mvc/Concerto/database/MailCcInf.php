<?php

/**
*   mail_cc_inf
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\database\MailCcInfData;
use Concerto\standard\ModelDb;

class MailCcInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mail_cc_inf';

    /**
    *   ユーザ情報取得
    *
    *   @param MailCcInfData $obj
    *   @param string $order
    *   @return mixed[]
    */
    public function getUserList(
        MailCcInfData $obj,
        string $order = ''
    ): array {
        $sql = "
            SELECT A.*, B.* 
            FROM {$this->name} A 
            LEFT JOIN public.mst_tanto B 
                ON B.cd_tanto = A.cd_tanto 
            WHERE 1 = 1 
        ";

        foreach ($obj->toArray() as $key => $val) {
            if (!is_null($val)) {
                $sql .= "AND {$key} = :{$key} ";
            }
        }

        if (is_string($order) && (mb_strlen($order) > 0)) {
            $sql .= "ORDER BY {$order}";
        }

        $stmt = $this->pdo->prepare($sql);

        $this->bind($stmt, $obj);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }


    /**
    *   no_seq最大値取得
    *
    *   @param string $cd_system
    *   @param ?string $cd_type
    *   @return ?int
    */
    public function getMaxNoSeq(
        string $cd_system,
        ?string $cd_type
    ): ?int {
        $sql = "
            SELECT MAX(no_seq) AS no_seq
            FROM {$this->schema}
            WHERE cd_type = :type
                AND cd_system = :system
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':type', $cd_type, PDO::PARAM_STR);
        $stmt->bindValue(':system', $cd_system, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? null : (int)$result;
    }
}
