<?php

/**
*   keiji_inf
*
*   @version 210118
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class KeijiInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.keiji_inf';

    /**
    *   新規採番
    *
    *   @return string
    */
    public function generateNo()
    {
        $sql = "
            SELECT MAX(no_keiji) AS no_keiji
            FROM {$this->schema}
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $no_keiji = $stmt->fetchColumn();
        return $no_keiji + 1;
    }

    /**
    *   有効期限内リスト
    *
    *   @param string $order
    *   @return array
    */
    public function getYukoList($order = 'up_date DESC')
    {
        $sql = "
            SELECT *
            FROM {$this->schema}
            WHERE dt_kigen = ''
                OR dt_kigen >= :kigen
            ORDER BY {$order}
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':kigen', date('Ymd'), PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }
}
