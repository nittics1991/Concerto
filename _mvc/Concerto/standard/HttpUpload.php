<?php

/**
*   HTTPファイルアップロード
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\standard;

use finfo;
use RuntimeException;

class HttpUpload
{
    /**
    *   設定値
    *
    *   @var mixed[]
    */
    private $params = [
        'mime' => [
            'xls' =>
                'application/vnd.ms-excel',
            'xlsm' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xlsx' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'doc' =>
                'application/msword',
            'docx' =>
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ],
        'max_size' => 1000000,
        'diversion' => false
    ];

    /**
    *   __construct
    *
    *   @param mixed[] $params HTTP設定
    *   @example $params = [['max_size' => '1000000']]
    */
    public function __construct(array $params = [])
    {
        $this->setParam($params);
    }

    /**
    *   アップロード
    *
    *   @param string $tag 名前
    *   @param ?string $dir 保存先ディレクトリ
    *   @return string[] ファイルパス
    */
    public function load(string $tag, ?string $dir = null)
    {
        $names  = (array)($_FILES[$tag]['name'] ?? []);
        $tmps   = (array)($_FILES[$tag]['tmp_name'] ?? []);
        $files = [];

        for (
            $i = 0, $length = count($names);
            $i < $length;
            $i++
        ) {
            if (!$this->isValidUploadParameter($tag, $i)) {
                throw new RuntimeException(
                    "parameter is invalid:tag={$tag},position={$i}"
                );
            }

            $ext = '';

            if (!empty($this->params['mime'])) {
                $mime = $this->getMimeType($tmps[$i]);
                $ext = $this->checkAndGetExt($names[$i], $mime);
            }

            $file_name = ($this->params['diversion']) ?
                $names[$i] : uniqid() . ".{$ext}";
            $files[$i] = $this->toUpload($tmps[$i], $file_name, $dir);
        }
        return $files;
    }

    /**
    *   isValidUploadParameter
    *
    *   @param string $tag
    *   @param int $pos
    *   @return bool
    */
    private function isValidUploadParameter(string $tag, int $pos): bool
    {
        $errors = (array)$_FILES[$tag]['error'];
        $sizes = (array)$_FILES[$tag]['size'];

        if (
            !isset($errors[$pos]) ||
            !is_int($errors[$pos]) ||
            $errors[$pos] !== UPLOAD_ERR_OK ||
            $sizes[$pos] > $this->params['max_size']
        ) {
            return false;
        }
        return true;
    }

    /**
    *   getMimeType
    *
    *   @param string $tempname
    *   @return string
    *   @throws RuntimeException
    */
    private function getMimeType($tempname): string
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file(addslashes($tempname));

        if (array_search($mime, $this->params['mime'], true) === false) {
            throw new RuntimeException("mime is invalid:{$mime}");
        }
        return $mime;
    }

    /**
    *   checkAndGetExt
    *
    *   @param string $filename
    *   @param string $mime
    *   @return string
    *   @throws RuntimeException
    */
    private function checkAndGetExt(string $filename, string $mime): string
    {
        $pos = mb_strrpos(
            mb_convert_encoding($filename, 'UTF-8', 'auto'),
            '.'
        );

        if ($pos === false) {
            return '';
        }

        $ext_src = array_keys($this->params['mime'], $mime, true);
        $ext = mb_substr($filename, $pos + 1);

        if (!in_array($ext, $ext_src, true)) {
            throw new RuntimeException("ext is not match");
        }
        return $ext;
    }

    /**
    *   toUpload
    *
    *   @param string $tempfile
    *   @param string $upfilename
    *   @param ?string $dir
    *   @return string
    *   @throws RuntimeException
    */
    private function toUpload(
        string $tempfile,
        string $upfilename,
        ?string $dir = null
    ): string {
        $file = (!is_null($dir)) ?
            $dir . DIRECTORY_SEPARATOR . $upfilename
            : sys_get_temp_dir() . DIRECTORY_SEPARATOR . $upfilename;

        if (!file_exists(dirname($file))) {
            throw new RuntimeException("not exists temp dir");
        }

        if (!move_uploaded_file($tempfile, $file)) {
            throw new RuntimeException("upload error");
        }
        return $file;
    }

    /**
    *   設定値取得
    *
    *   @return mixed[]
    */
    public function getParam()
    {
        return $this->params;
    }

    /**
    *   設定値設定
    *
    *   @param mixed[] $params
    */
    public function setParam(array $params): void
    {
        foreach (array_keys($this->params) as $key) {
            if (array_key_exists($key, $params)) {
                $this->params[$key] = $params[$key];
            }
        }
    }

    /**
    *   MIME確認
    *
    *   @param string $path ファイルパス
    *   @return ?string MIME or null
    */
    public function whatMime(string $path)
    {
        $result = null;

        if (file_exists($path)) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $result = $finfo->file(addslashes($path));
        }
        return $result === false ? null : $result;
    }

    /**
    *   UPLOADファイル有無
    *
    *   @param string $name 要素名
    *   @return bool
    */
    public function isNull(string $name): bool
    {
        if (!isset($_FILES[$name])) {
            return false;
        }

        if (is_array($_FILES[$name]['name'])) {
            $result = true;
            array_walk(
                $_FILES[$name]['name'],
                function ($val, $key) use (&$result) {
                    if ($val != '') {
                        $result = false;
                    }
                }
            );
            return $result;
        }

        if ($_FILES[$name]['name'] == '') {
            return true;
        }
        return false;
    }
}
