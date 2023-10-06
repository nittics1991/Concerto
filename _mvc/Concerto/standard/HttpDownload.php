<?php

/**
*   HTTPファイルダウンロード
*
*   @version 221221
*/

declare(strict_types=1);

namespace Concerto\standard;

use RuntimeException;

class HttpDownload
{
    /**
    *   @var string[][]
    */
    private array $params = [
        ['Content-Type' => 'application/octet-stream']
    ];

    /**
    *   HTTPヘッダ(ユーザ設定)
    *
    *   @var string[][]
    */
    private array $user_params = [];

    /**
    *   __construct
    *
    *   @param string[][] $params HTTP設定 [[]]
    *   @example $params = [[''Content-Transfer-Encoding' => 'binary']
    *       , ['pragma' => 'no-cache']]
    */
    public function __construct(
        array $params = []
    ) {
        $this->setParam($params);
    }

    /**
    *   ダウンロード
    *
    *   @param string $file ファイルパス
    *   @param bool $save_after_remove
    */
    public function send(
        string $file,
        bool $save_after_remove = true
    ): void {
        if (headers_sent()) {
            throw new RuntimeException(
                "already sent http header"
            );
        }

        //php8.1対応
        /*
        $file_sjis = mb_convert_encoding($file, 'SJIS', 'UTF-8');

        if (!file_exists($file_sjis)) {
            throw new RuntimeException("file not found:{$file}");
        }
       */

        $exists_file = $file;
        if (!file_exists($exists_file)) {
            throw new RuntimeException(
                "file not found:{$file}"
            );
        }

        //php8.1対応
        /*
        $filesize = filesize($file_sjis);
       */

        $filesize_file = $file;
        $filesize = filesize($filesize_file);

        //php8.1対応
        /*
        $file_escape =  addslashes(
            mb_convert_encoding($file, 'SJIS', 'UTF-8')
        );
       */
        $escape_file = $file;
        $file_escape =  addslashes(
            $escape_file,
        );

        if (!($fp = fopen($file_escape, 'rb'))) {
            throw new RuntimeException(
                "file open error:{$file}"
            );
        }

        foreach ($this->params as $settiongs) {
            foreach ($settiongs as $key => $val) {
                header("{$key}:{$val}");
            }
        }

        foreach ($this->user_params as $settiongs) {
            foreach ((array)$settiongs as $key => $val) {
                header("{$key}:{$val}");
            }
        }

        //php8.1対応
        /*
        header(
            "Content-Disposition: attachment; filename=\"" .
                basename($file_sjis) . "\""
        );
       */
        $basename_file = $file;
        $base_name = basename($basename_file);

        $header_file = mb_convert_encoding(
            $base_name,
            'SJIS',
            'UTF-8'
        );

        header(
            "Content-Disposition: attachment; filename=\"" .
                $header_file . "\""
        );


        //Edgeの文字化け対策を実施するとファイル名が「_」で囲われる為、
        //ファイル名を変えないと開けない
        //header(
            // 'Content-Disposition: attachment; filename*=UTF-8\'\'"'
            // . rawurlencode(basename($file)) . '"'
        // );

        header("Content-Length:{$filesize}");

        //DL中にリクエスト受付許可
        session_write_close();

        //ダウンロードファイルの先頭に不要データが入る場合がある対策
        ob_end_clean();

        rewind($fp);
        fpassthru($fp);
        @fclose($fp);

        @ob_end_flush();

        //php8.1対応
        /*
        if ($save_after_remove) {
            unlink($file_sjis);
        }
       */
        $unlink_file = $file;
        if ($save_after_remove) {
            unlink($unlink_file);
        }
    }

    /**
    *   ユーザパラメータ取得
    *
    *   @return string[][]
    */
    public function getParam(): array
    {
        return $this->user_params;
    }

    /**
    *   ユーザパラメータ設定
    *
    *   @param string[][] $params
    *   @return void
    */
    public function setParam(
        array $params
    ): void {
        $this->user_params = $params;
    }
}
