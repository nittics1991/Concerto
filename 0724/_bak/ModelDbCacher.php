<?php

/**
*   ModelDbCacher
*
*   @version 190523
*/

namespace Concerto\standard;

use Exception;
use PDO;
use RuntimeException;
use Concerto\standard\DataModelInterface;
use Concerto\standard\DataMapperInterface;

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
    *   @var array
    */
    protected $inserts = [];
    
    /**
    *   updates
    *
    *   @var array
    */
    protected $updates = [];
    
    /**
    *   deletes
    *
    *   @var array
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
    **/
    public function createCacher(DataMapperInterface $modelDb)
    {
        return new $this($this->pdo, $modelDb);
    }
    
    /**
    *   addInsertData
    *
    *   @param DataModelInterface $data
    *   @return $this
    **/
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
    **/
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
    **/
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
    **/
    public function save()
    {
        if (!isset($this->modelDb)) {
            throw new RuntimeException(
                "DataMapperInterface is unregistered"
            );
        }
        
        try {
            $this->pdo->beginTransaction();
            
            if (!empty($this->deletes)) {
                $this->modelDb->delete($this->deletes);
            }
            
            if (!empty($this->updates)) {
                $this->modelDb->update($this->updates);
            }
            
            if (!empty($this->inserts)) {
                $this->modelDb->insert($this->inserts);
            }
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
        $this->pdo->commit();
        return $this;
    }
}
