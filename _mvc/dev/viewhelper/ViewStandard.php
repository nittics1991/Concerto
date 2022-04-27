<?php

/**
*   HTML画面表示
*
*   @version 210827
*/

declare(strict_types=1);

namespace dev\standard;

use BadMethodCallException;
use InvalidArgumentException;
use dev\auth\Csrf;
use dev\standard\ArrayAccessObject;
use dev\template\PhpTemplate;

class ViewStandard extends ArrayAccessObject
{
    /**
    *   Csrf token
    *
    *   @var string
    */
    public $csrf;

    /**
    *   http status
    *
    *   @var int
    */
    public $httpStatus;

    /**
    *   ヘルパー
    *
    *   @var mixed[]
    */
    protected $helpers = [];

    /**
    *   __construct
    *
    *   @param mixed[] $data データ
    */
    public function __construct(array $data = [])
    {
        $this->fromArray($data);
        $this->csrf = Csrf::generate();
    }

    /**
    *   全メンバHTML エンティティ変換
    *
    *   @return $this
    */
    public function toHTML()
    {
        array_walk_recursive($this->data, function (&$val, $key) {
            if (is_string($val)) {
                $val = htmlspecialchars($val, ENT_QUOTES);
            }
        });
        return $this;
    }

    /**
    *   全メンバHTML SJIS変換
    *
    *   @return $this
    */
    public function toSJIS()
    {
        array_walk_recursive($this->data, function (&$val, $key) {
            if (is_string($val)) {
                $val = mb_convert_encoding($val, 'SJIS', 'UTF-8');
            }
        });
        return $this;
    }

    /**
    *   toHTMLされた変数のデコード
    *
    *   @param string $name
    *   @return $this
    */
    public function decodeHTML(string $name)
    {
        if (!isset($this->$name)) {
            throw new InvalidArgumentException(
                "undefined property name:{$name}"
            );
        }

        $this->$name  = htmlspecialchars_decode(
            $this->$name,
            ENT_QUOTES
        );
        return $this;
    }

    /**
    *   描画実行
    *
    *   @param string $template テンプレートファイル
    */
    protected function doRrender(string $template)
    {
        $templateEngine = new PhpTemplate($template);
        $templateEngine->csrf = $this->csrf;
        $templateEngine->httpStatus = $this->httpStatus;

        foreach ($this->helpers as $name => $helper) {
            $templateEngine->$name = $helper;
        }

        return $templateEngine->render($this->data);
    }

    /**
    *   描画
    *
    *   @param string $template テンプレートファイル
    */
    public function render(string $template)
    {
        echo $this->doRrender($template);
    }

    /**
    *   描画キャッシュ
    *
    *   @param string $template テンプレートファイル
    *   @return string
    */
    public function cache(string $template)
    {
        return $this->doRrender($template);
    }

    /**
    *   abort
    *
    *   @param int $status
    *   @param ?string $template
    */
    public function abort(int $status, ?string $template = null)
    {
        $this->httpStatus = $status;
        http_response_code($this->httpStatus);

        if (isset($template)) {
            $this->render($template);
        }
    }

    /**
    *   ヘルパー追加
    *
    *   @param string $name
    *   @param mixed $helper
    *   @return $this
    */
    public function addHelper(string $name, mixed $helper)
    {
        $this->helpers[$name] = $helper;
        return $this;
    }

    /**
    *   {inherit}
    */
    public function __call(string $name, array $arguments): mixed
    {
        if (isset($this->helpers[$name])) {
            return call_user_func_array(
                $this->helpers[$name],
                $arguments
            );
        }
        throw new BadMethodCallException(
            "method not defined:{$name}"
        );
    }
}
