<?php

/**
 *  Config
 *
 * @version 210822
 */

declare(strict_types=1);

namespace Concerto\conf;

use ArrayObject;
use Concerto\arrays\ArrayDot;
use Concerto\conf\{
  ConfigInterface,
  ConfigReaderInterface,
  ConfigWritableInterface,
};

class Config extends ArrayObject implements
    ConfigInterface,
    ConfirWraitableInterface
{
    /**
     *   __construct
     *
     * @param array $dataset
     */
    public function __construct(
        array $dataset
    ) {
        parent::__construct(
            $dataset,
            ArrayObject::ARRAY_AS_PROPS
        );
    }

    /**
     *   {inherit}
     **/
    public function has(
        string $name
    ): bool {
        return ArrayDot::has(
            $this->getArrayCopy(),
            $name
        );
    }

    /**
     *   {inherit}
     **/
    public function get(
        string $name
    ): mixed {
        return ArrayDot::get(
            $this->getArrayCopy(),
            $name
        );
    }

    /**
     *   {inherit}
     **/
    public function set(
        string $name,
        $val
    ): static {
        $data = ArrayDot::set(
            $this->getArrayCopy(),
            $name,
            $val
        );
        return new static($data);
    }

    /**
     *   {inherit}
     **/
    public function remove(
        string $name
    ): static {
        $data = ArrayDot::remove(
            $this->getArrayCopy(),
            $name
        );
        return new static($data);
    }

    /**
     *   replace
     *
     * @param  array $dataset
     * @return static
     **/
    public function replace(
        ConfigReaderInterface $reader
    ): static {
        $src = $this->getArrayCopy();
        $replaced_data = array_replace_recursive(
            $this->getArrayCopy(),
            $dataset
        );
        return new static($replaced_data);
    }

    /**
     *
     **/
    public function extract(
        string $name
    ): static {


        return new static([]);
    }
}
