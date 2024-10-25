<?php

/**
*   StandardStringObject
*
*   @version 220514
*/

declare(strict_types=1);

namespace Concerto\wrapper\string;

use InvalidArgumentException;
use RuntimeException;
use Concerto\wrapper\string\{
    CaseFunctionTrait,
    ConvertFunctionTrait,
    EregFunctionTrait,
    PositioningFunctionTrait,
    TakeFunctionTrait,
};
use Concerto\wrapper\string\extends\{
    AliasFunctionTrait,
    ExtentionEregFunctionTrait,
    ExtentionPositioningFunctionTrait,
};

class StandardStringObject
{
    use CaseFunctionTrait;
    use ConvertFunctionTrait;
    use EregFunctionTrait;
    use PositioningFunctionTrait;
    use TakeFunctionTrait;

    /**
    *   @var string
    */
    protected string $string;

    /**
    *   @var string
    */
    protected string $encoding;

    /**
    *   __construct
    *
    *   @param string $string
    *   @param string $encoding
    */
    public function __construct(
        string $string,
        string $encoding = 'UTF-8',
        ?array $detect_orders = null,
    ) {
        $this->string = $string;
        
        if (!$this->validEncodeName($encoding)) {
            throw new InvalidArgumentException(
                "not defined encoding:{$encoding}",
            );
        }

        $this->encoding = $encoding;
    }

    /**
    *   validEncodeName
    *
    *   @param string $string
    *   @return bool
    */
    protected function validEncodeName(
        string $string
    ): bool {
        $encodings = mb_list_encodings();

        $aliases = array_map(
            'mb_encoding_aliases',
            $encodings
        );

        $encodings = array_reduce(
            $aliases,
            'array_merge',
            $encodings
        );
        
        return in_array($string, $encodings);
    }

    /**
    *   create
    *
    *   @param string $string
    *   @param string $encoding
    *   @return self
    */
    public static function create(
        string $string,
        string $encoding = 'UTF-8',
    ): self {
        return new self(
            $string,
            $encoding,
        );
    }

    /**
    *   toString
    *
    *   @return string
    */
    public function toString(): string
    {
        return $this->string;
    }
}
