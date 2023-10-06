<?php

/**
*   CsvModelReader
*
*   @ver 210918
**/

declare(strict_types=1);

namespace Concerto\sql;

use SplFileObject;
use Concerto\standard\DataMapperInterface;

class CsvModelReader extends SplFileObject
{

    /**
    *   import
    *
    *   @param DataMapperInterface $dataMapper
    *   @return void
    **/
    public function import(
      DataMapperInterface $dataMapper,
    ):void {
      $rows = [];
      $dataModelTemplate = $dataMapper->createModel();

      foreach($this as $row) {
        $dataModel = clone $dataModelTemplate;
        $rows[] = $dataMapper->fromArray($row);
      }

      $dataMapper->insert($rows);
    }

    /**
    *   importCsv
    *
    *   @param DataMapperInterface $dataMapper
    *   @return void
    **/
    public function importCsv(
      DataMapperInterface $dataMapper,
    ):void {
      $this->setFlags(
        SplFileObject::READ_CSV |
        SplFileObject::READ_AHEAD |
        SplFileObject::SKIP_EMPTY
      );

      $this->setCsvControl(
        ',',
        "\"",
        "\\",
      );

      $this->import($dataMapper);
    }
}
