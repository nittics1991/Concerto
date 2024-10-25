<?php

/**
*   Translator
*
*   @ver 180612
*   @caution 定義ファイルはid=>message型式の配列をreturnする
*       messageはvsprintfのフォーマット
*/

declare(strict_types=1);

namespace candidate\translation;

use InvalidArgumentException;

class Translator implements TranslatorInterface
{
    /**
    *   messages
    *
    *   @var string[]
    */
    private $messages = [];

    /**
    *   __construct
    *
    *   @param ?string $filePath
    */
    public function __construct(?string $filePath = null)
    {
        if (isset($filePath)) {
            $this->readMessageFile($filePath);
        }
    }

    /**
    *   readMessageFile
    *
    *   @param string $filePath
    *   @return $this
    */
    public function readMessageFile(string $filePath): Translator
    {
        $this->messages = array_merge(
            $this->messages,
            $this->readFile($filePath)
        );
        return $this;
    }

    /**
    *   readFile
    *
    *   @param string $filePath
    *   @return mixed[]
    */
    private function readFile(string $filePath): array
    {
        $realpath = realpath($filePath);

        if ($realpath == false) {
            throw new InvalidArgumentException(
                "trans file faild:{$filePath}"
            );
        }
        return include($realpath);
    }

    /**
    *   @inheritDoc
    *
    */
    public function trans(string $id, array $params = []): string
    {
        $message = isset($this->messages[$id]) ?
            $this->messages[$id] : '';
        return (string)vsprintf($message, $params);
    }
}
