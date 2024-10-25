<?php

/**
*   UserTableModel
*
*   @version
*/

declare(strict_types=1);

namespace pgperf\model;

use pgperf\model\AbstractPgPerfModel

class UserTableModel extends AbstractPgPerfModel
{
    //public? protected?
    
    public int $sid;
    public string $oid;
    public string $schemaname;
    public string $relname;
    public int $seq_scan;
    public int $seq_tup_read;
    public int $idx_scan;
    public int $idx_tup_fetch;
    public string $n_tup_ins;
    public string $n_tup_upd;
    public string $n_tup_del;
    public string $n_tup_hot_upd;
    public string $n_live_tup;
    public string $n_dead_tup;
    public DateTimeImmutable $last_vacuum;
    public DateTimeImmutable $last_autovacuum;
    public DateTimeImmutable $last_analyze;
    public DateTimeImmutable $last_autoanalyze;
    public int $vacuum_count;
    public int $autovacuum_count;
    public int $analyze_count;
    public int $autoanalyze_count;
}
