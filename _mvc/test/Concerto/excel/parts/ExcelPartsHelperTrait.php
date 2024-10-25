<?php

declare(strict_types=1);

namespace test\Concerto\excel\parts;

use RuntimeException;
use Concerto\excel\ExcelArchive;

trait ExcelPartsHelperTrait
{
    public static function copyTemplateFile(
        string $template_file_name,
    ): string {
        $src = implode(
            DIRECTORY_SEPARATOR,
            [
                __DIR__,
                '..',
                'data',
                $template_file_name,
            ],
        );

        $dest = implode(
            DIRECTORY_SEPARATOR,
            [
                sys_get_temp_dir(),
                uniqid() .
                '.' .
                    pathinfo(
                        $template_file_name,
                        PATHINFO_EXTENSION,
                    ),
            ],
        );

        $result = copy($src, $dest);

        if (!$result) {
            throw new RuntimeException(
                "work file copy error:{$template_file_name}"
            );
        }

        return $dest;
    }

    public static function createExcelArchive(
        string $template_file_name,
    ): ExcelArchive {
        $zip_file = self::copyTemplateFile($template_file_name);

        return new ExcelArchive($zip_file);
    }
}
