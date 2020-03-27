<?php

/**
*   設定
*
*   @version 200322
*/

declare(strict_types=1);

namespace Concerto\conf;

use ArrayObject;
use Concerto\arrays\ArrayDot;
use Concerto\conf\ConfigReaderArray;

class Config extends ArrayObject
{
    /**
    *   __construct
    *
    *   @param ConfigReaderInterface $reader
    */
    public function __construct(ConfigReaderInterface $reader)
    {
        parent::__construct(
            $reader->read(),
            ArrayObject::ARRAY_AS_PROPS
        );
    }
    
    /**
    *   replace
    *
    *   @param ConfigReaderInterface $reader
    *   @return $this
    **/
    public function replace(ConfigReaderInterface $reader)
    {
        $src = $this->getArrayCopy();
        $dest = $reader->read();
        
        $data = array_replace_recursive($src, $dest);
        parent::__construct(
            $data,
            ArrayObject::ARRAY_AS_PROPS
        );
        return $this;
    }
    
    /**
    *   set
    *
    *   @param string $dot
    *   @param mixed $val
    *   @return Config
    **/
    public function set(string $dot, $val): Config
    {
        $data = ArrayDot::set($this->getArrayCopy(), $dot, $val);
        parent::__construct(
            $data,
            ArrayObject::ARRAY_AS_PROPS
        );
        return $this;
    }
    
    /**
    *   get
    *
    *   @param string $dot
    *   @return mixed
    **/
    public function get(string $dot)
    {
        return ArrayDot::get($this->getArrayCopy(), $dot);
    }
    
    /**
    *   has
    *
    *   @param string $dot
    *   @return bool
    **/
    public function has(string $dot)
    {
        return ArrayDot::has($this->getArrayCopy(), $dot);
    }
    
    /**
    *   remove
    *
    *   @param string $dot
    *   @return Config
    **/
    public function remove(string $dot): Config
    {
        $data = ArrayDot::remove($this->getArrayCopy(), $dot);
        parent::__construct(
            $data,
            ArrayObject::ARRAY_AS_PROPS
        );
        return $this;
    }
}
