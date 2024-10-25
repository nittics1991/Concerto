<?php

/**
*   phpテンプレート
*
*   @version 221219
*/

declare(strict_types=1);

namespace Concerto\template;

use InvalidArgumentException;
use Concerto\accessor\SimpleAttributeTrait;
use Concerto\template\TemplateInterface;

class PhpTemplate implements TemplateInterface
{
    use SimpleAttributeTrait;

    /**
    *   @var string
    */
    protected string $tempalte;

    /**
    *   @var mixed[]
    */
    protected array $dataset;

    /**
    *   __construct
    *
    *   @param string $path
    */
    public function __construct(
        string $path
    ) {
        $this->tempalte = $path;
    }

    /**
    *   描画
    *
    *   @param mixed $dataset
    *   @return string
    */
    public function render(
        mixed $dataset
    ): string {
        if (!file_exists($this->tempalte)) {
            throw new InvalidArgumentException(
                "file not found:{$this->tempalte}"
            );
        }

        if (!is_array($dataset)) {
            throw new InvalidArgumentException(
                "dataset required array"
            );
        }
        $this->dataset = $dataset;

        $result = $this->expand();

        ob_end_clean();

        return $result;
    }

    /**
    *   展開
    *
    *   @return string
    */
    protected function expand(): string
    {
        extract($this->dataset);

        @ob_end_clean();

        ob_start();

        @include($this->tempalte);

        return (string)ob_get_contents();
    }
}
