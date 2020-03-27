<?php

/**
*   Post
*
*   @version 190523
*/

namespace Concerto\standard;

use Concerto\standard\DataContainerValidatable;

class Post extends DataContainerValidatable
{
    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->data = $_POST;
    }
    
    /**
    *   バリデート全変数共通処理
    *
    *   @param string $key 変数名
    *   @param mixed $val データ
    *   @return bool
    **/
    protected function validCom($key, $val)
    {
        $result = true;
        
        if (is_array($val)) {
            foreach ($val as $key2 => $val2) {
                $result = $this->validCom($key2, $val2) && $result;
            }
        } else {
            if (!mb_check_encoding($val)) {
                $this->valid[$key][] = 'invalid encoding';
                $result = false;
            }
            
            if (
                !mb_ereg_match(
                    '\A[\x20-\x7e\x80-\xff\x09-\x0a\x0d]*\z',
                    $val
                )
            ) {
                $this->valid[$key][] = 'invalid code';
                $result = false;
            }
        }
        return $result;
    }
    
    /**
    *   AJAX判定
    *
    *   @return bool
    */
    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])
            === 'xmlhttprequest';
    }
    
    /**
    *   {inherit}
    *
    **/
    public function offsetGet($key)
    {
        $method = 'get' . mb_convert_case($key, MB_CASE_TITLE);
        if (method_exists($this, $method)) {
            $value = $this->$method($key);
        } else {
            $value = parent::offsetGet($key);
        }
        return $this->getFilterCom($value);
    }
    
    /**
    *   get共通フィルタ
    *
    *   @param string|array $val
    *   @return string|array
    **/
    protected function getFilterCom($val)
    {
        if (is_array($val)) {
            return array_map(
                function ($val2) {
                    return $this->getFilterCom($val2);
                },
                $val
            );
        }
        return $this->doGetFilterCom((string)$val);
    }
    
    /**
    *   get共通フィルタ実行
    *
    *   @param string $val
    *   @return string
    **/
    protected function doGetFilterCom(string $val)
    {
        return strip_tags($val);
    }
    
    /**
    *   getフィルタ
    *
    *   @see 必要に応じてフィルタ処理を作成する
    **/
    //protected function getXxxx($key)
}
