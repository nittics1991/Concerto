<?php

/**
*   ControllerModelExcelTrait
*
*   @version 241011
*/

declare(strict_types=1);

namespace Concerto\standard;

use RuntimeException;

trait ControllerModelExcelTrait
{
    /**
    *   EXCELファイル作成
    *
    *   @param string $template_file_path
    *   @return string file path
    */
    public function buildExcel(
        string $template_file_path
    ): string {
        $download_file_name = $this->createDownloadFileName(
            $template_file_path,
        );

        $excelBuilder = $this->factory->getExcelBuilder(
            $template_file_path,
        );

        $work_file_path = $excelBuilder->build();

        $work_dir = dirname($work_file_path);

        $file_path = $work_dir .
            DIRECTORY_SEPARATOR .
            $download_file_name;

        $renamed = rename(
            $work_file_path,
            $file_path,
        );

        if ($renamed === false) {
            throw new RuntimeException(
                "faild:{$work_file_path} to {$file_path}",
            );
        }

        return $file_path;
    }

    /**
    *   createDownloadFileName
    *
    *   @param string $template_file_path
    *   @return string
    */
    protected function createDownloadFileName(
        string $template_file_path
    ): string {
        return $this->authUser->id .
            pathinfo(
                $template_file_path,
                PATHINFO_FILENAME
            ) .
            date('Ymd_His') .
            '.' .
            pathinfo(
                $template_file_path,
                PATHINFO_EXTENSION
            );
    }
}
