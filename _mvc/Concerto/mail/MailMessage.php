<?php

/**
*   Mail Message
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\mail;

use Concerto\standard\DataContainerValidatable;

class MailMessage extends DataContainerValidatable
{
    /**
    *   カラム情報
    *
    *   @var string[]
    *   @example ['from => ['aaa@bbb' => 'name1', ...] //from, to, cc, bcc
    *                   'subject' => 'title', 'message' => 'message body'
    *                   'attach' => [['file' => '/file/path.txt', 'mime' =>
    *                           'text/plain'], [...]...]
    *                   'type' => '' //text or html ]
    */
    protected static $schema = [
        'from', 'to', 'cc', 'bcc', 'subject', 'message', 'attach', 'type'
    ];

    /**
    *   @var string text|html
    */
    // public string $type;

    /**
    *   __construct
    *
    *   @param mixed[] $params データ
    */
    public function __construct(array $params = [])
    {
        $this->type = 'text';
        $this->fromArray($params);
    }

    /**
    *   validMailAddress
    *
    *   @param string[] $val
    *   @return bool|array
    */
    protected function isValidMailAddress($val)
    {
        if (!is_array($val)) {
            return false;
        }

        $result = [];
        foreach ($val as $address => $name) {
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

    protected function isValidFrom($val)
    {
        if (!is_array($val) || empty($val)) {
            return false;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidTo($val)
    {
        if (!is_array($val) || empty($val)) {
            return false;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidCc($val)
    {
        if (is_null($val)) {
            return true;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidBcc($val)
    {
        if (is_null($val)) {
            return true;
        }
        return $this->isValidMailAddress($val);
    }

    protected function isValidSubject($val)
    {
        if (is_null($val)) {
            return true;
        }
        return is_string($val);
    }

    protected function isValidMessage($val)
    {
        if (is_null($val)) {
            return true;
        }
        return is_string($val);
    }

    protected function isValidAttach($val)
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
        return (empty($result)) ?    true : $result;
    }

    protected function isValidType($val)
    {
        return $val === 'text' ||
             $val === 'html';
    }
}
