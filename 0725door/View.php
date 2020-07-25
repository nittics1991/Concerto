<?php

/**
*
*/
class View
{
    private $tempalte = 'template.php';
    
    public function __construct()
    {
    }
    
    public function render()
    {
        ob_start();
        include($this->tempalte);
         $contents = (string)ob_get_contents();
        ob_end_clean();
        
        echo $contents;
    }
}
