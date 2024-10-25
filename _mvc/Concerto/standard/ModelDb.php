<?php

/**
*   ModelDb
*
*   @version 230926
*/

declare(strict_types=1);

namespace Concerto\standard;

use DateTimeInterface;
use LogicException;
use PDO;
use PDOStatement;
use RuntimeException;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
    ModelData,
};
use Concerto\standard\modeldbs\{
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
    *   @var string
    */
    protected string $schema = 'public';

    /**
    *   @var PDO
    */
    protected PDO $pdo;

    /**
    *   @var string
    */
    protected string $name;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(
        PDO $pdo
    ) {
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
    */
    protected function errorInfoToMessage(
        PDO|PDOStatement|null $obj = null
    ): string {
        if (!isset($obj)) {
            return '';
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
    *   bind
    *
    *   @param PDOStatement $stmt
    *   @param DataModelInterface $obj
    *   @param string $prefix
    *   @return mixed[]
    */
    protected function bind(
        PDOStatement $stmt,
        DataModelInterface $obj,
        string $prefix = ':'
    ): array {
        $data = $obj->toArray();
        $result = [];

        if (!empty($data)) {
            $schema = (array)$obj->getInfo();

            //bindParamは参照渡しの為、バインドする値のアドレス渡しとする
            foreach ($data as $key => &$val) {
                if (!is_null($val)) {
                    $type = $this->convertPdoParam($schema[$key]);

                    //PDO::PARAM_BOOLでは't','f'となるので使わない
                    if ($schema[$key] === 'boolean') {
                        $val = ($val) ?  '1' : '0';
                    } elseif ($schema[$key] === 'datetime') {
                        $val = is_object($val) &&
                            $val instanceof DateTimeInterface ?
                            $val->format('Ymd His') :
                            print_r($val, true);
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
    *   @param string $key
    *   @return int
    */
    protected function convertPdoParam(
        string $key
    ): int {
        switch ($key) {
            case 'integer':
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
        $class = get_called_class() . 'Data';

        if (!class_exists($class)) {
            throw new LogicException(
                "not defined:{$class}"
            );
        }

        return $class;
    }

    /**
    *   Entityクラス生成
    *
    *   @return DataModelInterface
    */
    public function createModel(): DataModelInterface
    {
        $namespace = $this->entityName();
        $object = new $namespace();

        if (!$object instanceof DataModelInterface) {
            throw new LogicException(
                "must be DataModelInterface:{$namespace}"
            );
        }

        return $object;
    }
}
