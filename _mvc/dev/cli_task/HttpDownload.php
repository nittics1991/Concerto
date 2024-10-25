<?php

/**
*   HTTPファイルダウンロード
*
*   @version 210121
*/

declare(strict_types=1);

namespace dev\standard;

use RuntimeException;

class HttpDownload
{
    /**
    *   HTTPヘッダ(システム初期値)
    *
    *   @var string[]
    */
    private $params = [
        ['Content-Type' => 'application/octet-stream']
    ];

    /**
    *   HTTPヘッダ(ユーザ設定)
    *
    *   @var string[]
    */
    private $user_params = [];

    /**
    *   __construct
    *
    *   @param string[] $params HTTP設定 [[]]
    *   @example $params = [[''Content-Transfer-Encoding' => 'binary']
    *       , ['pragma' => 'no-cache']]
    */
    public function __construct(array $params = [])
    {
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
    ) {
        if (php_sapi_name() === 'cli') {
            return;
        }

        if (headers_sent()) {
            throw new RuntimeException("already sent http header");
        }

        $file_sjis = mb_convert_encoding($file, 'SJIS', 'UTF-8');




        var_dump($file);






        if (!file_exists($file_sjis)) {
            throw new RuntimeException("file not found:{$file}");
        }

        $filesize = filesize($file_sjis);

        $file_escape =  addslashes(
            mb_convert_encoding($file, 'SJIS', 'UTF-8')
        );

        if (!($fp = fopen($file_escape, 'rb'))) {
            throw new RuntimeException("file open error:{$file}");
        }

        foreach ($this->params as $settiongs) {
            foreach ($settiongs as $key => $val) {
                header("{$key}:{$val}");
            }
        }

        foreach ($this->user_params as $settiongs) {
            foreach ($settiongs as $key => $val) {
                header("{$key}:{$val}");
            }
        }

        header(
            "Content-Disposition: attachment; filename=\"" .
                basename($file_sjis) . "\""
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

        if ($save_after_remove) {
            unlink($file_sjis);
        }
    }

    /**
    *   ユーザパラメータ取得
    *
    *   @return string[]
    */
    public function getParam()
    {
        return $this->user_params;
    }

    /**
    *   ユーザパラメータ設定
    *
    *   @param string[] $params
    */
    public function setParam(array $params)
    {
        $this->user_params = $params;
    }
}
