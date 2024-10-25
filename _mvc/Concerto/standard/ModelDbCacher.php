<?php

/**
*   ModelDbCacher
*
*   @version 230116
*/

declare(strict_types=1);

namespace Concerto\standard;

use PDO;
use RuntimeException;
use Concerto\standard\{
    DataMapperInterface,
    DataModelInterface,
};

class ModelDbCacher
{
    /**
    *   @var PDO
    */
    protected PDO $pdo;

    /**
    *   @var ?DataMapperInterface
    */
    protected ?DataMapperInterface $modelDb;

    /**
    *   @var DataModelInterface[]
    */
    protected array $inserts = [];

    /**
    *   @var DataModelInterface[][]
    *       [[DataModelInterface,DataModelInterface],...]
    */
    protected array $updates = [];

    /**
    *   @var DataModelInterface[]
    */
    protected array $deletes = [];

    /**
    *   __construct
    *
    *   @param PDO $pdo
    *   @param ?DataMapperInterface $modelDb
    */
    public function __construct(
        PDO $pdo,
        DataMapperInterface $modelDb = null
    ) {
        $this->pdo = $pdo;
        $this->modelDb = $modelDb;
    }

    /**
    *   createCacher
    *
    *   @param DataMapperInterface $modelDb
    *   @return static
    */
    public function createCacher(
        DataMapperInterface $modelDb
    ): static {
        return new $this($this->pdo, $modelDb);
    }

    /**
    *   addInsertData
    *
    *   @param DataModelInterface $data
    *   @return static
    */
    public function addInsertData(
        DataModelInterface $data
    ): static {
        $this->inserts[] = $data;
        return $this;
    }

    /**
    *   addUpdateData
    *
    *   @param DataModelInterface $data
    *   @param DataModelInterface $where
    *   @return static
    */
    public function addUpdateData(
        DataModelInterface $data,
        DataModelInterface $where
    ): static {
        $this->updates[] = [$data, $where];
        return $this;
    }

    /**
    *   addDeleteData
    *
    *   @param DataModelInterface $where
    *   @return static
    */
    public function addDeleteData(
        DataModelInterface $where
    ): static {
        $this->deletes[] = $where;
        return $this;
    }

    /**
    *   save
    *
    *   @return static
    */
    public function save(): static
    {
        if (!isset($this->modelDb)) {
            throw new RuntimeException(
                "DataMapperInterface is unregistered"
            );
        }

        if (!empty($this->deletes)) {
            $this->modelDb->delete($this->deletes);
        }

        if (!empty($this->updates)) {
            $this->modelDb->update($this->updates);
        }

        if (!empty($this->inserts)) {
            $this->modelDb->insert($this->inserts);
        }
        return $this;
    }

    /**
    *   getMapper
    *
    *   @return ?DataMapperInterface
    */
    public function getMapper(): ?DataMapperInterface
    {
        return $this->modelDb;
    }

    /**
    *   createModel
    *
    *   @return ?DataModelInterface
    */
    public function createModel(): ?DataModelInterface
    {
        return $this->modelDb?->createModel();
    }

    /**
    *   getInsertData
    *
    *   @return DataModelInterface[]
    */
    public function getInsertData(): array
    {
        return $this->inserts;
    }

    /**
    *   getUpdateData
    *
    *   @return DataModelInterface[][]
    */
    public function getUpdateData(): array
    {
        return $this->updates;
    }

    /**
    *   getDeleteData
    *
    *   @return DataModelInterface[]
    */
    public function getDeleteData(): array
    {
        return $this->deletes;
    }
}
