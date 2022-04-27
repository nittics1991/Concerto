<?php

/**
*   StandardEncrypter
*
*   @version 210903
*   @see https://github.com/illuminate/encryption
*/

declare(strict_types=1);

namespace Concerto\encrypt;

use RuntimeException;
use Concerto\encrypt\EncrypterInterface;

class StandardEncrypter implements EncrypterInterface
{
    /**
    *   key
    *
    *   @var string
    */
    protected $key;

    /**
    *   cipher
    *
    *   @var string
    */
    protected $cipher;

    /**
    *   __construct
    *
    *   @param string $key
    *   @param string $cipher
    *   @throws RuntimeException
    */
    public function __construct(
        string $key,
        string $cipher = 'AES-256-CBC'
    ) {

        if (!$this->isValidCipher($key, $cipher)) {
            throw new RuntimeException(
                "must be AES-256-CBC(32bit) or AES-128-CBC(16bit)"
            );
        }
        $this->key = $key;
        $this->cipher = $cipher;
    }

    /**
    *   isValidCipher
    *
    *   @param string $key
    *   @param string $cipher
    *   @return bool
    */
    public function isValidCipher(
        string $key,
        string $cipher
    ): bool {
        $length = mb_strlen($key, '8bit');

        return ($cipher === 'AES-128-CBC' && $length === 16) ||
               ($cipher === 'AES-256-CBC' && $length === 32);
    }

    /**
    *   {inherit}
    *
    */
    public function encrypt(string $value): string
    {
        $iv = random_bytes(
            (int)openssl_cipher_iv_length($this->cipher)
        );

        $value = openssl_encrypt(
            $value,
            $this->cipher,
            $this->key,
            0,
            $iv
        );

        if ($value === false) {
            throw new RuntimeException(
                "Could not encrypt the data"
            );
        }

        $mac = $this->hash($iv = base64_encode($iv), $value);

        $json = (string)json_encode(
            compact('iv', 'value', 'mac'),
            JSON_UNESCAPED_SLASHES
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                "Could not encrypt the data"
            );
        }
        return base64_encode($json);
    }

    /**
    *   {inherit}
    *
    */
    public function decrypt(string $payload): string
    {
        $payload = $this->getJsonPayload($payload);

        $iv = base64_decode($payload['iv']);

        $decrypted = openssl_decrypt(
            $payload['value'],
            $this->cipher,
            $this->key,
            0,
            $iv
        );

        if ($decrypted === false) {
            throw new RuntimeException(
                "Could not decrypt the data"
            );
        }
        return $decrypted;
    }

    /**
    *   hash
    *
    *   @param string  $iv
    *   @param string  $value
    *   @return string
    */
    protected function hash(
        string $iv,
        string $value
    ): string {
        return hash_hmac('sha256', $iv . $value, $this->key);
    }

    /**
    *   getJsonPayload
    *
    *   @param string $payload
    *   @return array
    *   @throws RuntimeException
    */
    protected function getJsonPayload(string $payload): array
    {
        $payload = json_decode(base64_decode($payload), true);

        if (! $this->isValidPayload($payload)) {
            throw new RuntimeException(
                "The payload is invalid"
            );
        }

        if (! $this->isValidMac($payload)) {
            throw new RuntimeException(
                "The MAC is invalid"
            );
        }
        return $payload;
    }

    /**
    *   isValidPayload
    *
    *   @param array $payload
    *   @return bool
    */
    protected function isValidPayload(array $payload): bool
    {
        return is_array($payload) &&
            isset($payload['iv'], $payload['value'], $payload['mac']) &&
            mb_strlen((string)base64_decode($payload['iv'], true), '8bit') ===
                openssl_cipher_iv_length($this->cipher);
    }

    /**
    *   isValidMac
    *
    *   @param array $payload
    *   @return bool
    */
    protected function isValidMac(array $payload): bool
    {
        return hash_equals(
            $this->hash($payload['iv'], $payload['value']),
            $payload['mac']
        );
    }

    /**
    *   getKey
    *
    *   @return string
    */
    public function getKey(): string
    {
        return $this->key;
    }
}
