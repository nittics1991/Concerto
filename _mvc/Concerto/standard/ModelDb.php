<?php

/**
*   ModelDb
*
*   @version 220126
*/

declare(strict_types=1);

namespace Concerto\standard;

use PDO;
use PDOStatement;
use RuntimeException;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
};
use Concerto\standard\modeldb\{
    ModelDbCommandTrait,
    ModelDbExtensionCommandTrait,
    ModelDbQueryTrait,
};

class ModelDb implements DataMapperInterface
{
    use ModelDbCommandTrait;
    use ModelDbExtensionCommandTrait;
    use ModelDbQueryTrait;

    /**
    *   スキーマ名
    *
    *   @var string
    */
    protected $schema = 'public';

    /**
    *   データベース
    *
    *   @var PDO
    */
    protected $pdo;

    /**
    *   テーブル名
    *
    *   @var string
    */
    protected $name;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @throws RuntimeException
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;

        if (
            $this->pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            ) === false
        ) {
            $message = $this->errorInfoToMessage($this->pdo);
            throw new RuntimeException(
                "pdo set error mode/exception error:{$message}"
            );
        }
        $this->name = $this->schema;
    }

    /**
    *   getSchema
    *
    *   @return string
    */
    public function getSchema(): string
    {
        return $this->schema;
    }

    /**
    *   error info to string
    *
    *   @param PDO|PDOStatement|null $obj
    *   @return string
    *   @throws RuntimeException
    */
    protected function errorInfoToMessage(
        $obj = null
    ): string {
        if (!isset($obj)) {
            return '';
        }

        if (
            !($obj instanceof PDO) &&
            !($obj instanceof PDOStatement)
        ) {
            throw new RuntimeException(
                "require PDO|PDOStatement"
            );
        }
        $info = $obj->errorInfo();
        return implode('/', $info);
    }

    /**
    *   動作設定
    *
    *   @param PDOStatement $stmt
    *   @param string $class_name
    *   @return PDOStatement
    *   @throws RuntimeException
    */
    protected function decorate(
        PDOStatement $stmt,
        string $class_name
    ): PDOStatement {
        if (
            $stmt->setFetchMode(
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE,
                $class_name
            ) === false
        ) {
            $message = $this->errorInfoToMessage($stmt);
            throw new RuntimeException(
                "pdo set emulate parameters error:{$message}"
            );
        }
        return $stmt;
    }

    /**
    *   バインド
    *
    *   @param PDOStatement $stmt
    *   @param DataModelInterface $obj バインドデータ
    *   @param string $prefix パラメータ名prefix
    *   @return mixed[] 結果
    */
    protected function bind(
        PDOStatement $stmt,
        DataModelInterface $obj,
        string $prefix = ':'
    ): array {
        $data = $obj->toArray();
        $result = [];

        if (!empty($data)) {
            $schema = $obj->getInfo();

            //bindParamは参照渡しの為、バインドする値のアドレス渡しとする
            foreach ($data as $key => &$val) {
                if (!is_null($val)) {
                    $type = $this->convertPdoParam($schema[$key]);

                    //PDO::PARAM_BOOLでは't','f'となるので使わない
                    if ($schema[$key] == 'boolean') {
                        $val = ($val) ?  '1' : '0';
                    } elseif ($schema[$key] == 'datetime') {
                        $val = $val->format('Ymd His');
                    }

                    $stmt->bindParam($prefix . $key, $val, $type);
                    $result[$prefix . $key] = $val;
                }
            }
            //foreach参照渡しをリセットする
            unset($val);
        }
        return $result;
    }

    /**
    *   ModelDataからバインドタイプ取得
    *
    *   @param string $key プロパティ名
    *   @return int
    */
    protected function convertPdoParam(
        string $key
    ): int {
        switch ($key) {
            case 'integer':
            // case 'double':
                return PDO::PARAM_INT;
            default:
                return PDO::PARAM_STR;
        }
    }

    /**
    *   DataMapper Entityクラス名取得
    *
    *   @return string
    */
    public function entityName(): string
    {
        return get_called_class() . 'Data';
    }

    /**
    *   Entityクラス生成
    *
    *   @return DataModelInterface
    */
    public function createModel(): DataModelInterface
    {
        $namespace = $this->entityName();
        return new $namespace();
    }
}
