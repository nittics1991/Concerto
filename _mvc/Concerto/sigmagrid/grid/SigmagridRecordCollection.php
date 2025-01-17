<?php

/**
*   SigmagridRecordCollection
*
*   @version 221212
*/

declare(strict_types=1);

namespace Concerto\sigmagrid\grid;

use Concerto\sigmagrid\grid\SigmagridBaseCollection;
use Concerto\standard\DataContainerValidatable;

/**
*   @template TValue
*   @extends SigmagridBaseCollection<TValue>
*/
class SigmagridRecordCollection extends SigmagridBaseCollection
{
    /**
    *   __construct
    *
    *   @param mixed[] $fields
    *   @param mixed[] $params
    *   @param DataContainerValidatable<TValue> $dataObject
    *   @param string $recordType
    */
    public function __construct(
        array $fields,
        array $params,
        DataContainerValidatable $dataObject,
        string $recordType
    ) {
        $dataset = [];

        foreach ($params as $items) {
            $obj = clone $dataObject;
            $data = [];

            foreach ($fields as $key => $field) {
                if ($recordType === 'object') {
                    if (array_key_exists($field, $items)) {
                        $data[$field] = $items[$field];
                    }
                } else {
                    $data[$field] = $items[$key];
                }
            }
            $dataset[] = $obj->fromArray($data);
        }
        parent::__construct($dataset);
    }
}
