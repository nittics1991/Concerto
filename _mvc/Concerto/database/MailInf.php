<?php

/**
*   mail_inf
*
*   @version 231207
*/

declare(strict_types=1);

namespace Concerto\database;

use PDO;
use PDOStatement;
use Concerto\standard\ModelDb;

class MailInf extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mail_inf';

    /**
    *   最新情報取得取得
    *
    *   @param MailInfData $obj
    *   @return mixed[]
    */
    public function getLatestData(
        MailInfData $obj
    ): array {
        $sql = "
            WITH view AS
                (SELECT * 
                FROM {$this->schema} 
                WHERE 1 = 1 
        ";

        foreach ($obj->toArray() as $key => $val) {
            if (!is_null($val)) {
                $sql .= "AND $key = :$key ";
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
        $this->bind($stmt, $obj);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   ins_date最大日取得
    *
    *   @return string
    */
    public function getMaxDate(): string
    {
        $sql = "
            SELECT MAX(ins_date) AS ins_date 
            FROM {$this->schema} 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? '' : (string)$result;
    }

    /**
    *   ins_date最小日取得
    *
    *   @return string
    */
    public function getMinDate(): string
    {
        $sql = "
            SELECT MIN(ins_date) AS ins_date 
            FROM {$this->schema} 
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchColumn(0);
        return $result === false ? '' : (string)$result;
    }

    /**
    *   承認メールの終了フラグfg_endを同期
    *
    *   @param MailInfData $mailInfData
    *   @return void
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
        $stringProperties = [
            'cd_type', 'no_cyu', 'cd_bumon',
        ];

        $intBinds = [];
        $intProperties = [
            'no_page'
        ];

        foreach ($stringProperties as $property) {
            if (isset($mailInfData->$property)) {
                $sql .= " AND {$property} = :{$property}";
                $stringBinds[":{$property}"] =
                    $mailInfData->$property;
            }
        }

        foreach ($intProperties as $property) {
            if (isset($mailInfData->$property)) {
                $sql .= " AND {$property} = :{$property}";
                $intBinds[":{$property}"] =
                    $mailInfData->$property;
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
    public function generateNewNo(
        string $nm_sequence
    ): int {
        $sql = "
            SELECT NEXTVAL(:seq)
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':seq', $nm_sequence, PDO::PARAM_STR);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
    *   ワークフロー承認メール振替(実製番昇格)
    *
    *   @param string $no_cyu_src
    *   @param string $no_cyu_target
    *   @return void
    */
    public function updateWf(
        string $no_cyu_src,
        string $no_cyu_target,
    ): void {
        $sql = "
            UPDATE public.mail_inf
            SET no_cyu = :no_cyu_target
            WHERE no_cyu = :no_cyu_src
                AND cd_type IN ('1', '4', '5', '6', '7', '8', '9', '10', '15')
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':no_cyu_src',
            $no_cyu_src,
            PDO::PARAM_STR
        );
        $stmt->bindValue(
            ':no_cyu_target',
            $no_cyu_target,
            PDO::PARAM_STR
        );

        $stmt->execute();
    }
}
