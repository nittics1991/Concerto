<?php

/**
*   NullRemovePdo
*
*   @version 230406
*
*   @syntax
*       sql prepare key=>" @@@ :string @@@ "
*       bind params [int|string, => mixed,...]
*
*   @exapmle
*       $sql="SELECT * FROM foo WHERE @@@ aaa = :aaa @@@
*           AND @@@ bbb = :bbb"
*           AND ccc IN ( @@@ :0 @@@ @@@ ,:1 @@@ )
*           //:aaa�y�� :0 && :1 ����null �͊��ҊO�̓���
*
*   @caution
*       null�����e����SQL�ł͎g�p���Ȃ�
*       �S�Ă�bind�l��enclosure�ň͂������D�܂���
*       sql����enclosure�̑O���\s�����鎖���D�܂���
*       null�l��sql syntaxn�̐擪�Ɂu--�v��}������prepare����
*       prepare key��closure�̑O���\s
*       �u--�v��}���������ʂ��l������sql���K�v
**/

declare(strict_types=1);

namespace candidate\sql\nullRemovePdo;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use Throwable;
use candidate\sql\nullRemovePdo\NullRemovePdoException;

class NullRemovePdo
{
    /**
    *  @var PDO
    **/
    private PDO $pdo;

    /**
    *  @var string
    **/
    private string $enclosure;

    /**
    *  @var string
    **/
    private string $sql;

    /**
    *  @var mixed[]
    **/
    private array $params = [];

    /**
    *  @var string
    **/
    private string $nullSafeSql;

    /**
    *  @var PDOStatement
    **/
    private PDOStatement $statement;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param string $enclosure
    **/
    public function __construct(
        PDO $pdo,
        string $enclosure = '@@@',
    ) {
        $this->pdo = $pdo;

        if (empty(trim($enclosure))) {
            throw new InvalidArgumentException(
                "trim(enclosure) must be length > 0"
            );
        }

        $this->enclosure = $enclosure;

        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION,
        );
    }

    /**
    *   connection
    *
    *   @param PDO $pdo
    *   @return self
    **/
    public static function connection(
        PDO $pdo,
    ): self {
        return new self($pdo);
    }

    /**
    *   sql
    *
    *   @param string $sql prepare key=>" @@@ :name @@@ "
    *   @return self
    **/
    public function sql(
        string $sql,
    ): self {
        $this->sql = $sql;
        return $this;
    }

    /**
    *   bind
    *
    *   @param mixed[] $params [int|string => value,...]
    *   @return self
    **/
    public function bind(
        array $params,
    ): self {
        $this->params = $params;
        return $this;
    }

    /**
    *   execute
    *
    *   @return self
    **/
    public function execute(): self
    {
        $this->nullSafeSql = $this->buildSql();

        $this->statement = $this->pdo
            ->prepare($this->nullSafeSql);

        try {
            $this->bindParams();

            $this->statement->execute();
        } catch (Throwable $e) {
            throw NullRemovePdoException::create(
                $e,
                $this,
            );
        }

        return $this;
    }

    /**
    *   buildSql
    *
    *   @return string
    **/
    private function buildSql(): string
    {
        $sqls = explode($this->enclosure, $this->sql);

        foreach ($this->params as $name => $value) {
            if (is_null($value)) {
                $sqls = array_map(
                    function ($sql) use ($name) {
                        return mb_ereg_match(
                            '.*(\s+|\(|,):' . $name . '(\s*|\)|,)',
                            $sql,
                        ) ? "---{$sql}\n" : $sql;
                    },
                    $sqls,
                );
            }
        }

        return implode(' ', $sqls);
    }

    /**
    *   bindParams
    *
    *   @return void
    **/
    private function bindParams(): void
    {
        foreach ($this->params as $name => $value) {
            if (is_null($value)) {
                //nop
            } elseif (!is_int($value)) {
                $this->statement->bindValue(
                    ":{$name}",
                    $value,
                    PDO::PARAM_STR,
                );
            } else {
                $this->statement->bindValue(
                    ":{$name}",
                    $value,
                    PDO::PARAM_INT,
                );
            }
        }
    }

    /**
    *   fetchAll
    *
    *   @return mixed[]
    **/
    public function fetchAll(): array
    {
        return $this->statement->fetchAll();
    }

    /**
    *   statement
    *
    *   @return PDOStatement
    **/
    public function statement(): PDOStatement
    {
        return $this->statement;
    }
}
