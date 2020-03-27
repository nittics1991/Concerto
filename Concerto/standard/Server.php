<?php

/**
*   Server
*
*   @version 190523
*/

namespace Concerto\standard;

use InvalidArgumentException;

class Server
{
    /**
    *    convertVariableName
    *
    *   @return array
    **/
    protected static function convertVariableName()
    {
        return (array)array_combine(
            array_map(
                [Server::class, 'convert'],
                array_keys($_SERVER)
            ),
            $_SERVER
        );
    }
    
    /**
    *    convert
    *
    *   @param string $string
    *   @return string
    **/
    protected static function convert(string $string): string
    {
        return strtolower($string);
    }
    
    /**
    *   has
    *
    *   @param string $name
    *   @return bool
    */
    public static function has($name): bool
    {
        $data = self::convertVariableName();
        $name = self::convert($name);
        return array_key_exists($name, $data);
    }
    
    /**
    *   get
    *
    *   @return mixed
    */
    public static function get($name = null)
    {
        $data = self::convertVariableName();
        
        if (!isset($name)) {
            return $data;
        }
        $name = self::convert($name);
        return (array_key_exists($name, $data)) ? $data[$name] : null;
    }
    
    /**
    *   Ajax判定
    *
    *   @return bool
    */
    public static function isAjax()
    {
        $data = self::convertVariableName();
        
        return isset($data['http_x_requested_with'])
            && (strtolower($data['http_x_requested_with']) == 'xmlhttprequest');
    }
    
    /**
    *   リクエストURL取得
    *
    *   @return string
    */
    public static function getRequestUrl()
    {
        $data = self::convertVariableName();
        
        if (
            !isset($data['server_name'])
            || !isset($data['server_port'])
            || !isset($data['request_uri'])
        ) {
            return '';
        }
        
        $url = (isset($data['https']) && $data['https'] == 'on') ?
            'https://' : 'http://';
        $url .= "{$data['server_name']}";
        $url .= ":{$data['server_port']}";
        $url .= $data['request_uri'];
        return $url;
    }
    
    /**
    *   リクエストURL取得(query文字無し)
    *
    *   @return string
    */
    public static function getRequestSelfUrl()
    {
        $data = self::convertVariableName();
        
        if (
            !isset($data['server_name'])
            || !isset($data['server_port'])
            || !isset($data['request_uri'])
        ) {
            return '';
        }
        
        $url = (isset($data['https']) && $data['https'] == 'on') ?
            'https://' : 'http://';
        $url .= "{$data['server_name']}";
        $url .= ":{$data['server_port']}";
        
        $splited = mb_split('\?', $data['request_uri']);
        $url .= $splited[0];
        return $url;
    }
    
    /**
    *   リクエスト親URL取得
    *
    *   @return string
    */
    public static function getRequestParentUrl()
    {
        $data = self::convertVariableName();
        
        if (
            !isset($data['server_name'])
            || !isset($data['server_port'])
            || !isset($data['request_uri'])
        ) {
            return '';
        }
        
        $url = (isset($data['https']) && $data['https'] == 'on') ?
            'https://' : 'http://';
        $url .= "{$data['server_name']}";
        $url .= ":{$data['server_port']}";
        
        $splited = mb_split('\?', $data['request_uri']);
        $pos = mb_strrpos($splited[0], '/');
        $url .= mb_substr($splited[0], 0, $pos + 1);
        return $url;
    }
}
