<?php

/**
*   Post
*
*   @version 200405
*/

declare(type_stricts=1);

namespace Concerto\standard;

trait ReflectePropertyVaridateTrait
{
    protected array $validateResults = [];
    
    /**
    *   isValid
    *
    *   @return bool
    */
    public function isValid(): bool
    {
        $this->validateResults = [];
        $result = true;
        
        foreach (array_keys($this->properties) as $property) {
            $result = $result && $this->commonValidate($val);
            
            if ($)
            
            $result = $result && $this->propertyValidate($name, $val);
            
            
        }
        return $result;
    }
    
    /**
    *   共通検査
    *
    *   @param mixed $val
    *   @return bool
    */
    public function commonValidate($val): bool
    {
        return $val == $val;
    }
    
    /**
    *   プロパティ検査検査
    *
    *   @param string $name
    *   @param mixed $val
    *   @return bool
    *   @example isValidXxx Xxx:CamelCaseプロパティ名
    */
    public function propertyValidate(string $name, $val): bool
    {
        $methodName = 'isValid' .
            mb_convert_case($name, MB_CASE_TITLE);
        
        return method_exists($this, $methodName)?
            $this->$methodName($val):
            true;
    }
}
