<?php

/**
*   EXCELプロセスクリーナ
*
*   @version 191018
*/

declare(strict_types=1);

namespace Concerto\excel;

use DateTime;
use DateInterval;
use Concerto\win\DateTimeStringParser;
use Concerto\win\Win32Process;

class ExcelProcessCleaner
{
    /**
    *   interval
    *
    *   @var DateInterval
    */
    private $interval;

    /**
    *   __invoke
    *
    *   @param ?DateInterval $interval
    *   @return int[] 閉じたPID
    */
    public function __invoke(DateInterval $interval = null): array
    {
        $this->interval = ($interval) ? $interval : new DateInterval('PT10M');
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
        $limitTime = (new DateTime())->sub($this->interval);

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
