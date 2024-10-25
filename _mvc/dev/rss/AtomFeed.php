<?php

/**
*   ATOM FREED
*
*   @version 230927
*/

declare(strict_types=1);

namespace Concerto;


class AtomFeed
{
    /**
    *   @var array
    */
    private string $template = 'atom';

    /**
    *   @var string[]
    */
    private array $element_names = [
        'author', 'category', 'contributor',
        'generator', 'icon', 'id',
        'link', 'Logo', 'rights',
        'subtitle', 'title', 'updated',
        'entry',
    ];

    /**
    *   @var string[]
    */
    private array $contents = [];

    /**
    *   {inherit}
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {
        if (!in_array($name, $this->element_names)) {
            throw new BadMethodCallException(
                "not defined method:{$name}",
            );
        }
        
        $this->contents[$name] = $arguments;
        
        return static;
    }

    /**
    *   generate
    *
    *   @param string $param
    *   @return void
    */
    public function generate():string
    {
        
        
        
        
        
        
        
        $entries = '';
        
        foreach($this->entry as $atomEntry) {
            $entries .= $atomEntry->generate() . PHP_EOL;
        }
        
        
        
        
        
    }
    
    
    
    /**
    *   expand
    *
    *   @param string $tempalte
    *   @param mixed $dataset
    *   @return string
    */
    private function expand(
        string $tempalte,
        array $dataset,
    ): string {
        extract($dataset);

        @ob_end_clean();

        ob_start();

        @include($tempalte);

        return (string)ob_get_contents();
    }
}
