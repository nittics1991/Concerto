<?php

/**
*   データベーステーブル
*
*   @version 210615
*/

declare(strict_types=1);

namespace Concerto\standard;

use finfo;
use InvalidArgumentException;
use PDO;
use PDOStatement;
use RuntimeException;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface
};

class ModelDb implements DataMapperInterface
{
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
    *   ORDER句許可文字
    *
    *   @var string[]
    */
    protected $order_clause = [
        'asc',
        'desc'
    ];

    /**
    *   WINDOW OVER句許可文字
    *
    *   @var string[]
    */
    protected $window_clause = [
        'asc',
        'desc',
        'as',
        'over',
        'partition',
        'order',
        'by'
    ];

    /**
    *   集約関数許可文字
    *
    *   @var string[]
    */
    protected $agg_function = [
        'array_agg',
        'avg',
        'bit_and',
        'bit_or',
        'bool_and',
        'bool_or',
        'count',
        'every',
        'json_agg',
        'max',
        'min',
        'sum',
        'xml_agg',
        'row_number',
        'rank',
        'dense_rank',
        'percent_rank',
        'cume_dist',
        'first_value',
        'last_value'
    ];

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
    protected function errorInfoToMessage($obj = null): string
    {
        if (!isset($obj)) {
            return '';
        }

        if (!($obj instanceof PDO) && !($obj instanceof PDOStatement)) {
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
    protected function convertPdoParam(string $key): int
    {
        switch ($key) {
            case 'boolean':
                return PDO::PARAM_STR;
            case 'integer':
            case 'double':
                return PDO::PARAM_INT;
            case 'datetime':
                return PDO::PARAM_STR;
            default:
                return PDO::PARAM_STR;
        }
    }

    /**
    *   SELECT
    *
    *   @param DataModelInterface $obj WHERE条件
    *   @param ?string $order ORDER句
    *   @return DataModelInterface[] 結果セットクラス
    *   @throws InvalidArgumentException
    */
    public function select(DataModelInterface $obj, ?string $order = null)
    {
        /**
        *   stmt
        *
        *   @var PDOStatement
        */
        static $stmt;

        /**
        *   WHERE前回値
        *
        *   @var string[]
        */
        static $where_old;

        /**
        *   ORDER前回値
        *
        *   @var string
        */
        static $order_old;

        if (!is_null($order) && !$this->isValidOrderClause($obj, $order)) {
            throw new InvalidArgumentException("data type error:{$order}");
        }

        $where_key = array_keys($obj->toArray());

        if ($where_key != $where_old || $order != $order_old || empty($stmt)) {
            $sql = 'SELECT * FROM ' . $this->name . ' WHERE 1 = 1 ';

            if (!empty($where_key)) {
                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $sql .= "AND {$key} = :{$key} ";
                    }
                }
            }

            if (!is_null($order)) {
                $sql .= " ORDER BY {$order}";
            }

            $stmt = $this->pdo->prepare($sql);
            $where_old = $where_key;
            $order_old = $order;
        }

        $this->bind($stmt, $obj);
        $stmt->execute();
        $stmt = $this->decorate($stmt, get_class($obj));
        return (array)$stmt->fetchAll();
    }

    /**
    *   INSERT
    *
    *   @param mixed[] $dataset 保存値 [ModelData1, ModelData2, ...]
    *   @throws InvalidArgumentException
    */
    public function insert($dataset): void
    {
        if (!is_array($dataset)) {
            throw new InvalidArgumentException("not Array");
        }

        $schema_old = [];
        $count = 0;
        $stmt = null;
        $sql = '';
        $binds = [];
        $i = 0;

        foreach ($dataset as $obj) {
            if (!$obj instanceof DataModelInterface) {
                throw new InvalidArgumentException("data type different");
            }

            if ($schema_old == array_keys($obj->toArray())) {
                $values = [];
                $i++;

                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $values[] = ":{$i}{$key}";
                    }
                }

                $sql .= sprintf(
                    " , (%s)",
                    implode(',', $values)
                );
                $binds[] = $obj;
            } else {
                if ($schema_old != []) {
                    $stmt = $this->pdo->prepare($sql);
                    $j = 0;

                    foreach ($binds as $obj1) {
                        $this->bind($stmt, $obj1, ":{$j}");
                        $j++;
                    }
                    $stmt->execute();
                }

                $fields = [];
                $values = [];
                $binds = [];
                $i = 0;

                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $fields[] = $key;
                        $values[] = ":{$i}{$key}";
                    }
                }

                $sql = sprintf(
                    "INSERT INTO %s (%s) VALUES (%s) ",
                    $this->name,
                    implode(',', $fields),
                    implode(',', $values)
                );
                $binds[] = $obj;
            }
            $schema_old = array_keys($obj->toArray());
            $count++;
        }

        if ($schema_old != []) {
            $stmt = $this->pdo->prepare($sql);
            $j = 0;

            foreach ($binds as $obj1) {
                $this->bind($stmt, $obj1, ":{$j}");
                $j++;
            }
            $stmt->execute();
        }
    }

    /**
    *   UPDATE
    *
    *   @param mixed[] $dataset [[ModelData 保存値,  ModelData 条件], ...]
    *   @throws InvalidArgumentException
    */
    public function update($dataset): void
    {
        /**
        *   プリペア
        *
        *   @var PDOStatement
        */
        static $stmt;

        /**
        *   データ前回値
        *
        *   @var string[]
        */
        static $data_old;

        /**
        *   WHERE前回値
        *
        *   @var string[]
        */
        static $where_old;

        if (!is_array($dataset)) {
            throw new InvalidArgumentException("not Array");
        }

        $count = 0;
        $stmt = null;
        $sql = '';

        foreach ($dataset as $list) {
            if (!is_array($list) || count($list) != 2) {
                throw new InvalidArgumentException(
                    "inner array not Array"
                );
            }

            $obj = $list[0];
            $where = $list[1];

            if (
                !($obj instanceof DataModelInterface) ||
                !($where instanceof DataModelInterface)
            ) {
                throw new InvalidArgumentException("data type different");
            }

            $data_key = array_keys($obj->toArray());
            $where_key = array_keys($where->toArray());

            if (
                $data_key != $data_old ||
                $where_key != $where_old ||
                empty($stmt)
            ) {
                $sql = "UPDATE {$this->name} SET ";

                foreach ($obj->toArray() as $key => $val) {
                    if (!is_null($val)) {
                        $sql .= " {$key} = :{$key},";
                    }
                }

                $sql = mb_substr($sql, 0, (mb_strlen($sql) - 1));

                if (!empty($where_key)) {
                    $sql .= ' WHERE 1 = 1 ';
                    foreach ($where->toArray() as $key => $val) {
                        if (!is_null($val)) {
                            $sql .= "AND {$key} = :_{$key} ";
                        }
                    }
                }

                $stmt = $this->pdo->prepare($sql);
                $data_old = $data_key;
                $where_old = $where_key;
            }

            $this->bind($stmt, $obj);
            $this->bind($stmt, $where, ':_');
            $stmt->execute();
            $count++;
        }
    }

    /**
    *   DELETE
    *
    *   @param mixed[] $dataset 条件 [ModelData1, ModelData2, ...]
    *   @throws InvalidArgumentException
    */
    public function delete($dataset): void
    {
        if (!is_array($dataset)) {
            throw new InvalidArgumentException("not Array");
        }

        $sql = "DELETE FROM {$this->name} ";
        $flg = false;
        $binds = [];
        $i = 0;

        foreach ($dataset as $obj) {
            if (!$obj instanceof DataModelInterface) {
                throw new InvalidArgumentException("data type different");
            }

            if (!$flg) {
                $sql .= 'WHERE ';
                $flg = true;
            } else {
                $sql .= 'OR ';
            }

            $fields = [];

            foreach ($obj->toArray() as $key => $val) {
                if (!is_null($val)) {
                    $fields[] = "{$key} = :{$i}{$key}";
                }
            }

            $sql .= sprintf("(%s) ", implode(' AND ', $fields));
            $binds[] = $obj;
            $i++;
        }

        $stmt = $this->pdo->prepare($sql);
        $j = 0;

        foreach ($binds as $obj1) {
            $this->bind($stmt, $obj1, ":{$j}");
            $j++;
        }
        $stmt->execute();
    }

    /**
    *   COPY RECORDS
    *
    *   @param DataModelInterface $where 条件
    *   @param DataModelInterface $replace 置換フィールド(指定した値で置換、他は元データコピー)
    *   @throws InvalidArgumentException
    *   @example $where(no_cyu, no_sheet)
    *                       $replace(update, editor, no_cyu, no_sheet_max)
    */
    public function copyRecord(
        DataModelInterface $where,
        DataModelInterface $replace
    ): void {
        $class_name = $this->entityName();

        if (
            !($where instanceof $class_name) ||
            !($replace instanceof $class_name)
        ) {
            throw new InvalidArgumentException(
                "data type error:{$class_name}"
            );
        }

        $replace_columns = [];
        $replace_values = [];
        $columns = [];
        $where_fields = [];

        foreach ($replace->toArray() as $key => $val) {
            $replace_columns[] = $key;
            $replace_values[] = ":{$key}";
        }

        foreach ($replace->getInfo() as $key => $val) {
            if (!in_array($key, $replace_columns)) {
                $columns[] = $key;
            }
        }

        $sql = "INSERT INTO {$this->schema} ";
        $sql .= sprintf(
            "(%s, %s) SELECT %s, %s FROM {$this->schema} ",
            implode(',', $replace_columns),
            implode(',', $columns),
            implode(',', $replace_values),
            implode(',', $columns)
        );

        foreach ($where->toArray() as $key => $val) {
            $where_fields[] = "{$key} = :where_{$key}";
        }

        if (count($where_fields)) {
            $sql .= sprintf("WHERE %s ", implode(' AND ', $where_fields));
        }

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $replace);
        $this->bind($stmt, $where, ':where_');
        $stmt->execute();
    }

    /**
    *   集約検索
    *
    *   @param string $select SELECT句(集約後のエイリアス名はカラム名と同じにする)
    *   @param DataModelInterface $where WHERE条件
    *   @param ?string $group GROUP BY句
    *   @return DataModelInterface[] 結果セット
    *   @throws InvalidArgumentException
    *   @example $where->id $group='year, month' $select='SUM(money) AS money'
    */
    public function groupBy(
        string $select,
        DataModelInterface $where,
        ?string $group = null
    ): array {
        $class_name = $this->entityName();

        if (!($where instanceof $class_name)) {
            throw new InvalidArgumentException(
                "data type error:{$class_name}"
            );
        }

        if (!is_null($group) && (!$this->isValidClause($where, $group))) {
            throw new InvalidArgumentException(
                "data type error:{$group}"
            );
        }

        if (!$this->isValidAggClause($where, $select)) {
            throw new InvalidArgumentException(
                "data type error:{$select}"
            );
        }

        $sql = "
            SELECT {$select} 
            FROM {$this->name} 
            WHERE 1 = 1 
        ";

        foreach ($where->toArray() as $key => $val) {
            if (!is_null($val)) {
                $sql .= "AND {$key} = :{$key} ";
            }
        }

        if (is_string($group)) {
            $sql .= "
                GROUP BY {$group} 
            ";
        }

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $where);
        $stmt = $this->decorate($stmt, get_class($where));
        $stmt->execute();
        return (array)$stmt->fetchAll();
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

    /**
    *   SQL句バリデーション
    *
    *   @param DataModelInterface $template 基準ModelData class
    *   @param string $clause 判定対象文字列
    *   @param string[] $haystack 許可文字列
    *   @return bool
    *   @see ModelDataのプロパティまたは許可句のみがtrue
    */
    protected function isValidClause(
        DataModelInterface $template,
        ?string $clause,
        array $haystack = []
    ) {
        if (is_null($clause)) {
            return true;
        }

        //$pattern[]表現[ ,　]で全角空白エラーする
        $ar = mb_split('( |,|　|\(|\))', mb_strtolower($clause));

        if (empty($ar)) {
            return true;
        }

        $allows = array_merge(
            ['', '　'],
            array_keys($template->getInfo()),
            $haystack
        );

        foreach ($ar as $val) {
            if (!in_array($val, $allows)) {
                return false;
            }
        }
        return true;
    }

    /**
    *   ORDER句バリデーション
    *
    *   @param DataModelInterface $template 基準ModelData class
    *   @param string $clause 判定対象文字列
    *   @return bool
    */
    protected function isValidOrderClause(
        DataModelInterface $template,
        string $clause
    ): bool {
        return $this->isValidClause(
            $template,
            $clause,
            $this->order_clause
        );
    }

    /**
    *   集約句バリデーション
    *
    *   @param DataModelInterface $template 基準ModelData class
    *   @param string $clause 判定対象文字列
    *   @return bool
    */
    protected function isValidAggClause(
        DataModelInterface $template,
        string $clause
    ): bool {
        $haystack = array_merge(
            $this->window_clause,
            $this->agg_function
        );
        return $this->isValidClause($template, $clause, $haystack);
    }

    /**
    *   TRUNCATE
    *
    */
    public function truncate(): void
    {
        $sql = "truncate {$this->name}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
    }

    /**
    *   ファイルインポート(copy from)
    *
    *   @param string $file ファイルパス
    *   @params string[] $params パラメータ [delimiter, null]
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function import(string $file, array $params = []): void
    {
        if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) != 'pgsql') {
            throw new RuntimeException("invalid DB driver");
        }

        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                "file not found:{$file}"
            );
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = strtolower((string)$finfo->file($file));

        if (
            $mime != 'text'
            && $mime != 'text/plain'
            && $mime != 'application/csv'
        ) {
            throw new InvalidArgumentException(
                "different MIME type:{$mime}"
            );
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!in_array($ext, ['csv', 'txt', 'tsv','prn'])) {
            throw new InvalidArgumentException(
                "different EXT type:{$ext}"
            );
        }

        if (!is_array($params) || (!$this->isValidCopyParams($params))) {
            throw new InvalidArgumentException("invalid parameter");
        }

        $delimiter  = (!isset($params['delimiter'])) ?
            ',' : $params['delimiter'];
        $null = (!isset($params['null'])) ? "\\\\N" : $params['null'];

        $this->pdo->pgsqlCopyFromFile(
            $this->name,
            $file,
            $delimiter,
            $null
        );
    }

    /**
    *   COPYコマンドバリデーション
    *
    *   @param string[] $params
    *   @return bool
    */
    protected function isValidCopyParams(array $params): bool
    {
        foreach ($params as $key => $val) {
            switch ($key) {
                case 'delimiter':
                    if (!mb_check_encoding($val) || (strlen($val) != 1)) {
                        return false;
                    }
                    break;
                case 'null':
                    if (!mb_check_encoding($val)) {
                        return false;
                    }
                    break;
                default:
                    return false;
            }
        }
        return true;
    }

    /**
    *   UPSERT
    *
    *   @param DataModelInterface $data データ
    *   @param DataModelInterface $where 条件
    */
    public function upsert(
        DataModelInterface $data,
        DataModelInterface $where
    ): void {
        $values = [];
        foreach ($data->toArray() as $key => $val) {
            if (!is_null($val)) {
                $values[$key] = ":{$key}";
            }
        }

        if (empty($values)) {
            return;
        }

        $wheres = [];
        foreach ($where->toArray() as $key => $val) {
            if (!is_null($val)) {
                $wheres[$key] = ":_{$key}";
            }
        }

        if (empty($wheres)) {
            return;
        }

        $sql = "INSERT INTO {$this->name} AS Z (";
        $sql .= implode(',', array_keys($values)) . ') VALUES (';
        $sql .= implode(',', $values) . ') ON CONFLICT (';
        $sql .= implode(',', array_keys($wheres)) . ') DO UPDATE SET ';

        $updates = [];
        foreach ($values as $key => $val) {
            $updates[] = "{$key} = {$val}";
        }
        $sql .=  implode(',', $updates) . ' WHERE ';

        $wheres2 = [];
        foreach ($wheres as $key => $val) {
            $wheres2[] = "Z.{$key} = {$val}";
        }
        $sql .=  implode(' AND ', $wheres2);

        $stmt = $this->pdo->prepare($sql);
        $this->bind($stmt, $data);
        $this->bind($stmt, $where, ':_');
        $stmt->execute();
    }
}
