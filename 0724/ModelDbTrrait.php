<?php

/**
*   ModelDb Trait
*
*   @version 200724
*/

declare(strict_types=1);

namespace Concerto\sql;

use PDO;
use Concerto\standard\DataModelInterface;


    use finfo;
    use InvalidArgumentException;
    use RuntimeException;
    use PDOStatement;
    use Concerto\standard\DataMapperInterface;


trait ModelDbTrait
{
    /**
    *   table_name
    *
    *   @var string
    */
    protected string $table_name = '';
    
    /**
    *   PDO
    *
    *   @var PDO
    */
    protected PDO $pdo;
    
    /**
    *   DataModelInterfaceオブジェクト生成
    *
    *   @return string
    */
    public static function createModel()
    {
        $namespace = get_called_class() . 'Data';
        return new $namespace();
    }
    
    /**
    *   バインド
    *
    *   @param PDOStatement $stmt
    *   @param array $params バインドデータ
    */
    protected function bind(
        PDOStatement $stmt,
        array $params
    ): array {
        foreach($params as $prop_name => &$val) {
            if (!is_null($val)) {
                $pdo_type = $this->toPdoParamType(gettype($val));
                $stmt->bindParam(":{$prop_name}", $val, $type);
            }
        }
        unset($val);
    }
    
    /**
    *   ModelDataからバインドタイプ取得
    *
    *   @param string $key プロパティ名
    *   @return int
    */
    protected function toPdoParamType(string $type): int
    {
        switch ($type) {
            case 'NULL':
                return PDO::PARAM_NULL;
            case 'integer':
                return PDO::PARAM_INT;
            default:
                return PDO::PARAM_STR;
        }
    }
}
