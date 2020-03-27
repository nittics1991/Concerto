<?php

/**
 *   ChartData
 *
 * @version 191216
 **/

declare(strict_types=1);

namespace Concerto\chart\cpchart;

use IteratorAggregate;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use InvalidArgumentException;

class ChartData implements IteratorAggregate
{
    /**
     *   iniData
     *
     * @var array
     **/
    protected $container = [];
    
    /**
     *   __construct
     *
     * @param array $params
     **/
    public function __construct(array $params = [])
    {
        $this->container = $params;
    }
    
    /**
     *   bind
     *
     * @param  array $params
     * @return $this
     **/
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
     * @param  ?string $name
     * @return array
     **/
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
     **/
    public function getIterator()
    {
        return new RecursiveIteratorIterator(
            new RecursiveArrayIterator($this->container),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }
    
    /**
     *   import
     *
     * @param  string $file
     * @return $this
     **/
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
     * @return array
     **/
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
        
        $withoutDescripionTable = function ($points, $abscissa) {
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
