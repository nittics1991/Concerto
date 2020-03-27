<?php


viewに対するconfig

lang: jp,
date_format:Ymd,
nendo_format:function(FiscalYearObject $fiscalYear) {
    return $fiscalYear->isFirst()?
        "{$fiscalYear->year}年上期":
        "{$fiscalYear->year}年下期":
},
date_to_nendo_format:function(DateTimeInterface $date) {
    $year = (int)$date->format('Y');
    $month = (int)$date->format('n');
    
    if ($month >= 4 && $month <= 9) {
        return "{$year}上期";
    }
    
    if ($month >= 10 && $month <= 12) {
        return "{$year}下期";
    }
    
    $year--;
    return "{$year}下期";
},










