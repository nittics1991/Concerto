<?php

//namespace Concerto\Valodator;

//use \SplFileObject;

class FileObjectDataMapper implements FileObjectDataMapperInterface
{
    /**
    *   path
    *
    *   @var string
    **/
    protected $path;
    
    /**
    *   CSV control
    *
    *   @var string
    **/
    protected $delimiter = '\t';
    protected $enclosure = '"';
    protected $escape = '\\';
    
    /**
    *   __construct
    *
    *   @param string $path
    **/
    public function __construct(string $path)
    {
        $this->path = $path;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function all(): array
    {
        $file = $this->getFileObject('r')
            ->setFlags(SplFileObject::READ_CSV);
        
        $result = [];
        foreach ($file as $array) {
            $result[] = $array;
        }
        
        unset($file);
        return $result;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function findById(string $id): array
    {
        $file = $this->getFileObject('r')
            ->setFlags(SplFileObject::READ_CSV);
        
        $result = [];
        foreach ($file as $array) {
            if ($array[0] === $id) {
                $result = $array;
                break;
            }
        }
        
        unset($file);
        return $result;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function create(ReportFileFinder $finder)
    {
        $file = $this->getFileObject('w');
        
        $result = [];
        foreach ($finder as $list) {
            $file->fputcsv(
                $list,
                $this->delimiter,
                $this->enclosure,
                $this->escape
            );
        }
        
        unset($file);
        return $this;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function update(ReportFileFinder $finder)
    {
        $file = $this->getFileObject('a+')
            ->setFlags(SplFileObject::READ_CSV);
        
        $lastData = $file->seek(-1)->fgetcsv(
            $this->delimiter,
            $this->enclosure,
            $this->escape
        );
        
        $lastData = $lastData ?? [];
        
        $found = false;
        foreach ($finder as $list) {
            if ($found) {
                $file->fputcsv(
                    $list,
                    $this->delimiter,
                    $this->enclosure,
                    $this->escape
                );
            } elseif ($list === $lastData) {
                $found = true;
            }
        }
        
        unset($file);
        return $this;
    }
    
    /**
    *   {inherit}
    *
    **/
    public function delete()
    {
        unlink($this->path);
    }
    
    /**
    *   setCsvControl
    *
    *   @param ?string $delimiter
    *   @param ?string $enclosure
    *   @param ?string $escape
    *   @return $this
    **/
    public function setCsvControl(
        ?string $delimiter = null,
        ?string $enclosure = null,
        ?string $escape = null
    ) {
        if (isset($delimiter)) {
            $this->delimiter = $delimiter;
        }
        
        if (isset($enclosure)) {
            $this->enclosure = $enclosure;
        }
        
        if (isset($escape)) {
            $this->escape = $escape;
        }
        
        return $this;
    }
    
    /**
    *   getFileObject
    *
    *   @param string $mode
    *   @return SplFileObject
    **/
    protected function getFileObject(string $mode): SplFileObject
    {
        return (new SplFileObject($this->path, $mode))
            ->setCsvControl(
                $this->delimiter,
                $this->enclosure,
                $this->escape
            );
    }
}
