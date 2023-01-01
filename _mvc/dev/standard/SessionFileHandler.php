<?php

/**
*   Session
*
*   @version 221230
*/

declare(strict_types=1);

namespace Concerto\standard;

use SessionHandlerInterface;
use SplFileInfo;

class SessionFileHandler implements SessionHandlerInterface
{
    /**
    *   @var SplFileObject
    */
    protected SplFileObject $storage;

    /**
    *   __construct
    *
    *   @param ?string $file_name
    */
    public function __construct(
        ?string $file_name = null,
    ) {
        //return $file_name === null?
            //$this->createSessionFile():
            //$this->setSessionFile($file_name);
    }

    /**
    *   createSessionFile
    *
    *   @return static
    */
    protected function createSessionFile():static
    {
        $save_path = session_save_path();

        if ($save_path === false) {
            throw new RuntimeException(
                "get session save path error",
            );
        }
        
        $id = session_create_id();

        if ($id === false) {
            throw new RuntimeException(
                "create session id error",
            );
        }

        $this->storage = new SplFileObject(
            $save_path . DIRECTORY_SEPARATOR . $id,
            'w+',
        );
    }

    /**
    *   @inheritDoc
    */
    public function open(
        string $path,
        string $name
    ): bool
    {
        var_dump("OPEN path={$path} name={$name}");
        return true;
    }

    /**
    *   @inheritDoc
    */
    public function close(): bool
    {
        return true;
    }

    /**
    *   @inheritDoc
    */
    public function read(
        string $id
    ): string|false
    {
        var_dump("READ id={$id}");
        return "true";
    }

    /**
    *   @inheritDoc
    */
    public function write(
        string $id,
        string $data
    ): bool
    {
        var_dump("READ id={$id}");
        return true;
    }
    
    /**
    *   @inheritDoc
    */
    public function destroy(
        string $id
    ): bool
    {
        var_dump("DESTROY id={$id}");
        return true;
    }
    
    /**
    *   @inheritDoc
    */
    public function gc(
        int $max_lifetime
    ): int|false
    {
        var_dump("GC max_lifetime={$max_lifetime}");
        return $max_lifetime;
    }
}
