<?php

/**
*   設定
*
*   @version 221206
*/

declare(strict_types=1);

namespace Concerto\conf;

use ArrayObject;
use Concerto\arrays\ArrayDot;
use Concerto\conf\ConfigReaderArray;

/**
*   @template TValue
*   @extends ArrayObject<int|string, TValue>
*/
class Config extends ArrayObject
{
    /**
    *   __construct
    *
    *   @param ConfigReaderInterface $reader
    */
    public function __construct(
        ConfigReaderInterface $reader
    ) {
        parent::__construct(
            $reader->read(),
            ArrayObject::ARRAY_AS_PROPS
        );
    }

    /**
    *   replace
    *
    *   @param ConfigReaderInterface $reader
    *   @return static
    */
    public function replace(
        ConfigReaderInterface $reader
    ): static {
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
    *   @return static
    */
    public function set(
        string $dot,
        mixed $val
    ): static {
        $data = ArrayDot::set(
            $this->getArrayCopy(),
            $dot,
            $val
        );

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
    */
    public function get(
        string $dot
    ): mixed {
        return ArrayDot::get(
            $this->getArrayCopy(),
            $dot
        );
    }

    /**
    *   has
    *
    *   @param string $dot
    *   @return bool
    */
    public function has(
        string $dot
    ): bool {
        return ArrayDot::has(
            $this->getArrayCopy(),
            $dot
        );
    }

    /**
    *   remove
    *
    *   @param string $dot
    *   @return static
    */
    public function remove(string $dot): static
    {
        $data = ArrayDot::remove(
            $this->getArrayCopy(),
            $dot
        );

        parent::__construct(
            $data,
            ArrayObject::ARRAY_AS_PROPS
        );

        return $this;
    }
}
