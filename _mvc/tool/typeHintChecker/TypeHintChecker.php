<?php

/**
*   TypeHintChecker
*
*   @version 240819
*/

declare(strict_types=1);

namespace tool\typeHintChecker;

use InvalidArgumentException;
use PhpToken;
use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use RuntimeException;

class TypeHintChecker
{
    /**
    *   @var int[]
    */
    private const REQUIRED_CLASS_IDS = [
        T_CLASS,    //369
        T_ENUM, //370
        T_INTERFACE,    //371
        T_TRAIT,    //372
    ];

    /**
    *   @var PhpToken[]
    */
    public array $phpTokens;

    /**
    *   @var string
    */
    public string $currentNamespace;

    /**
    *   @var string
    */
    public string $className;

    /**
    *   @var string[]
    */
    public array $methodNames;

    /**
    *   @var string[]
    */
    public array $messages;

    /**
    *   __construct
    *
    */
    public function __construct()
    {
        $this->phpTokens = [];
        $this->currentNamespace = '';
        $this->className = '';
        $this->methodNames = [];
        $this->messages = [];
    }

    /**
    *   __invoke
    *
    *   @param string $path
    *   @return string[] no typehint message
    */
    public function __invoke(
        string $path,
    ): array {
        if (
            !file_exists($path) ||
            !is_readable($path)
        ) {
            throw new InvalidArgumentException(
                "invalid file:{$path}"
            );
        }

        return $this->execute($path);
    }

    /**
    *   execute
    *
    *   @param string $path
    *   @return string[]
    */
    private function execute(
        string $path,
    ): array {
        $contents = $this->readFile($path);

        $this->lexicalAnalysis($contents);

        $this->syntacticAnalysis();

        $this->classAnalysis();

        return $this->messages;
    }

    /*
    *   readFile
    *
    *   @param string $path
    *   @return string
    */
    private function readFile(
        string $path,
    ): string {
        $contents = file_get_contents(
            $path,
        );

        if ($contents === false) {
            throw new RuntimeException(
                "file read error:{$path}"
            );
        }

        return $contents;
    }

    /*
    *   lexicalAnalysis
    *
    *   @param string $contents
    *   @return void
    */
    private function lexicalAnalysis(
        string $contents,
    ): void {
        $this->phpTokens = PhpToken::tokenize(
            $contents,
        );
    }

    /*
    *   syntacticAnalysis
    *
    *   @return void
    */
    private function syntacticAnalysis(): void
    {
        for ($pos = 0; $pos < count($this->phpTokens); $pos++) {
            //375
            if (($this->phpTokens[$pos])->is(T_NAMESPACE)) {
                $pos = $this->getNamespace($pos);
            }

            if ($this->isClass($pos)) {
                $pos = $this->getClassName($pos);
                continue;
            }

            //347
            if (($this->phpTokens[$pos])->id === T_FUNCTION) {
                $pos = $this->getMethodName(
                    $pos,
                    $this->className,
                );
            }
        }
    }

    /*
    *   getNamespace
    *
    *   @param int $position
    *   @return int moved position
    */
    private function getNamespace(
        int $position,
    ): int {
        for (
            $pos = $position;
            $pos < count($this->phpTokens);
            $pos++
        ) {
            //316|313
            if (
                $this->currentNamespace === '' && (
                    ($this->phpTokens[$pos])->is(T_NAME_QUALIFIED) ||
                    ($this->phpTokens[$pos])->is(T_STRING)
                )
            ) {
                $this->currentNamespace =
                    ($this->phpTokens[$pos])->text;
                return ++$pos;
            }
        }

        return ++$position;
    }

    /*
    *   isClass
    *
    *   @param int $position
    *   @return bool
    */
    private function isClass(
        int $position,
    ): bool {
        return $this->className === '' &&
            in_array(
                ($this->phpTokens[$position])->id,
                self::REQUIRED_CLASS_IDS,
            ) &&
            ($this->phpTokens[$position - 1])->id !== 402; //T_DOUBLE_COLON
    }

    /*
    *   getClassName
    *
    *   @param int $position
    *   @return int moved position
    */
    private function getClassName(
        int $position,
    ): int {
        for (
            $pos = $position;
            $pos < count($this->phpTokens);
            $pos++
        ) {
            $id = ($this->phpTokens[$pos])->id;

            //313
            if ($id === T_STRING) {
                $this->className =
                    $this->currentNamespace .
                    '\\' .
                    ($this->phpTokens[$pos])->text;

                return ++$pos;
            }
        }

        return ++$position;
    }

    /*
    *   getMethodName
    *
    *   @param int $position
    *   @param string $className
    *   @return int moved position
    */
    private function getMethodName(
        int $position,
        string $className,
    ): int {
        for (
            $pos = $position;
            $pos < count($this->phpTokens);
            $pos++
        ) {
            $id = ($this->phpTokens[$pos])->id;

            //313
            if ($id === T_STRING) {
                $this->methodNames[] =
                    ($this->phpTokens[$pos])->text;

                return ++$pos;
            }
        }

        return ++$position;
    }

    /*
    *   classAnalysis
    *
    *   @return void
    */
    private function classAnalysis(): void
    {
        $reflectionClass = new ReflectionClass(
            $this->className,
        );

        $this->checkProperty(
            $reflectionClass,
        );

        $this->checkMethod(
            $reflectionClass,
        );
    }

    /*
    *   checkProperty
    *
    *   @param ReflectionClass $reflectionClass
    *   @return void
    */
    private function checkProperty(
        ReflectionClass $reflectionClass,
    ): void {
        $properties = $reflectionClass->getProperties();

        foreach ($properties as $property) {
            if ($property->getType() === null) {
                $this->setPropertyMessage(
                    $property
                );
            }
        }
    }

    /*
    *   setPropertyMessage
    *
    *   @param ReflectionProperty $reflectionProperty
    *   @return void
    */
    private function setPropertyMessage(
        ReflectionProperty $reflectionProperty,
    ): void {
        $this->messages[] = sprintf(
            "class:%s,property:%s",
            $this->className,
            $reflectionProperty->getName(),
        );
    }

    /*
    *   checkMethod
    *
    *   @param ReflectionClass $reflectionClass
    *   @return void
    */
    private function checkMethod(
        ReflectionClass $reflectionClass,
    ): void {
        $methods = $reflectionClass->getMethods();

        foreach ($methods as $method) {
            $methodName = $method->getName();

            if (
                !in_array(
                    $methodName,
                    $this->methodNames,
                )
            ) {
                continue;
            }

            if ($method->getReturnType() === null) {
                $this->setReturnTypeMessage(
                    $methodName,
                    $method,
                );
            }

            $this->checkArgument(
                $methodName,
                $method
            );
        }
    }

    /*
    *   setReturnTypeMessage
    *
    *   @param string $methodName
    *   @param ReflectionMethod $reflectionMethod
    *   @return void
    */
    private function setReturnTypeMessage(
        string $methodName,
        ReflectionMethod $reflectionMethod,
    ): void {
        $this->messages[] = sprintf(
            "class:%s,method:%s",
            $this->className,
            $methodName,
        );
    }

    /*
    *   checkArgument
    *
    *   @param string $methodName
    *   @param ReflectionMethod $reflectionMethod
    *   @return void
    */
    private function checkArgument(
        string $methodName,
        ReflectionMethod $reflectionMethod,
    ): void {
        $parameters = $reflectionMethod->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter->getType() === null) {
                $this->setParameterMessage(
                    $methodName,
                    $parameter
                );
            }
        }
    }

    /*
    *   setParameterMessage
    *
    *   @param string $methodName
    *   @param ReflectionParameter $reflectionParameter
    *   @return void
    */
    private function setParameterMessage(
        string $methodName,
        ReflectionParameter $reflectionParameter,
    ): void {
        $this->messages[] = sprintf(
            "class:%s,method:%s,argument:%s",
            $this->className,
            $methodName,
            $reflectionParameter->getName(),
        );
    }
}
