<?php

/**
 *   ConfigReaderIni
 *
 * @version 191210
 */

declare(strict_types=1);

namespace Concerto\conf\conf;

use InvalidArgumentException;
use RuntimeException;
use Concerto\conf\conf\{
    Config,
    ConfigInterface,
    ConfigReaderInterface
};

class ConfigReaderIni implements ConfigReaderInterface
{
    /**
     *   mode
     *
     * @see parse_ini_string
     */
    public const NORMAL = INI_SCANNER_NORMAL;
    public const RAW = INI_SCANNER_RAW;
    public const TYPED = INI_SCANNER_TYPED;
    
    /**
     *   section
     *
     * @var bool
     */
    private $section;
    
    /**
     *   mode
     *
     * @var int
     */
    private $mode;
    
    /**
     *   __construct
     *
     * @param int  $mode
     * @param bool $useSection
     */
    public function __construct(
        int $mode = INI_SCANNER_TYPED,
        bool $useSection = false
    ) {
        $this->mode($mode);
        $this->section = $useSection;
    }
    
    /**
     *   {inherit}
     */
    public function build(string $path): ConfigInterface
    {
        $data = parse_ini_file(
            $path,
            $this->section,
            $this->mode
        );
        
        if (!is_array($data)) {
            throw new RuntimeException(
                "config file read error"
            );
        }
        return new Config($data);
    }
    
    /**
     *   セクションを使用
     *
     * @return $this
     */
    public function useSection()
    {
        $this->section = true;
        return $this;
    }
    
    /**
     *   mode
     *
     * @param  int $param
     * @return $this
     */
    public function mode(int $param)
    {
        switch ($param) {
            case self::NORMAL:
            case self::RAW:
            case self::TYPED:
                $this->mode = $param;
                break;
            default:
                throw new InvalidArgumentException(
                    "mode invalid:{$param}"
                );
        }
        return $this;
    }
}
