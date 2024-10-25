<?php

/**
*   Sigmagrid Save Request
*
*   @version 221212
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

/**
*   @template TValue
*   @extends DataContainerValidatable<TValue>
*/
class SigmagridSaveRequest extends DataContainerValidatable
{
    /**
    *   @var string
    */
    protected string $jsonId;

    /**
    *   @var DataContainerValidatable<TValue>
    */
    protected DataContainerValidatable $dataClass;

    /**
    *   @var mixed[]
    */
    protected array $rawData;

    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'recordType', 'parameters', 'action',
        'fieldsName', 'insertedRecords',
        'updatedRecords', 'deletedRecords'
    ];

    /**
    *   __construct
    *
    *   @param DataContainerValidatable<TValue> $dataClass
    *   @param DataContainerValidatable<TValue> $parameters
    *   @param string $jsonId
    */
    public function __construct(
        DataContainerValidatable $dataClass,
        DataContainerValidatable $parameters = null,
        string $jsonId = '_gt_json'
    ) {
        $this->jsonId = $jsonId;
        $this->dataClass = $dataClass;
        $this->parameters = is_object($parameters) ?
            $parameters : null;

        if (!$this->isAjax()) {
            return;
        }

        if (!isset($_POST[$this->jsonId])) {
            return;
        }
        $this->rawData = (array)json_decode(
            $_POST[$this->jsonId],
            true
        );
        $this->init();
    }

    /**
    *   isAjax
    *
    *   @return bool
    */
    protected function isAjax(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) ===
                'xmlhttprequest';
    }

    /**
    *   init
    *
    *   @return void
    */
    protected function init(): void
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
    *   @return void
    */
    protected function setParameters(): void
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
    *   @return void
    */
    protected function setInsertedRecords(): void
    {
        $this->insertedRecords = new SigmagridRecordCollection(
            $this->fieldsName,
            (array)$this->rawData['insertedRecords'],
            clone $this->dataClass,
            $this->recordType
        );
    }

    /**
    *   setUpdatedRecords
    *
    *   @return void
    */
    protected function setUpdatedRecords(): void
    {
        $this->updatedRecords = new SigmagridRecordCollection(
            $this->fieldsName,
            (array)$this->rawData['updatedRecords'],
            clone $this->dataClass,
            $this->recordType
        );
    }

    /**
    *   setDeletedRecords
    *
    *   @return void
    */
    protected function setDeletedRecords(): void
    {
        $this->deletedRecords = new SigmagridRecordCollection(
            $this->fieldsName,
            (array)$this->rawData['deletedRecords'],
            clone $this->dataClass,
            $this->recordType
        );
    }

    public function isValidRecordType(
        mixed $val
    ): bool {
        return $val === 'array' || $val === 'object';
    }

    public function isValidAction(
        mixed $val
    ): bool {
        return $val === 'save';
    }

    public function isValidFieldsName(
        mixed $val
    ): bool {
        return is_array($val);
    }

    public function isValidInsertedRecords(
        mixed $val
    ): bool {
        return (isset($this->insertedRecords)) ?
            $this->insertedRecords->isValid() : false;
    }

    public function isValidUpdatedRecords(
        mixed $val
    ): bool {
        return (isset($this->updatedRecords)) ?
            $this->updatedRecords->isValid() : false;
    }

    public function isValidDeletedRecords(
        mixed $val
    ): bool {
        return (isset($this->deletedRecords)) ?
            $this->deletedRecords->isValid() : false;
    }

    public function isValidParameters(
        mixed $val
    ): bool {
        return (isset($this->parameters)) ?
            $this->parameters->isValid() : true;
    }
}
