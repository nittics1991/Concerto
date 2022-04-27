<?php

/**
*   Sigmagrid Load Request
*
*   @version 210903
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\Validate;
use Concerto\sigmagrid\grid\{
    SigmagridColumnInfos,
    SigmagridFilterInfos,
    SigmagridPageInfo,
    SigmagridSortInfos
};
use Concerto\standard\DataContainerValidatable;

class SigmagridLoadRequest extends DataContainerValidatable
{
    /**
    *   jsonId
    *
    *   @var string
    */
    protected $jsonId;

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
    protected static $schema = [
        'recordType', 'parameters', 'action',
        'pageInfo', 'columnInfo', 'sortInfo', 'filterInfo',
        'remotePaging', 'exportType', 'exportFileName'
    ];

    /**
    *   __construct
    *
    *   @param ?DataContainerValidatable $parameters
    *   @param ?string $jsonId
    */
    public function __construct(
        ?DataContainerValidatable $parameters = null,
        ?string $jsonId = '_gt_json'
    ) {
        $this->parameters = (is_object($parameters)) ?
            $parameters : null;
        $this->jsonId = (string)$jsonId;

        $this->rawData = json_decode(
            $_POST[$this->jsonId] ?? "",
            true
        );
        $this->init();
    }

    /**
    *   init
    *
    */
    protected function init()
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
    */
    protected function setParameters()
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
    */
    protected function setPageInfo()
    {
        $this->pageInfo = new SigmagridPageInfo(
            $this->rawData['pageInfo'] ?? []
        );
    }

    /**
    *   setColumnInfo
    *
    */
    protected function setColumnInfo()
    {
        $this->columnInfo = new SigmagridColumnInfos(
            $this->rawData['columnInfo'] ?? []
        );
    }

    /**
    *   setSortInfo
    *
    */
    protected function setSortInfo()
    {
        $this->sortInfo = new SigmagridSortInfos(
            $this->rawData['sortInfo'] ?? []
        );
    }

    /**
    *   setFilterInfo
    *
    */
    protected function setFilterInfo()
    {
        $this->filterInfo = new SigmagridFilterInfos(
            $this->rawData['filterInfo'] ?? []
        );
    }

    /**
    *   {inherit}
    *
    */
    protected function validCom($key, $val): bool
    {
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

    public function isValidRecordType($val)
    {
        return ($val == 'array') || ($val == 'object');
    }

    public function isValidAction($val)
    {
        return ($val == 'load');
    }

    public function isValidRemotePaging($val)
    {
        return is_bool($val);
    }

    public function isValidExportType($val)
    {
        switch ($val) {
            case null:
            case 'csv':
                return true;
        }
        return false;
    }

    public function isValidExportFileName($val)
    {
        if (!isset($val)) {
            return true;
        }
        return
            Validate::isTextEscape($val, 0, 100, null, '\r\n\t') &&
            !Validate::hasTextHankaku($val) &&
            !Validate::hasTextHtml($val) &&
            !Validate::hasTextDatabase($val);
    }

    public function isValidPageInfo($val)
    {
        return isset($this->pageInfo) ?
            $this->pageInfo->isValid() : false;
    }

    public function isValidColumnInfo($val)
    {
        return isset($this->columnInfo) ?
            $this->columnInfo->isValid() : false;
    }

    public function isValidSortInfo($val)
    {
        return isset($this->sortInfo) ?
            $this->sortInfo->isValid() : false;
    }

    public function isValidFilterInfo($val)
    {
        return isset($this->filterInfo) ?
            $this->filterInfo->isValid() : false;
    }

    public function isValidParameters($val)
    {
        return isset($this->parameters) ?
            $this->parameters->isValid() : true;
    }
}
