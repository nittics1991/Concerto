<?php

/**
*   ExcelSheet
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel;

use DateTimeInterface;
use InvalidArgumentException;
use stdClass;
use Concerto\excel\ExcelAddress;

class ExcelSheet
{
    /**
    *   @var string
    */
    private string $sheet_name;

    /**
    *   @var stdClass[]
    */
    private array $values = [];

    /**
    *   @var array<array<int|float|string|DateTimeInterface>>
    */
    private array $mapping_data = [];

    /**
    *   __construct
    *
    *   @param string $sheet_name
    */
    public function __construct(
        string $sheet_name,
    ) {
        $this->sheet_name = $sheet_name;
    }

    /**
    *   getSheetName
    *
    *   @return string
    */
    public function getSheetName(): string
    {
        return $this->sheet_name;
    }

    /**
    *   setMappingData
    *
    *   @param array<array<int|float|string|DateTimeInterface>> $data
    *   @return static
    */
    public function setMappingData(
        array $data,
    ): static {
        $this->validateData($data);

        $this->mapping_data = $data;

        return $this;
    }

    /**
    *   addData
    *
    *   @param string $cell_address
    *   @param array<mixed[]> $data
    *   @param bool $toIndexed
    *   @return static
    */
    public function addData(
        string $cell_address,
        array $data,
        bool $toIndexed = false,
    ): static {
        $this->validateData($data);

        $container = new stdClass();

        $container->address = $cell_address;

        $container->data = $toIndexed ?
            $this->toIndexed($data) :
            $data;

        $this->values[] = $container;

        return $this;
    }

    /**
    *   validateData
    *
    *   @param array<mixed[]> $data
    *   @return void
    */
    private function validateData(
        array $data,
    ): void {
        foreach ($data as $row_no => $row) {
            if (!is_array($row)) {
                throw new InvalidArgumentException(
                    "data must be 2D. row:{$row_no}",
                );
            }

            foreach ($row as $column_no => $column) {
                if (
                    !is_int($column) &&
                    !is_float($column) &&
                    !is_string($column) &&
                    is_object($column) &&
                    !$column instanceof DateTimeInterface
                ) {
                    throw new InvalidArgumentException(
                        "must be int|float|string|DateTimeInterface." .
                        "row:{$row_no} column:{$column_no}",
                    );
                }
            }
        }
    }

    /**
    *   toIndexed
    *
    *   @param array<mixed[]> $data
    *   @return array<mixed[]>
    */
    private function toIndexed(
        array $data,
    ): array {
        $data = array_values($data);

        return array_map(
            fn($array) => array_values($array),
            $data,
        );
    }

    /**
    *   expandData
    *
    *   @return static
    */
    public function expandData(): static
    {
        $this->mappingDataAll();

        $this->sortMappingData();

        return $this;
    }

    /**
    *   mappingDataAll
    *
    *   @return void
    */
    private function mappingDataAll(): void
    {
        foreach ($this->values as $container) {
            $this->mappingData($container);
        }
    }

    /**
    *   mappingData
    *
    *   @param stdClass $container
    *   @return void
    */
    private function mappingData(
        stdClass $container,
    ): void {
        $locations = ExcelAddress::addressToLocation(
            $container->address,
        );

        $row_no = $locations[0];

        foreach ($container->data as $row) {
            $range_data = $this->adjustCellNo(
                $locations[1],
                $row,
            );

            $this->mapping_data[$row_no] = array_replace(
                $this->mapping_data[$row_no] ?? [],
                $range_data,
            );

            $row_no++;
        }
    }

    /**
    *   adjustCellNo
    *
    *   @param int $start_cell_no
    *   @param array<int|float|string|DateTimeInterface> $row
    *   @return array<int|float|string|DateTimeInterface>
    */
    private function adjustCellNo(
        int $start_cell_no,
        array $row,
    ): array {
        $adjusted = [];

        foreach ($row as $cell_no => $val) {
            $adjusted[(int)$cell_no + $start_cell_no] = $val;
        }

        return $adjusted;
    }

    /**
    *   sortMappingData
    *
    *   @return void
    */
    private function sortMappingData(): void
    {
        ksort($this->mapping_data, SORT_NATURAL);

        foreach ($this->mapping_data as &$rows) {
            ksort($rows, SORT_NATURAL);
        }

        unset($rows);
    }

    /**
    *   toArray
    *
    *   @return array<array<int|float|string|DateTimeInterface>>
    */
    public function toArray(): array
    {
        $this->expandData();

        return $this->mapping_data;
    }
}
