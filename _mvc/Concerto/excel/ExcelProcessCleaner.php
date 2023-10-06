<?php

/**
*   EXCELプロセスクリーナ
*
*   @version 221208
*/

declare(strict_types=1);

namespace Concerto\excel;

use DateTimeImmutable;
use DateInterval;
use Concerto\win\{
    DateTimeStringParser,
    Win32Process
};

class ExcelProcessCleaner
{
    /**
    *   @var DateInterval
    */
    private DateInterval $interval;

    /**
    *   __invoke
    *
    *   @param ?DateInterval $interval
    *   @return int[] 閉じたPID
    */
    public function __invoke(
        DateInterval $interval = null
    ): array {
        $this->interval = $interval ??
            new DateInterval('PT10M');

        return $this->execute();
    }

    /**
    *   execute
    *
    *   @return int[]
    */
    private function execute(): array
    {
        $processes =  (new Win32Process())
            ->findByName('EXCEL.EXE');

        $closedProcess = [];

        $limitTime = (new DateTimeImmutable())
            ->sub($this->interval);

        foreach ($processes as $process) {
            $createDate = DateTimeStringParser::parse(
                $process->CreationDate
            );

            if ($createDate < $limitTime) {
                $process->terminate();
                $closedProcess[] = $process->Handle;
            }
        }

        return $closedProcess;
    }
}
