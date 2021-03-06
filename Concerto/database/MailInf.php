<?php

/**
*   mail_inf
*
*   @version 210610
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use Concerto\standard\ModelDb;

class MailInf extends ModelDb
{
    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public.mail_inf';

    /**
    *   no_seq最大値取得
    *
    *   @param MailInfData $obj
    *   @return ?int
    */
    public function getMaxNoSeq(MailInfData $obj)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        /**
        *   WHERE前回値
        *
        *   @var array
        */
        static $where_old;

        $where_key = array_keys($obj->toArray());

        if (($where_key != $where_old) || (empty($stmt))) {
            $sql = "SELECT MAX(no_seq) AS no_seq 
                    FROM {$this->schema} 
                    WHERE 1 = 1 
            ";

            if (!empty($where_key)) {
                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $sql .= "AND {$key} = :{$key} ";
                    }
                }
            }

            $stmt = $this->pdo->prepare($sql);
            $where_old = $where_key;
        }

        $this->bind($stmt, $obj);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? null : (int)$result;
    }

    /**
    *   最新情報取得取得
    *
    *   @param MailInfData $obj
    *   @return array
    */
    public function getLatestData(MailInfData $obj)
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        /**
        *   WHERE前回値
        *
        *   @var array
        */
        static $where_old;

        $where_key = array_keys($obj->toArray());

        if (($where_key != $where_old) || (empty($stmt))) {
            $sql = "
                WITH view AS
                    (SELECT * 
                    FROM {$this->schema} 
                    WHERE 1 = 1 
            ";

            if (!empty($where_key)) {
                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $sql .= "AND $key = :$key ";
                    }
                }
            }

            $sql .= "
                    )
                SELECT * 
                FROM view 
                WHERE ins_date = 
                    (SELECT MAX(ins_date) 
                    FROM view
                    )
            ";

            $stmt = $this->pdo->prepare($sql);
            $where_old = $where_key;
        }

        $this->bind($stmt, $obj);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   ins_date最大日取得
    *
    *   @return string
    */
    public function getMaxDate()
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT MAX(ins_date) AS ins_date 
                FROM {$this->schema} 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? '' : (string)$result;
    }

    /**
    *   ins_date最小日取得
    *
    *   @return string
    */
    public function getMinDate()
    {
        /**
        *   プリペア
        *
        *   @var \PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT MIN(ins_date) AS ins_date 
                FROM {$this->schema} 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? '' : (string)$result;
    }

    /**
    *   承認メールの終了フラグfg_endを同期
    *
    *   @param MailInfData $mailInfData
    */
    public function syncWfMailEndStatus(
        MailInfData $mailInfData
    ): void {
        $sql = "
            UPDATE public.mail_inf
            SET fg_end = '1'
            WHERE 1 = 1
        ";

        $stringBinds = [];
        $strongProperties = [
            'cd_type', 'no_cyu', 'no_seq', 'cd_bumon', 'kb_nendo',
        ];

        $intBinds = [];
        $intProperties = [
            'no_page'
        ];

        foreach ($strongProperties as $property) {
            if (isset($mailInfData->$property)) {
                $sql .= " AND {$property} = :{$property}";
                $stringBinds[":{$property}"] = $mailInfData->$property;
            }
        }

        foreach ($intProperties as $property) {
            if (isset($mailInfData->$property)) {
                $sql .= " AND {$property} = :{$property}";
                $intBinds[":{$property}"] = $mailInfData->$property;
            }
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($stringBinds as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_STR);
        }

        foreach ($intBinds as $key => $val) {
            $stmt->bindValue($key, $val, PDO::PARAM_INT);
        }

        $stmt->execute();
    }

    /**
    *   新番号生成
    *
    *   @param string $nm_sequence
    *   @return int
    */
    public function generateNewNo(string $nm_sequence): int
    {
        $sql = "
            SELECT NEXTVAL(:seq)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':seq', $nm_sequence, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
}
