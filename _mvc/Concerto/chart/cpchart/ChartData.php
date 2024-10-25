<?php

/**
 *   ChartData
 *
 * @version 240823
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use InvalidArgumentException;
use IteratorAggregate;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Traversable;

/**
*   @implements IteratorAggregate<RecursiveIteratorIterator>
*/
class ChartData implements IteratorAggregate
{
    /**
    *   iniData
    *
    *   @var mixed[]
    */
    protected array $container = [];

    /**
    *   __construct
    *
    *   @param mixed[] $params
    */
    public function __construct(
        array $params = []
    ) {
        $this->container = $params;
    }

    /**
    *   bind
    *
    *   @param mixed[] $params
    *   @return static
    */
    public function bind(
        array $params
    ): static {
        $this->container = array_replace_recursive(
            $this->container,
            $params
        );

        return $this;
    }

    /**
    *   get
    *
    *   @param ?string $name
    *   @return mixed
    */
    public function get(
        ?string $name = null
    ): mixed {
        if (!isset($name)) {
            return $this->container;
        }

        return isset($this->container[$name]) ?
            $this->container[$name] : null;
    }

    /**
    *   @inheritDoc
    *
    *   @return RecursiveIteratorIterator
    */
    public function getIterator(): Traversable
    {
        return new RecursiveIteratorIterator(
            new RecursiveArrayIterator($this->container),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }

    /**
    *   import
    *
    *   @param string $file
    *   @return $this
    */
    public function import(
        string $file
    ): static {
        if (!file_exists($file)) {
            throw new InvalidArgumentException(
                "file not found:{$file}"
            );
        }

        $this->container = include $file;

        return $this;
    }

    /**
    *   getTableData
    *
    *   @return mixed[]
    */
    public function getTableData(): array
    {
        $points = (array)$this->container['points'];

        $dataset = $this->container['dataset'];

        $abscissa = $dataset['Abscissa'][0] ?? null;

        if (isset($abscissa)) {
            $backup = $points[$abscissa];

            unset($points[$abscissa]);

            $points = array_merge(
                [$abscissa => $backup],
                $points
            );
        }

        $result = [];

        $withoutDescripionTable =
            function ($points, $abscissa) use ($result) {
                foreach ($points as $name => $values) {
                    $result[] = ($name == $abscissa) ?
                    array_merge([''], $values)
                    : array_merge([$name], $values);
                }

                return $result;
            };

        //not have description
        if (!isset($dataset['SerieDescription'])) {
            return $withoutDescripionTable(
                $points,
                $abscissa
            );
        }

        //have description
        $descriptions = [];

        foreach ((array)$dataset['SerieDescription'] as $format) {
            $descriptions[$format[0]] = $format[1];
        }

        foreach ($points as $name => $values) {
            if ($name == $abscissa) {
                $result[] = array_merge(
                    [''],
                    (array)$values
                );
            } elseif (
                array_key_exists($name, (array)$descriptions)
            ) {
                $result[] = array_merge(
                    [$descriptions[$name]],
                    (array)$values
                );
            } else {
                $result[] = array_merge(
                    [$name],
                    (array)$values
                );
            }
        }
        return $result;
    }
}
