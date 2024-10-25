<?php

/**
*   ConfigReaderCsv
*
*   version 241007
*/

declare(strict_types=1);

namespace Concerto\conf;

use SplFileObject;
use Concerto\conf\AbstractConfigReader;

class ConfigReaderCsv extends AbstractConfigReader
{
    /**
    *   @var string
    */
    private string $separator = ",";

    /**
    *   @var string
    */
    private string $enclosure = "\"";

    /**
    *   @var string
    */
    private string $escape = "\\";

    /**
    *   @var bool
    */
    private bool $header = true;

    /**
    *   {inherit}
    */
    public function read(): array
    {
        $csv = new SplFileObject($this->file, 'r');

        $csv->setCsvControl(
            $this->separator,
            $this->enclosure,
            $this->escape,
        );

        $csv->setFlags(
            SplFileObject::READ_CSV |
                SplFileObject::READ_AHEAD |
                SplFileObject::SKIP_EMPTY
        );

        $first_row = true;
        $configs = [];

        foreach ($csv as $row) {
            if ($first_row) {
                $titles = $this->header ?
                    $row :
                    range(0, count($row) - 1);

                $first_row = false;

                if ($this->header) {
                    continue;
                }
            }

            $configs[] = array_combine(
                $titles,
                $row
            );
        }

        return $configs;
    }

    /**
    *   setSeparator
    *
    *   @param string $separator
    *   @return static
    */
    public function setSeparator(
        string $separator,
    ): static {
        $this->separator = $separator;
        return $this;
    }

    /**
    *   setEnclosure
    *
    *   @param string $enclosure
    *   @return static
    */
    public function setEnclosure(
        string $enclosure,
    ): static {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
    *   setEscape
    *
    *   @param string $escape
    *   @return static
    */
    public function setEscape(
        string $escape,
    ): static {
        $this->escape = $escape;
        return $this;
    }

    /**
    *   noHeader
    *
    *   @return static
    */
    public function noHeader(): static
    {
        $this->header = false;
        return $this;
    }
}
