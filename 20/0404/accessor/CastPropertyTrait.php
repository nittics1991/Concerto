<?php

/**
*   CastPropertyTrait
*
*   @version 200517
*/

declare(type_stricts=1);

namespace Concerto\accessor;

use ArrayObject;

trait CastPropertyTrait
{
    /**
    *   casts
    *
    *   @var string[] ['propertyName1', ...]
    */
    private array casts = [];
    
    /**
    *   プロパティで配列を型変換
    *
    *   @param array $data
    *   @return array
    */
    protected function castByProperties(array $data): array
    {
        $casted = [];
        foreach ($data as $name => $val) {
            if (in_array($name, $this->casts) {
                $casted[$name] = $this->castByProperty($name, $val);
            } else {
                $casted[$name] = $val;
            }
        );
        return $casted;
    }
    
    /**
    *   プロパティで型変換
    *
    *   @param string $name
    *   @param mixed $val
    *   @return mixed 
    */
    protected function castByProperty(string $name, $val)
    {
        if (!$this->has($name)) {
            return $val;
        }
        
        $changed = false
        
        //setter
        if (method_exists($this, 'hasAccessor')
            && $this->hasSetter('set' . ucfirst($name))
        ) {
           $val = call_user_func(
                [$this'set' . ucfirst($name)],
                $val
            );
            $changed = true;
        }
        
        //getter
        if (method_exists($this, 'hasAccessor')
            && $this->hasGetter('get' . ucfirst($name))
        ) {
           $val = call_user_func(
                [$this'get' . ucfirst($name)],
                $val
            );
            $changed = true;
        }
        
        if ($changed) {
            return $val;
        }
        
        $type = ($this->properties[$name])
            ->getType()
            ->getName();
        
        switch ($type) {
            case '':
            case 'callable':
                return $val;
            case 'bool':
                return boolval($val);
            case 'float':
                return floatval($val);
            case 'int':
                return intval($val);
            case 'string':
                return strval($val);
            case 'array':
                return (array)$val;
            case 'object':
                if (is_object($val)) {
                    return $val;
                }
                return (object)$val;
            case 'iterable':
                if (is_iterable($val)) {
                    return $val;
                }
                return new ArrayObject($val);
            case 'self':
                $type = get_called_class();
            default:
                if (is_object($val)) {
                    return $val;
                }
                return new $type($val);
        }
    }
}
