<?php

/**
*   Sigmagrid Load Request
*
*   @version 230116
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use InvalidArgumentException;
use Concerto\Validate;
use Concerto\sigmagrid\grid\{
    SigmagridColumnInfos,
    SigmagridFilterInfos,
    SigmagridPageInfo,
    SigmagridSortInfos
};
use Concerto\standard\DataContainerValidatable;

/**
*   @template TValue
*   @extends DataContainerValidatable<TValue>
*/
class SigmagridLoadRequest extends DataContainerValidatable
{
    /**
    *   @var ?string
    */
    protected ?string $jsonId;

    /**
    *   @var ?mixed[]
    */
    protected ?array $rawData;

    /**
    *   @inheritDoc
    */
    protected static array $schema = [
        'recordType', 'parameters', 'action',
        'pageInfo', 'columnInfo', 'sortInfo', 'filterInfo',
        'remotePaging', 'exportType', 'exportFileName'
    ];

    /**
    *   __construct
    *
    *   @param ?DataContainerValidatable<TValue> $parameters
    *   @param ?string $jsonId
    */
    public function __construct(
        ?DataContainerValidatable $parameters = null,
        ?string $jsonId = '_gt_json'
    ) {
        $this->parameters = is_object($parameters) ?
            $parameters : null;
        $this->jsonId = (string)$jsonId;

        $rawData = json_decode(
            $_POST[$this->jsonId] ?? "",
            true
        );

        if (
            !is_null($rawData) &&
            !is_array($rawData)
        ) {
            throw new InvalidArgumentException(
                "POST data bust be type array",
            );
        }

        $this->rawData = $rawData;

        $this->init();
    }

    /**
    *   init
    *
    *   @return void
    */
    protected function init(): void
    {
        $this->action = $this->rawData['action'] ?? null;
        $this->recordType = $this->rawData['recordType'] ?? null;

        $this->remotePaging = $this->rawData['remotePaging'] ?? null;
        $this->exportType = $this->rawData['exportType'] ?? null;
        $this->exportFileName = $this->rawData['exportFileName'] ?? null;

        $this->setPageInfo();
        $this->setColumnInfo();
        $this->setSortInfo();
        $this->setFilterInfo();
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
                $this->rawData['parameters'] ?? []
            );
        }
    }

    /**
    *   setPageInfos
    *
    *   @return void
    */
    protected function setPageInfo(): void
    {
        $this->pageInfo = new SigmagridPageInfo(
            (array)($this->rawData['pageInfo'] ?? [])
        );
    }

    /**
    *   setColumnInfo
    *
    *   @return void
    */
    protected function setColumnInfo(): void
    {
        $this->columnInfo = new SigmagridColumnInfos(
            (array)($this->rawData['columnInfo'] ?? [])
        );
    }

    /**
    *   setSortInfo
    *
    *   @return void
    */
    protected function setSortInfo(): void
    {
        $this->sortInfo = new SigmagridSortInfos(
            (array)($this->rawData['sortInfo'] ?? [])
        );
    }

    /**
    *   setFilterInfo
    *
    *   @return void
    */
    protected function setFilterInfo(): void
    {
        $this->filterInfo = new SigmagridFilterInfos(
            (array)($this->rawData['filterInfo'] ?? [])
        );
    }

    /**
    *   @inheritDoc
    */
    protected function validCom(
        string $key,
        mixed $val
    ): bool {
        return $this->isAjax() && $this->hasGridId();
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
    *   hasGridId
    *
    *   @return bool
    */
    protected function hasGridId(): bool
    {
        return isset($_POST[$this->jsonId]);
    }

    public function isValidRecordType(
        mixed $val
    ): bool {
        return $val === 'array' || $val === 'object';
    }

    public function isValidAction(
        mixed $val
    ): bool {
        return $val === 'load';
    }

    public function isValidRemotePaging(
        mixed $val
    ): bool {
        return is_bool($val);
    }

    public function isValidExportType(
        mixed $val
    ): bool {
        switch ($val) {
            case null:
            case 'csv':
                return true;
        }
        return false;
    }

    public function isValidExportFileName(
        mixed $val
    ): bool {
        if (!isset($val)) {
            return true;
        }
        return
            Validate::isTextEscape($val, 0, 100, null, '\r\n\t') &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidPageInfo(
        mixed $val
    ): bool {
        return isset($this->pageInfo) ?
            $this->pageInfo->isValid() : false;
    }

    public function isValidColumnInfo(
        mixed $val
    ): bool {
        return isset($this->columnInfo) ?
            $this->columnInfo->isValid() : false;
    }

    public function isValidSortInfo(
        mixed $val
    ): bool {
        return isset($this->sortInfo) ?
            $this->sortInfo->isValid() : false;
    }

    public function isValidFilterInfo(
        mixed $val
    ): bool {
        return isset($this->filterInfo) ?
            $this->filterInfo->isValid() : false;
    }

    public function isValidParameters(
        mixed $val
    ): bool {
        return isset($this->parameters) ?
            $this->parameters->isValid() : true;
    }
}
