<?php

/**
 *   ChartData
 *
 * @version 210614
 */

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use InvalidArgumentException;
use IteratorAggregate;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Traversable;

class ChartData implements IteratorAggregate
{
    /**
     *   iniData
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     *   __construct
     *mixed[]
     * @param mixed[] $params
     */
    public function __construct(array $params = [])
    {
        $this->container = $params;
    }

    /**
     *   bind
     *
     * @param mixed[] $params
     * @return $this
     */
    public function bind(array $params)
    {
        $this->container = array_replace_recursive(
            $this->container,
            $params
        );
        return $this;
    }

    /**
     *   get
     *
     * @param ?string $name
     * @return mixed[]
     */
    public function get($name = null)
    {
        if (!isset($name)) {
            return $this->container;
        }
        return (isset($this->container[$name])) ?
            $this->container[$name] : null;
    }

    /**
     *   {inherit}
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
     * @param string $file
     * @return $this
     */
    public function import($file)
    {
        if (!file_exists($file)) {
            throw new InvalidArgumentException("file not found:{$file}");
        }
        $this->container = include $file;
        return $this;
    }

    /**
     *   getTableData
     *
     * @return mixed[]
     */
    public function getTableData()
    {
        $points = $this->container['points'];
        $dataset = $this->container['dataset'];
        $abscissa = isset($dataset['Abscissa'][0]) ?
            $dataset['Abscissa'][0] : null;

        if (isset($abscissa)) {
            $backup = $points[$abscissa];
            unset($points[$abscissa]);
            $points = array_merge([$abscissa => $backup], $points);
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
            return $withoutDescripionTable($points, $abscissa);
        }

        //have description
        $descriptions = [];
        foreach ($dataset['SerieDescription'] as $format) {
            $descriptions[$format[0]] = $format[1];
        }

        foreach ($points as $name => $values) {
            if ($name == $abscissa) {
                $result[] = array_merge([''], $values);
            } elseif (array_key_exists($name, $descriptions)) {
                $result[] = array_merge([$descriptions[$name]], $values);
            } else {
                $result[] = array_merge([$name], $values);
            }
        }
        return $result;
    }
}
