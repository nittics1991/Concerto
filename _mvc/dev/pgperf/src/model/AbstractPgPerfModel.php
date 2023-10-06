<?php

/**
*   AbstractPgPerfModel
*
*   @version
*/

declare(strict_types=1);

namespace pgperf\model;

use Iterable;

abstract class AbstractPgPerfModel
{
    /*
    *   @var ReflectionProperty[]
    *       [[name => $reflectionProperty],...]
    */
    protected readonly array $parsedProperties;

    /*
    *   __construct
    *
    *   @param Iterable|object $dataset
    */
    public function __construct(
        Iterable|object $dataset
    ) {
        $this->parseProperties();
        $this->fromIterable($dataset);
    }

    /*
    *   parseProperties
    *
    *   @return void
    */
    protected function parseProperties(
        Iterable|object $dataset
    ):void {
        $properties = (new ReflectionClass($this))
            ->getProperties();

        foreach($properties as $property) {
            if ($property_name !== 'parsedProperties') {
                $this->parsedProperties[$property->getName()] =
                    $property;
            }
        }
    }

    /*
    *   fromIterable
    *
    *   @param Iterable|object $params
    *   @return void
    */
    protected function fromIterable(
        Iterable|object $dataset
    ):mixed {
        foreach($dataset as $property => $value) {
            if ($this->hasProperty($property)) {
                $this->setProperty(
                    $property,
                    $value,
                );
            } else {
                throw new NotDefinedPropertyException(
                    "property name=${property}",
                )
            }
        }
    }

    /*
    *   hasProperty
    *
    *   @param string $property_name
    *   @return bool
    */
    protected function hasProperty(
        string $property_name
    ):bool {
        return $property_name !== 'parsedProperties' &&
            array_key_exists(
                $property_name,
                $tihs->parsedProperties,
            );
    }

    /*
    *   setProperty
    *
    *   @param string $property_name
    *   @param mixed $value
    *   @return mixed
    */
    protected function setProperty(
        string $property_name,
        mixed $value,
    ):mixed {
        $setter_method_name = 'set' .
            mb_convert_case(
                $property_name,
                MB_CASE_TITLE,
            );

        if (method_exists($setter_method_name) {
            $this->$setter_method_name($property_name);
            return;
        );



//型判定大変


        $this->property_name = match (
            ($this->properties[$property_name])
                ->getType()?
        ) {
            aaa => $this->mutateDateTime(
                $property_name,
                $value,
                );



        }


        
        if (
        ) {

        



        
    }


    
    
    
    


 
}
