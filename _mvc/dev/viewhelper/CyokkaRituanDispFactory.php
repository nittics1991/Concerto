<?php

/**
*   factory
*
*   @version 210615
*/

declare(strict_types=1);

namespace cyokka_rituan2\model;

use PDO;
use dev\database\{
    CyokkaKeikaku,
    CyokkaMonKeikaku
};
use dev\standard\ViewJson;
use cyokka_rituan2\model\{
    CyokkaRituanDispControllerModel,
    CyokkaRituanDispModel,
    CyokkaRituanDispMntModel,
    CyokkaRituanDispMntRepository,
    CyokkaRituanDispMntMonRepository,
    PostCyokkaRituanDisp,
    QueryCyokkaRituanDisp
};
use cyokka_rituan2\view\CyokkaRituanDispView;

class CyokkaRituanDispFactory
{
    /**
    *   pdo
    *
    *   @var PDO
    */
    private $pdo;

    /**
    *   __construct
    *
    *   @param PDO $pdo
    */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }



    public function getPdo()
    {
        return $this->pdo;
    }



    public function getCyokkaKeikaku()
    {
        return new CyokkaKeikaku($this->pdo);
    }

    public function getCyokkaMonKeikaku()
    {
        return new CyokkaMonKeikaku($this->pdo);
    }



    public function getCyokkaRituanDispMntRepository()
    {
        return new CyokkaRituanDispMntRepository(
            $this->getCyokkaKeikaku()
        );
    }

    public function getCyokkaRituanDispMntMonRepository()
    {
        return new CyokkaRituanDispMntMonRepository(
            $this->getCyokkaMonKeikaku()
        );
    }

    public function getMntModel()
    {
        return new CyokkaRituanDispMntModel(
            $this->getPdo(),
            $this->getCyokkaRituanDispMntRepository(),
            $this->getCyokkaRituanDispMntMonRepository()
        );
    }



    public function getPost()
    {
        return new PostCyokkaRituanDisp();
    }

    public function getQuery()
    {
        return new QueryCyokkaRituanDisp();
    }

    public function getModel()
    {
        return new CyokkaRituanDispModel(
            $this->getPdo(),
            $this->getCyokkaKeikaku(),
            $this->getCyokkaMonKeikaku(),
        );
    }

    public function getControllerModel()
    {
        return new CyokkaRituanDispControllerModel($this);
    }

    public function getViewDispModel(array $contents)
    {
        return new CyokkaRituanDispView($contents);
    }

    public function getViewJsonModel()
    {
        return new ViewJson();
    }
}
