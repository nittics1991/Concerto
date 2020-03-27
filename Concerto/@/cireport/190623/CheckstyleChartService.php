<?php

// use \InvalidArgumentException;

class CheckstyleParser
{
    /**
    *   dom
    *
    *   @var SimpleXML
    **/
    protected $dom;
    
    /**
    *   __construct
    *
    *   @param string $filePath
    */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(
                "file not found:{$filePath}"
            );
        }
        $this->dom = simplexml_load_file($filePath);
        
        if ($this->dom === false) {
            throw new InvalidArgumentException(
                "file load error:{$filePath}"
            );
        }
    }
    
    /**
    *   countByPath
    *
    *   @param string $targetTagName
    *   @return int
    */
    public function countByPath(string $targetPath): int
    {
        $elements = $this->dom->xpath($targetPath);
        
        if ($elements === false) {
            throw new InvalidArgumentException(
                "path not found:{$basePath}"
            );
        }
        return count($elements);
    }
    
    /**
    *   groupByPath
    *
    *   @param string $targetTagName
    *   @param string $attributeName
    *   @return array [['value' => string, 'count' => int], ...]
    */
    public function groupByPath(
        string $targetPath,
        string $attributeName
    ): array {
        $elements = $this->dom->xpath($targetPath);
        
        if ($elements === false) {
            throw new InvalidArgumentException(
                "path not found:{$basePath}"
            );
        }
        
        $results = [];
        $keys = [];
        
        foreach ($elements as $element) {
            if (isset($element[$attributeName])) {
                $value = (string)$element[$attributeName];
                
                if (($pos = array_search($value, $keys)) === false) {
                    $keys[] = $value;
                    $results[count($keys) - 1] = [
                        'value' => $value,
                        'count' => 1,
                    ];
                } else {
                    $results[$pos]['count'] += 1;
                }
            }
        }
        return $results;
    }
}
