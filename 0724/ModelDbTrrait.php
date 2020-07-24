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
    *   バインド
    *
    *   @param PDOStatement $stmt
    *   @param DataModelInterface $params バインドデータ
    */
    protected function bind(
        PDOStatement $stmt,
        DataModelInterface $params
    ): array {
        $prop_types = $params->getInfo();
        
        foreach($params as $prop_name => &$val) {
            if (!is_null($val)) {
                $pdo_type = $this->toPdoParamType($prop_types[$prop_name]);
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
            case 'integer':
                return PDO::PARAM_INT;
            default:
                return PDO::PARAM_STR;
        }
    }
    
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
    
}
