<?php

/**
*   mst_tanto
*
*   @version 221215
*/

declare(strict_types=1);

namespace Concerto\database;

use Exception;
use InvalidArgumentException;
use PDO;
use Concerto\mbstring\MbConvert;
use Concerto\standard\ModelDb;

class MstTanto extends ModelDb
{
    /**
    *   @inheritDoc
    */
    protected string $schema = 'public.mst_tanto';

    /**
    *   現在担当リスト
    *
    *   @param ?string $cd_bumon
    *   @return mixed[]
    */
    public function getTantoList(
        ?string $cd_bumon = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND cd_bumon = :bumon
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   指定部門優先担当リスト
    *
    *   @param ?string $cd_bumon
    *   @return string[][] [[cd_tanto, nm_tanto],...]
    */
    public function getTantoListPriotityBumon(
        ?string $cd_bumon = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt1;

        /**
        *   @var ?\PDOStatement
        */
        static $stmt2;

        if (is_null($stmt1)) {
            $sql = "
                SELECT cd_tanto
                    , nm_tanto 
                FROM {$this->schema} 
                WHERE cd_bumon != '' 
                ORDER BY disp_seq 
            ";

            $stmt1 = $this->pdo->prepare($sql);
        }

        $stmt1->execute();
        $list1 = (array)$stmt1->fetchAll();

        if (is_null($cd_bumon)) {
            return $list1;
        } else {
            if (is_null($stmt2)) {
                $sql = "
                    SELECT cd_tanto
                        , nm_tanto 
                    FROM {$this->schema} 
                    WHERE cd_bumon != '' 
                        AND cd_bumon = :bumon
                    ORDER BY disp_seq 
                ";

                $stmt2 = $this->pdo->prepare($sql);
            }

            $stmt2->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
            $stmt2->execute();
            $list2 = (array)$stmt2->fetchAll();

            return array_merge($list2, $list1);
        }
    }

    /**
    *   指定部門担当リスト
    *
    *   @param ?string $cd_bumon
    *   @return mixed[] [[cd_tanto, nm_tanto],...]
    */
    public function getTantoListSpecifyBumon(
        ?string $cd_bumon = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT cd_tanto
                    , nm_tanto 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND cd_bumon = :bumon
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   指定部門担当－統一ユーザIDリスト
    *
    *   @param ?string $cd_bumon
    *   @return mixed[] [[cd_tanto, cd_user],...]
    */
    public function getTantoIdListSpecifyBumon(
        ?string $cd_bumon = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt;

        if (is_null($stmt)) {
            $sql = "
                SELECT cd_tanto
                    , username AS cd_user 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND cd_bumon = :bumon
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";

            $stmt = $this->pdo->prepare($sql);
        }

        $stmt->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }


    /**
    *   メールリスト
    *
    *   @param ?string $cd_bumon
    *   @return mixed[]
    */
    public function getMailList(
        ?string $cd_bumon = null
    ): array {
        /**
        *   @var ?\PDOStatement
        */
        static $stmt1;

        /**
        *   @var ?\PDOStatement
        */
        static $stmt2;

        if (is_null($stmt1)) {
            $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (cd_bumon != '' 
                    AND fg_mail = '1'
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";

            $stmt1 = $this->pdo->prepare($sql);
        }

        $stmt1->execute();
        $list1 = (array)$stmt1->fetchAll();

        if (is_null($cd_bumon)) {
            return $list1;
        } else {
            if (is_null($stmt2)) {
                $sql = "
                SELECT * 
                FROM {$this->schema} 
                WHERE (cd_bumon = :bumon 
                    AND fg_mail = '1'
                    ) IS NOT FALSE 
                ORDER BY disp_seq 
            ";

                $stmt2 = $this->pdo->prepare($sql);
            }

            $stmt2->bindValue(':bumon', $cd_bumon, PDO::PARAM_STR);
            $stmt2->execute();
            $list2 = (array)$stmt2->fetchAll();

            return array_merge($list2, $list1);
        }
    }

    /**
    *   updateNewUserDispSeq
    *
    *   @return void
    */
    public function updateNewUserDispSeq(): void
    {
        $sql = "
            UPDATE public.mst_tanto
            SET disp_seq = :kana
            WHERE cd_tanto = :tanto
        ";

        $stmt = $this->pdo->prepare($sql);

        foreach ($this->findNewDispSeqUsers() as $list) {
            $splited1 = mb_split('@', $list['mail_add']);

            if ($splited1 === false || !isset($splited1[0])) {
                continue;
            }

            $splited2 = mb_split('\.', $splited1[0]);

            if ($splited2 === false || !isset($splited2[1])) {
                continue;
            }

            //mail addressの名前がローマ字以外いる
            try {
                $nm_kana = MbConvert::roma2kana(
                    implode(
                        ' ',
                        array_reverse($splited2)
                    )
                );

                $stmt->bindvalue(
                    ':kana',
                    $nm_kana,
                    PDO::PARAM_STR
                );

                $stmt->bindvalue(
                    ':tanto',
                    $list['cd_tanto'],
                    PDO::PARAM_STR
                );

                $stmt->execute();
            } catch (Exception $e) {
                //continue
            }
        }
    }

    /**
    *   findNewDispSeqUsers
    *
    *   @return mixed[]
    */
    public function findNewDispSeqUsers(): array
    {
        $sql = "
            SELECT *
            FROM public.mst_tanto
            WHERE disp_seq = ''
                AND mail_add != ''
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   updateNewUserPassword
    *
    *   @return void
    */
    public function updateNewUserPassword(): void
    {
        $sql = "
            UPDATE public.mst_tanto
            SET cd_hash = :hash
                , dt_hash = :date
            WHERE cd_tanto = :tanto
        ";

        $stmt = $this->pdo->prepare($sql);

        $date = date('Ymd');

        foreach ($this->findNewUserPasswordUsers() as $list) {
            $cd_hash = $this->generateUserPassword(
                $list['mail_add']
            );

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindvalue(':hash', $cd_hash, PDO::PARAM_STR);
            $stmt->bindvalue(':date', $date, PDO::PARAM_STR);
            $stmt->bindvalue(
                ':tanto',
                $list['cd_tanto'],
                PDO::PARAM_STR
            );

            $stmt->execute();
        }
    }

    /**
    *   findNewUserPasswordUsers
    *
    *   @return mixed[]
    */
    public function findNewUserPasswordUsers(): array
    {
        $sql = "
            SELECT *
            FROM public.mst_tanto
            WHERE dt_hash = ''
                AND mail_add != ''
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return (array)$stmt->fetchAll();
    }

    /**
    *   generateUserPassword
    *
    *   @param string $mail_address
    *   @return string
    */
    public function generateUserPassword(
        string $mail_address
    ): string {
        if (mb_strlen($mail_address) < 12) {
            throw new InvalidArgumentException(
                "few string length:{$mail_address}"
            );
        }

        return password_hash(
            ':' . mb_substr($mail_address, 0, 10),
            PASSWORD_DEFAULT
        );
    }
}
