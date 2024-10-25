<?php

/**
*   CsvToDataMapper
*
*   @ver 210918
**/

declare(strict_types=1);

namespace Concerto\sql;

use Concerto\standard\DataMapperInterface;

class CsvToDataMapper
{
    /**
    *  @var DataMapperInterface
    **/
    private DataMapperInterface $dataModel;

    /**
    *   __construct
    *
    *   @param DataMapperInterface $dataModel
    **/
    public function __construct(
      DataMapperInterface $dataModel
    ){
      $this->dataModel = $dataModel;
    }

    /**
    *   import
    *
    *   @param string $file_path
    *   @param ?string $separator
    *   @param ?string $enclosure
    *   @param ?string $escape
    *   @return void
    **/
    public function import(
      string $file_path,
      ?string $separator = ',',
      ?string $enclosure = "\"",
      ?string $escape = "\\",
    ):void {
      $splFileObject = new SplFileObject(
        $file_path,
        'r',
        false,
      );

      




    }
}
