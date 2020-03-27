<?php

/**
*   WorkDir
*
*   @version 170428
*/

declare(strict_types=1);

namespace Concerto\filesystem;

use CallbackFilterIterator;
use FilesystemIterator;
use Iterator;
use IteratorAggregate;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use DateTimeImmutable;
use DateInterval;

class WorkDir implements IteratorAggregate
{
    /**
    *   path
    *
    *   @var string
    **/
    protected $path;
    
    /**
    *   iterator
    *
    *   @var Iterator
    **/
    protected $iterator;
    
    /**
    *   __construct
    *
    *   @param ?string $path
    **/
    public function __construct(?string $path = null)
    {
        if (!is_string($path)) {
            $this->path = sys_get_temp_dir();
        } else {
            $this->path = $path;
            $this->create();
        }
        
        $this->iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->path,
                FilesystemIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );
    }
    
    /**
    *   create
    *
    *   @return object $this
    **/
    protected function create()
    {
        if (file_exists($this->path)) {
            return $this;
        }
        
        if (mkdir($this->path, 0777, true) == false) {
            throw new RuntimeException("create falrure:{$this->path}");
        }
        return $this;
    }
    
    /**
    *   get
    *
    *   @return string
    **/
    public function get()
    {
        return $this->path;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function getIterator()
    {
        return $this->iterator;
    }
    
    /**
    *   clear
    *
    *   @return array failure path
    **/
    public function clear()
    {
        return $this->doClear($this->iterator);
    }
    
    /**
    *   doClear
    *
    *   @param iterable $iterator
    *   @return array failure path
    **/
    public function doClear(iterable $iterator)
    {
        $failure = [];
        
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                if (rmDir($fileInfo->getPathName()) == false) {
                    $failure[] = $fileInfo->getPathName();
                }
            } else {
                if (unlink($fileInfo->getPathName()) == false) {
                    $failure[] = $fileInfo->getPathName();
                }
            }
        }
        return $failure;
    }
    
    /**
    *   delete
    *
    *   @return array failure path
    **/
    public function delete()
    {
        $failure = $this->clear();
        if (rmDIr($this->path) == false) {
            $failure[] = $this->path;
        }
        return $failure;
    }
    
    /**
    *   指定日以前のタイムスタンプで削除
    *
    *   @param string $interval @see DateInterval
    *   @return array failure path
    **/
    public function clearBeforeDate(string $interval)
    {
        $limit = (new DateTimeImmutable())
            ->sub(new DateInterval($interval))
            ->getTimestamp()
            ;
        
        $iterator = new CallbackFilterIterator(
            $this->iterator,
            function ($fileinfo, $key, $iterator) use ($limit) {
                return ($fileinfo->getMTime() < $limit);
            }
        );
        return $this->doClear($iterator);
    }
}
