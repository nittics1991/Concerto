<?php

//namespace Concerto\Valodator;

//use \IteratorAggregate;

class ReportFileFinder implements IteratorAggregate
{
    
    protected $factory;
    
    public function __construct($factory)
    {
        $thi->factory = $factory;
    }
    
    public function getIterator()
    {
        $iterator = new RegexIterator(
            new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $basePath,
                    FilesystemIterator::CURRENT_AS_PATHNAME
                )
            ),
            $pattern,
            RecursiveRegexIterator::GET_MATCH
        );
        
        //return array=>datamappaerだからarrayで返す [[id, count], ..]
        //factoryでtotalCountやPhpCs1件を切り替え?
        
        foreach ($iterator as $path) {
            yield $this->fatory->create($path);
        }
    }
}
