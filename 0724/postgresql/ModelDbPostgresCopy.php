<?php

/**
*   ModelDb importSQL Trait (postgresql)
*
*   @version 200724
*/

declare(strict_types=1);

namespace Concerto\sql;

use Concerto\standard\DataModelInterface;

class ModelDbAggPostgresTrait implements ModelDbFunctionInterface
{
    /**
    *   ファイルインポート(copy from)
    *
    *   @param string $file ファイルパス
    *   @params array $params パラメータ [delimiter, null]
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function import(string $file, array $params = [])
    {
        if ($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME) != 'pgsql') {
            throw new RuntimeException("invalid DB driver");
        }
        
        if (!file_exists($file)) {
            throw new InvalidArgumentException("file not found");
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = strtolower((string)$finfo->file($file));
        
        if (($mime != 'text') && ($mime != 'text/plain')) {
            throw new InvalidArgumentException("different MIME type");
        }
        
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        
        if (!in_array($ext, ['csv', 'txt', 'tsv','prn'])) {
            throw new InvalidArgumentException("different EXT type");
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
    *   @param array $params
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
}
