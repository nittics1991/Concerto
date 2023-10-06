<?php

/**
*   Win32Process
*
*   @version 230116
*/

declare(strict_types=1);

namespace Concerto\win;

use COM;
use RuntimeException;
use VARIANT;

class Win32Process
{
    /**
    *   @var ?object SWbemLocator
    */
    protected ?object $locator;

    /**
    *   @var ?object SWbemServices
    */
    protected ?object $service;

    /**
    *   @var ?object SWbemObject
    */
    protected ?object $object;

    /**
    *   __construct
    *
    *   @param ?object $object SWbemObject
    */
    public function __construct(
        ?object $object = null
    ) {
        $this->object = $object;
    }

    /**
    *   __get
    *
    *   @param string $name
    *   @return mixed
    */
    public function __get(
        string $name
    ): mixed {
        if (!isset($this->object)) {
            throw new RuntimeException(
                "WIN32_process object not setting"
            );
        }

        return $this->object->$name;
    }

    /**
    *   __call
    *
    *   @param string $name
    *   @param mixed[] $arguments
    *   @return mixed
    */
    public function __call(
        string $name,
        array $arguments
    ): mixed {
        $methods = ['getowner'];

        if (
            !in_array(
                mb_convert_case($name, MB_CASE_LOWER),
                $methods
            )
        ) {
            throw new RuntimeException(
                "method is not defined:{$name}"
            );
        }

        if (!isset($this->object)) {
            throw new RuntimeException(
                "WIN32_process object not setting"
            );
        }

        return call_user_func_array(
            [$this->object, $name],
            $arguments
        );
    }

    /**
    *   connect
    *
    *   @return void
    */
    protected function connect(): void
    {
        if (!isset($this->service)) {
            $this->locator = new COM(
                'WbemScripting.SWbemLocator'
            );

            $this->service = $this->locator->ConnectServer();
        }
    }

    /**
    *   findAll
    *
    *   @return mixed[] [this, ...]
    */
    public function findAll(): array
    {
        $this->connect();

        $objectSet = $this->service
            ->ExecQuery("select * from Win32_Process");

        $items = [];

        foreach ($objectSet as $obj) {
            $items[] = new $this($obj);
        }

        return $items;
    }

    /**
    *   findById
    *
    *   @param int $id
    *   @return ?static
    */
    public function findById(
        int $id
    ): ?static {
        $this->connect();

        $objectSet = $this->service->ExecQuery(
            "select * from Win32_Process where ProcessId = {$id}"
        );

        $item = null;

        foreach ($objectSet as $obj) {
            $item = new $this($obj);
        }

        return $item;
    }

    /**
    *   findByName
    *
    *   @param string $name
    *   @return mixed[] [this, ...]
    */
    public function findByName(
        string $name
    ): array {
        $this->connect();

        $objectSet = $this->service->ExecQuery(
            "select * from Win32_Process where name = '{$name}' "
        );

        $items = [];

        foreach ($objectSet as $obj) {
            $items[] = new $this($obj);
        }

        return $items;
    }

    /**
    *   terminate
    *
    *   @return static
    */
    public function terminate(): static
    {
        if (!isset($this->object)) {
            throw new RuntimeException(
                "WIN32_process object not setting"
            );
        }
        $this->connect();

        //php実行ユーザ名取得
        $cliPid = getmypid();

        if ($cliPid === false) {
            throw new RuntimeException(
                "failure getmypid()"
            );
        }

        $process = $this->findById($cliPid);

        $user = new VARIANT();

        $domain = new VARIANT();

        $process->getowner($user, $domain);

        $userName = $this->variantToString($user);

        //processオーナー
        $user = new VARIANT();

        $domain = new VARIANT();

        $this->object->getowner($user, $domain);

        $processOwner = $this->variantToString($user);

        if ($processOwner !== $userName) {
            throw new RuntimeException("not have permission");
        }

        $this->object->terminate;

        return $this;
    }

    /**
    *   variantToString
    *
    *   @param VARIANT $data
    *   @return string
    */
    protected function variantToString(
        VARIANT $data
    ): string {
        ob_start();

        @print $data;

        $result = (string)ob_get_contents();

        ob_end_clean();

        return $result;
    }
}
