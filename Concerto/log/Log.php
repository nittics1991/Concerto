<?php

/**
*   ログ
*
*   @version 170126
*/

declare(strict_types=1);

namespace Concerto\log;

use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Psr\Log\InvalidArgumentException as LogException;
use InvalidArgumentException;
use Concerto\log\LogWriterInterface;

class Log implements LoggerInterface, LogInterface
{
    /**
    *   レベル
    *
    **/
    public const DEBUG = 100;
    public const INFO = 200;
    public const NOTICE = 250;
    public const WARNING = 300;
    public const ERROR = 400;
    public const CRITICAL = 500;
    public const ALERT = 550;
    public const EMERGENCY = 600;
    
    /**
    *   レベルマップ
    *
    *   @var array
    **/
    private static $levelmap = [
        LogLevel::DEBUG => self::DEBUG,
        LogLevel::INFO => self::INFO,
        LogLevel::NOTICE => self::NOTICE,
        LogLevel::WARNING => self::WARNING,
        LogLevel::ERROR => self::ERROR,
        LogLevel::CRITICAL => self::CRITICAL,
        LogLevel::ALERT => self::ALERT,
        LogLevel::EMERGENCY => self::EMERGENCY
    ];
    
    /**
    *   ログライター
    *
    *   @var array
    */
    private $writers = [];
    
    /**
    *   レベル制限値
    *
    *   @var int
    **/
    private $limit;
    
    /**
    *   __construct
    *
    *   @param LogWriterInterface $writer ログライター
    *   @param int|string $limit
    */
    public function __construct(LogWriterInterface $writer, $limit = 999)
    {
        $this->writers[] = $writer;
        $this->setLimit($limit);
    }
    
    /**
    *   制限値設定
    *
    *   @param int|string $limit
    **/
    private function setLimit($limit)
    {
        if (is_int($limit)) {
            $this->limit = $limit;
            return;
        }
        
        if (is_string($limit) && array_key_exists($limit, self::$levelmap)) {
            $this->limit = self::$levelmap[$limit];
            return;
        }
        throw new InvalidArgumentException("limit not defined");
    }
    
    /**
    *   {inherit}
    *
    */
    public function addWriter(LogWriterInterface $writer)
    {
        $this->writers[] = $writer;
    }
    
    /**
    *   {inherit}
    *
    */
    public function write($messages)
    {
        if ($this->depth($messages) == 0) {
            $list = array(array($messages));
        } elseif ($this->depth($messages) == 1) {
            $list = array($messages);
        } elseif ($this->depth($messages) == 2) {
            $list = $messages;
        } else {
            throw new InvalidArgumentException("log error");
        }
        
        $cnt = 0;
        foreach ($this->writers as $writer) {
            $writer->write($list[$cnt]);
            $cnt++;
        }
    }
    
    /**
    *   配列次元数
    *
    *   @param array $array 配列
    *   @param int $depth 次元数
    *   @return int 次元数
    */
    private function depth($array, $depth = 0)
    {
        if (!is_array($array)) {
            return $depth;
        }
        
        $tmp = [];
        $depth++;
        foreach ($array as $val) {
            $tmp[] = $this->depth($val, $depth);
        }
        return max($tmp);
    }
    
    //以下、PSR-3対応
    
    /**
    * Logs with an arbitrary level.
    *
    * @param mixed  $level
    * @param string $message
    * @param array  $context
    */
    public function log($level, $message, array $context = [])
    {
        if (is_int($level)) {
            $lvl = $level;
        } elseif (
            is_string($level)
            && array_key_exists($level, self::$levelmap)
        ) {
            $lvl = self::$levelmap[$level];
        } else {
            throw new LogException("level not defined");
        }
        
        $outdata = is_string($message) ? $message : $this->obj2str($message);
        
        if (count($context) > 0) {
            $outdata = $this->interpolate($outdata, $context);
        }
        
        if ($lvl <= $this->limit) {
            $this->write($outdata);
        }
    }
    
    /**
    *   obj2str
    *
    *   @param object $obj
    *   @return string
    **/
    private function obj2str($obj)
    {
        if (is_object($obj) && method_exists($obj, '__toString')) {
            return $obj->__toString();
        }
        return '';
    }
    
    /**
    *   変数展開
    *
    *   @param string $message
    *   @param array $context
    *   @return string
    **/
    private function interpolate($message, array $context)
    {
        $replace = [];
        foreach ($context as $key => $val) {
            if (
                !is_array($val)
                && (!is_object($val)
                || method_exists($val, '__toString'))
            ) {
                $replace['{' . $key . '}'] = $val;
            }
        }
        return strtr($message, $replace);
    }
    
    /**
    * System is unusable.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function emergency($message, array $context = [])
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }
    
    /**
    * Action must be taken immediately.
    *
    * Example: Entire website down, database unavailable, etc. This should
    * trigger the SMS alerts and wake you up.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function alert($message, array $context = [])
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }
    
    /**
    * Critical conditions.
    *
    * Example: Application component unavailable, unexpected exception.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function critical($message, array $context = [])
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }
    
    /**
    * Runtime errors that do not require immediate action but should typically
    * be logged and monitored.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function error($message, array $context = [])
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }
    
    /**
    * Exceptional occurrences that are not errors.
    *
    * Example: Use of deprecated APIs, poor use of an API, undesirable things
    * that are not necessarily wrong.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function warning($message, array $context = [])
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }
    
    /**
    * Normal but significant events.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function notice($message, array $context = [])
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }
    
    /**
    * Interesting events.
    *
    * Example: User logs in, SQL logs.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function info($message, array $context = [])
    {
        $this->log(LogLevel::INFO, $message, $context);
    }
    
    /**
    * Detailed debug information.
    *
    * @param string $message
    * @param array  $context
    *
    * @return null
    */
    public function debug($message, array $context = [])
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
