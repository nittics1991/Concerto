<?php

/**
*   Translator
*
*   @ver 180612
*   @caution 定義ファイルはid=>message型式の配列をreturnする
*       messageはvsprintfのフォーマット
**/

declare(strict_types=1);

namespace Concerto\translation;

class Translator implements TranslatorInterface
{
    /**
    *   messages
    *
    *   @var array
    **/
    private $messages = [];
    
    /**
    *   __construct
    *
    *   @param ?string $filePath
    **/
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
    **/
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
    *   @return array
    **/
    private function readFile(string $filePath): array
    {
        return include(
            realpath($filePath)
        );
    }
    
    /**
    *   {inherit}
    *
    **/
    public function trans(string $id, array $params = []): string
    {
        $message = isset($this->messages[$id]) ?
            $this->messages[$id] : '';
        return vsprintf($message, $params);
    }
}
