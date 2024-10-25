<?php

/**
*   ExcelAddress
*
*   @version 240919
*/

declare(strict_types=1);

namespace Concerto\excel;

use RuntimeException;

class ExcelAddress
{
    /**
    *   addressToLocation
    *
    *   @param string $address
    *   @return int[]
    *       'E3'=>[3,5]
    *       'A2:E4'=>[2,1,4,5]
    */
    public static function addressToLocation(
        string $address,
    ): array {
        $cells = mb_split(':', $address);

        if ($cells === false) {
            throw new RuntimeException(
                "string manipulation error:{$address}",
            );
        }

        $locations = [];

        foreach ($cells as $cell) {
            $locations[] = self::extractRowAddress($cell);
            $locations[] = self::extractColumnAddress($cell);
        }

        return $locations;
    }

    /**
    *   extractRowAddress
    *
    *   @param string $address
    *   @return int
    */
    private static function extractRowAddress(
        string $address,
    ): int {
        $column_string = mb_ereg_replace(
            '[$A-Za-z]',
            '',
            $address,
        );

        if (
            $column_string === null ||
            $column_string === false ||
            $column_string === ''
        ) {
            throw new RuntimeException(
                "extract column error:{$address}",
            );
        }

        return intval($column_string);
    }

    /**
    *   extractColumnAddress
    *
    *   @param string $address
    *   @return int
    */
    private static function extractColumnAddress(
        string $address,
    ): int {
        $row_string = mb_ereg_replace(
            '[$0-9]',
            '',
            $address,
        );

        if (
            $row_string === null ||
            $row_string === false ||
            $row_string === ''
        ) {
            throw new RuntimeException(
                "extract row error:{$address}",
            );
        }

        $row_string = mb_strtoupper($row_string);

        $row_chars = mb_str_split($row_string, 1);
        krsort($row_chars);

        $row_no = 0;
        $rate = 1;

        foreach ($row_chars as $str) {
            $row_no += (ord($str) - 65 + 1) * $rate;
            $rate *= 26;
        }

        return $row_no;
    }

    /**
    *   locationToAddress
    *
    *   @param int[] $location
    *   @return string
    *       [3,5]=>'E3'
    *       [2,1,4,5]=>'A2:E4'
    */
    public static function locationToAddress(
        array $location,
    ): string {
        if (!in_array(count($location), [2, 4])) {
            throw new RuntimeException(
                "location error:" .
                json_encode($location),
            );
        }

        $address = '';

        foreach ([1, 0, 3, 2] as $pos) {
            if (!isset($location[$pos])) {
                break;
            }

            $address .= match ($pos) {
                0, 2 => strval($location[$pos]),
                1 => self::columnNoToAddress($location[$pos]),
                3 => ':' . self::columnNoToAddress($location[$pos]),
            };
        }

        return $address;
    }

    /**
    *   columnNoToAddress
    *
    *   @param int $column_no
    *   @param string $prev
    *   @return string
    */
    private static function columnNoToAddress(
        int $column_no,
        string $prev = '',
    ): string {
        $remainder = ($column_no - 1) % 26;

        $divided = (int)floor(($column_no - 1) / 26);

        $result = chr($remainder + 65) . $prev;

        if ($divided > 0) {
            return self::columnNoToAddress(
                $divided,
                $result,
            );
        }

        return $result;
    }
}
