<?php

/**
*   ModelDbFactory
*
*   @version 200724
*/

declare(strict_types=1);

namespace Concerto\sql;

use Concerto\standard\ModelDataMapperInterface;

class ModelDbFactory
{
    /**
    *   namespace
    *
    *   @var string
    */
    private string $namespace;
    
    
    /**
    *   __construct
    *
    */
    public function __construct(string $path): ModelDataMapperInterface
    {
        $splited = mb_split(DIRECTORY_SEPARATOR, $path);
        $namespace = __NAMESPACE__
            . '\\'
            . $splited[count($splited)-1)];
            . '\\';
    }
    
    /**
    *   build
    *
    */
    public function build()
    {
        $obj = new ModelDb ==>どうする？
        
        foreach(DirectoryIterator as $file) {
            
            
            
            
        }
        
        
    }
}
