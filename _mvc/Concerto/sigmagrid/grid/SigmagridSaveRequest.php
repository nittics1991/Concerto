<?php

/**
*   Sigmagrid Save Request
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use InvalidArgumentException;
use RuntimeException;
use SplFileObject;
use Concerto\sigmagrid\grid\{
    SigmagridColumnInfos,
    SigmagridFilterInfos,
    SigmagridPageInfo,
    SigmagridSortInfos
};
use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridSaveRequest extends DataContainerValidatable
{
    /**
    *   jsonId
    *
    *   @var string
    */
    protected $jsonId;

    /**
    *   dataClass
    *
    *   @var object
    */
    protected $dataClass;

    /**
    *   rawData
    *
    *   @var mixed[]
    */
    protected $rawData;

    /**
    *   Columns
    *
    *   @var string[]
    */
    protected static $schema = ['recordType', 'parameters', 'action',
        'fieldsName', 'insertedRecords', 'updatedRecords', 'deletedRecords'
    ];

    /**
    *   __construct
    *
    *   @param DataContainerValidatable $dataClass
    *   @param DataContainerValidatable $parameters
    *   @param string $jsonId
    */
    public function __construct(
        DataContainerValidatable $dataClass,
        DataContainerValidatable $parameters = null,
        $jsonId = '_gt_json'
    ) {
        $this->jsonId = $jsonId;
        $this->dataClass = $dataClass;
        $this->parameters = (is_object($parameters)) ? $parameters : null;

        if (!$this->isAjax()) {
            return;
        }

        if (!isset($_POST[$this->jsonId])) {
            return;
        }
        $this->rawData = json_decode($_POST[$this->jsonId], true);
        $this->init();
    }

    /**
    *   isAjax
    *
    *   @return bool
    */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ===
                'xmlhttprequest';
    }

    /**
    *   init
    *
    */
    protected function init()
    {
        $this->action = $this->rawData['action'];
        $this->recordType = $this->rawData['recordType'];
        $this->fieldsName = $this->rawData['fieldsName'];

        $this->setInsertedRecords();
        $this->setUpdatedRecords();
        $this->setDeletedRecords();
        $this->setParameters();
    }

    /**
    *   setParameters
    *
    */
    protected function setParameters()
    {
        if (isset($this->parameters)) {
            $this->parameters = $this->parameters->fromArray(
                $this->rawData['parameters']
            );
        }
    }

    /**
    *   setInsertedRecords
    *
    */
    protected function setInsertedRecords()
    {
        $this->insertedRecords = new SigmagridRecordCollection(
            $this->fieldsName,
            $this->rawData['insertedRecords'],
            clone $this->dataClass,
            $this->recordType
        );
    }

    /**
    *   setUpdatedRecords
    *
    */
    protected function setUpdatedRecords()
    {
        $this->updatedRecords = new SigmagridRecordCollection(
            $this->fieldsName,
            $this->rawData['updatedRecords'],
            clone $this->dataClass,
            $this->recordType
        );
    }

    /**
    *   setDeletedRecords
    *
    */
    protected function setDeletedRecords()
    {
        $this->deletedRecords = new SigmagridRecordCollection(
            $this->fieldsName,
            $this->rawData['deletedRecords'],
            clone $this->dataClass,
            $this->recordType
        );
    }

    public function isValidRecordType($val)
    {
        return ($val == 'array') || ($val == 'object');
    }

    public function isValidAction($val)
    {
        return ($val == 'save');
    }

    public function isValidFieldsName($val)
    {
        return is_array($val);
    }

    public function isValidInsertedRecords($val)
    {
        return (isset($this->insertedRecords)) ?
            $this->insertedRecords->isValid() : false;
    }

    public function isValidUpdatedRecords($val)
    {
        return (isset($this->updatedRecords)) ?
            $this->updatedRecords->isValid() : false;
    }

    public function isValidDeletedRecords($val)
    {
        return (isset($this->deletedRecords)) ?
            $this->deletedRecords->isValid() : false;
    }

    public function isValidParameters($val)
    {
        return (isset($this->parameters)) ?
            $this->parameters->isValid() : true;
    }
}
