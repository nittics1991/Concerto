<?php

//namespace Concerto\Valodator;


class PhpCsReportFileFactory implements ReportFileFactoryInterface
{
    public function create(string $path): array
    {
        return (new CheckstyleParser($path))
            ->countByXpath('/checkstyle/file/error');
    }
}
