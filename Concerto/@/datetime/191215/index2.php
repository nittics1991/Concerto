<?php

factoyでdatetimeを生成
formatterでconfigで定義されたformatを出力する

class DateFactory
{
public const IMMUTABLE = DateTimeImmutable::class;
public const MUTABLE = DateTime::class;
    
    protected $className;
    protected $baseMonth = 4;
    protected $baseDay = 1;
    
public functiom __construct(
    string $className = DateFactory::IMMUTABLE
) {
        $this->className = $className;
    }
    
protected function buildBaseDate(int $year): DateTimeInterface
{
    return (new $this->className())
        ->setDate($year, $this->baseMonth, $this->baseDay)
        ->setTime(0, 0, 0);
}
    
protected function reDefineYear(DateTime $dummyDate, int $targetYear): DateTimeInterface
{
    if ((int)$dummyDate->format('Y') === $targetYear) {
        return $dummyDate;
    }
    return $dummyDate->modify('-1 month');
}
    
public functiom fiscalYear(string $dateString): DateTimeInterface
    {
        $year = (int)mb_substr($dateString, 0, 4);
        $half = mb_substr($dateString, -1);
        
if ($half === 'K') {
    return $this->buildBaseDate($year);
} elseif ($half === 'S') {
    $dummyDate  $this->buildBaseDate($year)
        ->modify('+6 month');
    return $this->reDefineYear($dumyDate, $year);
    ]
    throw new InvalidArgumentException(
        "must be yyyyK|yyyyS:{$dateString}"
    );
}
    
public functiom fiscalYearFromDateTime(DateTimeInterface $date): DateTimeInterface
    {
        $year = (int)$date->format('Y');
        $dummyDate = $this->buildBaseDate($year);
        
        
        $diff = (int)($date->diff($dummyDate, true)
            ->format('%m'));
        
        if ($diff > 6) {
            return new static()
        }
    
        public functiom quoter(string $dateString): DateTimeInterface
        {
        $year = (int)mb_substr($dateString, 0, 4);
        $quoter = mb_substr($dateString, -1);
        
        if ($quoter === 'Q1') {
            return $this->buildBaseDate($year);
        } elseif ($quoter === 'Q2') {
            $dummyDate  $this->buildBaseDate($year)
                ->modify('+3 month');
            return $this->reDefineYear($dumyDate, $year);
        } elseif ($quoter === 'Q3') {
            $dummyDate  $this->buildBaseDate($year)
                ->modify('+6 month');
            return $this->reDefineYear($dumyDate, $year);
        } elseif ($quoter === 'Q4') {
            $dummyDate  $this->buildBaseDate($year)
                ->modify('+9 month');
            return $this->reDefineYear($dumyDate, $year);
        }
        throw new InvalidArgumentException(
            "must be yyyyQn. n=1-4:{$dateString}"
        );
        }
    
        public functiom quoterFromDateTime(DateTimeInterface $date): DateTimeInterface
        {
        $year = $date->format('Y');
        $month = (int)$date->format('n');
        
        if ($month >= $this->baseMonth && $month <= ($this->baseMonth + 6)) {
            return new static("{$year}K");
        }
        return new static("{$year}S");
        }
    
    
    
    
    
    
    
    
        }
    
//class FiscalYearFormat implements DateFormatInterface
        {
        public function
    
    
        }
