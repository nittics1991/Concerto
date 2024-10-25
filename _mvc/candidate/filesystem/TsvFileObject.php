<?php

/**
*   TsvFileObject
*
*   @ver 210922
*   @example tsv data
*       true\t123\t3.33\tタブ区切り 囲い文字なし\t2000-1-1 00:00
*/

declare(strict_types=1);

namespace candidate\filesystem;

use RuntimeException;
use SplFileObject;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
};

class TsvFileObject extends SplFileObject
{
    /**
    *   readTsv
    *
    *   @param DataMapperInterface $dataMapper
    *   @param ?string $encoding
    *   @return DataModelInterface[]
    **/
    public function readTsv(
        DataMapperInterface $dataMapper,
        ?string $encoding = null,
    ): array {
        $this->setFlags(
            SplFileObject::DROP_NEW_LINE |
            SplFileObject::READ_AHEAD |
            SplFileObject::SKIP_EMPTY
        );

        if (
            mb_regex_encoding(
                (string)ini_get('default_charset')
            ) === false
        ) {
            throw new RuntimeException(
                "failure set encoding"
            );
        }

        $rows = [];
        $column_names = [];

        foreach ($this as $row) {
            $encoded_row = $this->convertEncoding(
                $row,
                $encoding,
            );

            $columns = $this->splitRow($encoded_row);

            if ($column_names === []) {
                $column_names = $columns;
                continue;
            }

            $rows[] = $this->createModelData(
                $dataMapper,
                $column_names,
                $columns,
            );
        }
        return $rows;
    }

    /**
    *   convertEncoding
    *
    *   @param string $row
    *   @param ?string $encoding
    *   @return string
    */
    protected function convertEncoding(
        string $row,
        ?string $encoding = null,
    ): string {
        if (
            $encoding === null ||
            ini_get('default_charset') === $encoding
        ) {
            return $row;
        }

        $result = mb_convert_encoding(
            $row,
            (string)ini_get('default_charset'),
            $encoding,
        );

        if ($result === false) {
            throw new RuntimeException(
                "failure convert encoding:{$row}"
            );
        }
        return $result;
    }

    /**
    *   splitRow
    *
    *   @param string $row
    *   @return string[]
    */
    protected function splitRow(
        string $row,
    ): array {
        $columns = mb_split(
            "\t",
            $row,
        );

        if ($columns === false) {
            throw new RuntimeException(
                "failure Column extraction:{$row}"
            );
        }
        return $columns;
    }

    /**
    *   createModelData
    *
    *   @param DataMapperInterface $dataMapper
    *   @param string[] $column_names
    *   @param string[] $data
    *   @return DataModelInterface
    */
    protected function createModelData(
        DataMapperInterface $dataMapper,
        array $column_names,
        array $data,
    ): DataModelInterface {
        static $dataModelTemplate;

        if ($dataModelTemplate === null) {
            $dataModelTemplate = $dataMapper->createModel();
        }

        $dataset = array_combine(
            $column_names,
            $data,
        );

        $dataModel = clone $dataModelTemplate;
        return $dataModel->fromArray($dataset);
    }
}
