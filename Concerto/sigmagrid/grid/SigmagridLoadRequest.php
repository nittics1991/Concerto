<?php

/**
*   Sigmagrid Load Request
*
*   @version 190612
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use InvalidArgumentException;
use RuntimeException;
use SplFileObject;
use Concerto\sigmagrid\grid\SigmagridColumnInfos;
use Concerto\sigmagrid\grid\SigmagridFilterInfos;
use Concerto\sigmagrid\grid\SigmagridPageInfo;
use Concerto\sigmagrid\grid\SigmagridSortInfos;
use Concerto\standard\DataContainerValidatable;
use Concerto\Validate;

class SigmagridLoadRequest extends DataContainerValidatable
{
    /**
    *   jsonId
    *
    *   @var string
    **/
    protected $jsonId;
    
    /**
    *   rawData
    *
    *   @var array
    **/
    protected $rawData;
    
    /**
    *   Columns
    *
    *   @var array
    */
    protected static $schema = array('recordType', 'parameters', 'action',
        'pageInfo', 'columnInfo', 'sortInfo', 'filterInfo',
        'remotePaging', 'exportType', 'exportFileName'
    );
    
    /**
    *   __construct
    *
    *   @param DataContainerValidatable $parameters
    *   @param string $jsonId
    **/
    public function __construct(
        DataContainerValidatable $parameters = null,
        $jsonId = '_gt_json'
    ) {
        $this->parameters = (is_object($parameters)) ? $parameters : null;
        $this->jsonId = $jsonId;
        
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
    **/
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])
                == 'xmlhttprequest'
            );
    }
    
    /**
    *   init
    *
    **/
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
    **/
    protected function setParameters()
    {
        if (isset($this->parameters)) {
            $this->parameters = $this->parameters->fromArray(
                $this->rawData['parameters']
            );
        }
    }
    
    /**
    *   setPageInfos
    *
    **/
    protected function setPageInfo()
    {
        $this->pageInfo = new SigmagridPageInfo(
            $this->rawData['pageInfo']
        );
    }
    
    /**
    *   setColumnInfo
    *
    **/
    protected function setColumnInfo()
    {
        $this->columnInfo = new SigmagridColumnInfos(
            $this->rawData['columnInfo']
        );
    }
    
    /**
    *   setSortInfo
    *
    **/
    protected function setSortInfo()
    {
        $this->sortInfo = new SigmagridSortInfos(
            $this->rawData['sortInfo']
        );
    }
    
    /**
    *   setFilterInfo
    *
    **/
    protected function setFilterInfo()
    {
        $this->filterInfo = new SigmagridFilterInfos(
            $this->rawData['filterInfo']
        );
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
            break;
        }
        return false;
    }

    public function isValidExportFileName($val)
    {
        if (!isset($val)) {
            return true;
        }
        return Validate::isTextEscape($val, 0, 100, null, '\r\n\t')
            && !Validate::hasTextHankaku($val)
            && !Validate::hasTextHtml($val)
            && !Validate::hasTextDatabase($val);
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
