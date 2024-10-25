<?php

/**
*   VIEW標準
*
*   @version 240826
*/

declare(strict_types=1);

namespace Concerto\standard;

use BadMethodCallException;
use InvalidArgumentException;
use Concerto\auth\Csrf;
use Concerto\standard\ArrayAccessObject;
use Concerto\template\PhpTemplate;

/**
*   @template TValue
*   @extends ArrayAccessObject<TValue>
*/
class ViewStandard extends ArrayAccessObject
{
    /**
    *   @var string
    */
    public string $csrf;

    /**
    *   @var int
    */
    public int $httpStatus = 200;

    /**
    *   @var callable[]
    */
    protected array $helpers = [];

    /**
    *   __construct
    *
    *   @param mixed[] $data
    */
    public function __construct(
        array $data = []
    ) {
        $this->fromArray($data);

        $this->csrf = Csrf::generate();
    }

    /**
    *   全メンバHTML エンティティ変換
    *
    *   @return static
    */
    public function toHTML(): static
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
    *   @return static
    */
    public function toSJIS(): static
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
    *   @return static
    */
    public function decodeHTML(
        string $name
    ): static {
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
    *   @param string $template
    *   @return string
    */
    protected function doRrender(
        string $template
    ): string {
        $templateEngine = new PhpTemplate($template);

        $templateEngine->csrf = $this->csrf;

        $templateEngine->httpStatus = $this->httpStatus;

        return $templateEngine->render($this->data);
    }

    /**
    *   描画
    *
    *   @param string $template
    *   @return void
    */
    public function render(
        string $template
    ): void {
        echo $this->doRrender($template);
    }

    /**
    *   描画キャッシュ
    *
    *   @param string $template
    *   @return string
    */
    public function cache(
        string $template
    ): string {
        return $this->doRrender($template);
    }

    /**
    *   abort
    *
    *   @param int $status
    *   @param ?string $template
    *   @return void
    */
    public function abort(
        int $status,
        ?string $template = null
    ): void {
        $this->httpStatus = $status;

        http_response_code($this->httpStatus);

        if (isset($template)) {
            $this->render($template);
        }
    }

    /**
    *   addHelper
    *
    *   @param string $name
    *   @param callable $callback
    *   @return static
    */
    public function addHelper(
        string $name,
        callable $callback
    ): static {
        $this->helpers[$name] = $callback;

        return $this;
    }

    /**
    *   @inheritDoc
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {
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
