<?php

/**
*   ModelDbCacher
*
*   @version 210615
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
    *   pdo
    *
    *   @var PDO
    */
    protected $pdo;

    /**
    *   modelDb
    *
    *   @var ?DataMapperInterface
    */
    protected $modelDb;

    /**
    *   inserts
    *
    *   @var DataModelInterface[]
    */
    protected $inserts = [];

    /**
    *   updates
    *
    *   @var array[] [[DataModelInterface,DataModelInterface],...]
    */
    protected $updates = [];

    /**
    *   deletes
    *
    *   @var DataModelInterface[]
    */
    protected $deletes = [];

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
    *   @return ModelDbCacher
    */
    public function createCacher(DataMapperInterface $modelDb)
    {
        return new $this($this->pdo, $modelDb);
    }

    /**
    *   addInsertData
    *
    *   @param DataModelInterface $data
    *   @return $this
    */
    public function addInsertData(DataModelInterface $data)
    {
        $this->inserts[] = $data;
        return $this;
    }

    /**
    *   addUpdateData
    *
    *   @param DataModelInterface $data
    *   @param DataModelInterface $where
    *   @return $this
    */
    public function addUpdateData(
        DataModelInterface $data,
        DataModelInterface $where
    ) {
        $this->updates[] = [$data, $where];
        return $this;
    }

    /**
    *   addDeleteData
    *
    *   @param DataModelInterface $where
    *   @return $this
    */
    public function addDeleteData(DataModelInterface $where)
    {
        $this->deletes[] = $where;
        return $this;
    }

    /**
    *   save
    *
    *   @return $this
    *   @throws RuntimeException
    */
    public function save()
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
    *   @return array
    */
    public function getInsertData(): array
    {
        return $this->inserts;
    }

    /**
    *   getUpdateData
    *
    *   @return array
    */
    public function getUpdateData(): array
    {
        return $this->updates;
    }

    /**
    *   getDeleteData
    *
    *   @return array
    */
    public function getDeleteData(): array
    {
        return $this->deletes;
    }
}
