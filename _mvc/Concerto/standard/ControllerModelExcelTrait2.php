<?php

/**
*   ControllerModelExcelTrait
*       skillで使用中
*
*   @version 221214
*/

declare(strict_types=1);

namespace Concerto\standard;

trait ControllerModelExcelTrait2
{
    /**
    *   EXCELファイル作成
    *
    *   @param string $template_file_path
    *   @return string
    */
    public function buildExcel(
        string $template_file_path
    ): string {
        $temp_path = $this->createTempPath(
            $template_file_path
        );

        $this->clearTempFile($temp_path);

        $excel = $this->factory->getExcelManager(
            $template_file_path
        );

        $report_file_path =
            $temp_path .
            DIRECTORY_SEPARATOR .
            $this->createDownloadFileName(
                $template_file_path
            );

        $excel->rename($report_file_path);

        $excel->report(
            $this->factory->getExcelBuilder()
        );

        return $report_file_path;
    }

    /**
    *   createTempPath
    *
    *   @param string $template_file_path
    *   @return string
    */
    private function createTempPath(
        string $template_file_path
    ): string {
        return
            dirname($template_file_path) .
            DIRECTORY_SEPARATOR .
            'tmp';
    }

    /**
    *   clearTempFile
    *
    *   @param string $temp_path
    *   @return void
    */
    protected function clearTempFile(
        string $temp_path
    ): void {
        $fileOperation = $this->factory->getFileOperation();
        $fileOperation->clearTempDir($temp_path, 1);
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
