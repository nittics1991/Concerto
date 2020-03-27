<?php

//namespace Concerto\Valodator;

//use \SplFileObject;

interface FileObjectDataMapperInterface
{
    /**
    *   all
    *
    *   @return array
    **/
    public function all(): array;
    
    /**
    *   all
    *
    *   @param string $id
    *   @return array
    **/
    public function findById(string $id): array;
    
    /**
    *   create
    *
    *   ReportFileFinder $finder
    **/
    public function create(ReportFileFinder $finder);
    
    /**
    *   update
    *
    *   ReportFileFinder $finder
    **/
    public function update(ReportFileFinder $finder);
    
    /**
    *   delete
    *
    **/
    public function delete();
}
