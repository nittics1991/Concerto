<?php

/**
*   Mail Message
*
*   @version 210902
*/

declare(strict_types=1);

namespace dev\mail;

use dev\standard\DataContainerValidatable;

class MailMessage extends DataContainerValidatable
{
    /**
    *   @var string
    */
    public const TYPE_TEXT = 'text';

    /**
    *   @var string
    */
    public const TYPE_HTML = 'html';

    /**
    *   {inherit}
    */
    protected static $schema = [
        'from', 'to', 'cc', 'bcc', 'subject', 'message', 'attach', 'type'
    ];

    /**
    *   @var string $type text|html
    */
    public string $type;

    /**
    *   __construct
    *
    *   @param mixed[] $params データ
    */
    public function __construct(array $params = [])
    {
        $this->type = self::TYPE_TEXT;
        $this->fromArray($params);
    }

    /**
    *   mime
    *
    *   @return string
    */
    public function mimeType(): string
    {
        return $this->type === self::TYPE_TEXT ?
            'text/plain' : 'text/html';
    }

    /**
    *   validMailAddress
    *
    *   @param string[] $val
    *   @return bool|array
    */
    protected function isValidMailAddress(mixed $val): bool | array
    {
        $result = [];
        foreach ((array)$val as $address => $name) {
            if (
                !is_string($name) ||
                !is_string($address) ||
                !mb_ereg_match(
                    "^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$",
                    $address
                )
            ) {
                $result[] = $address;
            }
        }
        return empty($result) ? true : $result;
    }

    protected function isValidFrom(mixed $val): bool | array
    {
        if (!is_array($val) || empty($val)) {
            return false;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidTo(mixed $val): bool | array
    {
        if (!is_array($val) || empty($val)) {
            return false;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidCc(mixed $val): bool | array
    {
        if (is_null($val)) {
            return true;
        }
        if (!is_array($val) ) {
            return false;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidBcc(mixed $val): bool | array
    {
        if (is_null($val)) {
            return true;
        }
        if (!is_array($val) ) {
            return false;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidSubject(mixed $val): bool
    {
        if (is_null($val)) {
            return true;
        }
        return is_string($val);
    }

    protected function isValidMessage(mixed $val): bool
    {
        if (is_null($val)) {
            return true;
        }
        return is_string($val);
    }

    protected function isValidAttach(mixed $val): bool | array
    {
        if (is_null($val)) {
            return true;
        }

        if (!is_array($val)) {
            return false;
        }

        $result = [];
        foreach ($val as $key => $dataset) {
            if (!is_array($dataset)) {
                $result = false;
            } elseif (!array_key_exists('file', $dataset)) {
                $result[] = $key;
            }
        }
        return empty($result) ? true : $result;
    }

    protected function isValidType(mixed $val): bool
    {
        return $this->type === self::TYPE_TEXT ||
             $this->type === self::TYPE_HTML;
    }
}
