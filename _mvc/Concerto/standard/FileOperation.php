<?php

/**
*   ファイル操作
*
*   @version 220126
*/

declare(strict_types=1);

namespace Concerto\standard;

use ErrorException;
use Exception;
use InvalidArgumentException;
use RuntimeException;

class FileOperation
{
    /**
    *   フィルタ実行フラグ
    *
    *   @var bool
    */
    private $flag;

    /**
    *   フィルタ関数
    *
    *   @var callable
    */
    private $filter;

    /**
    *   __construct
    *
    *   @param bool $filter フィルタ実行フラグ
    *   @param ?callable $func フィルタ関数
    */
    public function __construct($filter = true, $func = null)
    {
        $this->flag = $filter;

        if ($filter) {
            if (is_callable($func)) {
                $this->filter = $func;
            } else {
                $this->filter = function ($source) {
                    return mb_convert_encoding($source, 'SJIS-WIN', 'UTF-8');
                };
            }
        }
    }

    /**
    *   ErrorException
    *
    */
    protected function errorHandler($no, $message, $file, $line)
    {
        if (!(error_reporting() & $no)) {
            return;
        }
        throw new ErrorException($message, 0, $no, $file, $line);
    }

    /**
    *   パスフィルタリング
    *   @param string $path パス
    *   @return string フィルタ後パス
    *   @throws RuntimeException
    */
    protected function getFilterPath(string $path)
    {
        if ($this->flag) {
            try {
                $f = $this->filter;
                return $f($path);
            } catch (Exception $e) {
                throw new RuntimeException(
                    "path filter error:{$path}",
                    0,
                    $e
                );
            }
        }
        return $path;
    }

    /**
    *   コピー
    *
    *   @param string $from コピー元パス
    *   @param string $to コピー先パス
    *   @param bool $overwrite 上書き許可
    *   @return bool
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function copy(string $from, string $to, bool $overwrite = true)
    {
        $src    = $this->getFilterPath($from);
        $dest   = $this->getFilterPath($to);

        if (file_exists($src)) {
            if (($overwrite) || (!$overwrite && !file_exists($dest))) {
                set_error_handler([$this,  'errorHandler']);
                try {
                    copy(addslashes($src), addslashes($dest));
                    restore_error_handler();
                    return true;
                } catch (Exception $e) {
                    restore_error_handler();
                    throw new RuntimeException(
                        "copy error:{$from} {$to}",
                        0,
                        $e
                    );
                }
            }
            return false;
        }
        throw new InvalidArgumentException("file not found:{$src}");
    }

    /**
    *   削除
    *
    *   @param string $path ファイルパス
    *   @return bool
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function delete(string $path)
    {
        $target     = $this->getFilterPath($path);

        if (file_exists($target)) {
            set_error_handler([$this,  'errorHandler']);
            try {
                unlink($target);
                restore_error_handler();
                return true;
            } catch (Exception $e) {
                restore_error_handler();
                throw new RuntimeException("delete error:{$target}", 0, $e);
            }
        }
        throw new InvalidArgumentException("file not found:{$target}");
    }

    /**
    *   名前変更（移動）
    *
    *   @param string $from 移動元パス
    *   @param string $to 移動先パス
    *   @param bool $overwrite 上書き許可
    *   @return bool
    *   @throws InvalidArgumentException
    */
    public function rename(string $from, string $to, bool $overwrite = true)
    {
        $src    = $this->getFilterPath($from);
        $dest   = $this->getFilterPath($to);

        if (file_exists($src)) {
            if (($overwrite) || (!$overwrite && !file_exists($dest))) {
                set_error_handler([$this,  'errorHandler']);
                try {
                    rename($src, $dest);
                    restore_error_handler();
                    return true;
                } catch (Exception $e) {
                    restore_error_handler();
                    throw new RuntimeException(
                        "rename error:{$from} {$to}",
                        0,
                        $e
                    );
                }
            }
            return false;
        }
        throw new InvalidArgumentException("file not found:{$src}");
    }

    /**
    *   テンポラリフォルダ
    *
    *   @param string $path パス
    *   @param ?int $delete ファイル削除日数(X日前) null:削除無し
    *   @return bool
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function createTempDir(string $path, $delete = null)
    {
        $target = $this->getFilterPath($path);

        set_error_handler([$this,  'errorHandler']);

        try {
            if (!file_exists($target)) {
                mkdir($target);
            }

            $this->clearTempDir($path, $delete);

            restore_error_handler();
            return true;
        } catch (Exception $e) {
            restore_error_handler();
            throw new RuntimeException(
                "temp dir create and clear error:{$path}",
                0,
                $e
            );
        }
    }

    /**
    *   テンポラリフォルダクリア
    *
    *   @param string $path パス
    *   @param ?int $expire_day ファイル削除日数(X日前) null:削除無し
    *   @return bool
    *   @throws InvalidArgumentException, RuntimeException
    */
    public function clearTempDir(string $path, $expire_day = null)
    {
        $target = $this->getFilterPath($path);

        set_error_handler([$this,  'errorHandler']);

        try {
            if (!is_null($expire_day) && is_int($expire_day)) {
                $expire_day = -1 * $expire_day;
                exec(
                    "forfiles /P {$target} /M *.* /D {$expire_day} /C \"cmd /c del @file\""
                );
            }

            restore_error_handler();
            return true;
        } catch (Exception $e) {
            restore_error_handler();
            throw new RuntimeException(
                "temp dir clear error:{$path}",
                0,
                $e
            );
        }
    }
}
