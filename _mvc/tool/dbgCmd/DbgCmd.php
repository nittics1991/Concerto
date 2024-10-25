<?php

/**
*   CLIデバッグヘルパ
*   指定したクラスメソッドをラップ実行するヘルパコマンド
*
*   @version 240821
*/

declare(strict_types=1);

namespace tool\dbgCmd;

use RuntimeException;
use ReflectionClass;

class DbgCmd
{
    /**
    *   @var int return value
    */
    private const RET_OK = 0;
    private const RET_OPTION_PAESE_ERROR = 1;
    private const RET_INVALID_OPTION = 2;
    private const RET_EXEC_ERROR = 3;

    /**
    *   @var string
    *
    *   -cClassName クラス名q
    *   -mMethodName メソッド名
    *   -aClassArgument クラス引数
    *   -AMethodArgument メソッド引数
    *   -bBootstrupFile ブートストラップファイルパス
    *   -h ヘルプ
    */
    private string $shortOptions = 'c:m:a:A:b:h';

    /**
    *   @var array
    *
    *   phpdbg対応の為、longOprionsが使えないがhelpのみ許可
    *   --help ヘルプ
    */
    private array $longOprions = [
        'help::',
    ];

    /**
    *   @var string
    */
    private string $sapi;

    /**
    *   @var string
    */
    private string $className;

    /**
    *   @var string
    */
    private string $methodName;

    /**
    *   @var string[]
    */
    private array $classArgs;

    /**
    *   @var string[]
    */
    private array $methodArgs;

    /**
    *   {inheritDoc}
    *
    *   @param string[] ...$values
    *   @return int
    */
    public function __invoke(array $values): int
    {
        $this->sapi = mb_strtolower(PHP_SAPI);

        $options = $this->extractOptions($values);

        $ret = $this->parseOptions($options);

        if ($ret !== null) {
            return $ret;
        }

        $this->callBootstrap($options);

        $this->setStartBreakPoint();

        return $this->exec();
    }

    /**
    *   extractOptions
    *
    *   @param string[] $arguments
    *   @return string[]
    */
    // private function extractOptions(): array
    private function extractOptions(
        array $arguments,
    ): array
    {
        if (
            $this->sapi === 'cli' ||
            $this->sapi === 'phpdbg'
        ) {
            foreach ($arguments as $i => $arg) {
                unset($arguments[$i]);
                
                if ($arg === '--') {
                    break;
                }
            }
        } else {
            throw new RuntimeException(
                "SAPI must be cli|phpdbg",
            );
        }

        $options = [];

        foreach ($arguments as $arg) {
            $opt = mb_substr($arg, 1, 1);
            $val = mb_substr($arg, 2);
            $options[$opt][] = $val;
        }

        return $options;
  }

    /**
    *   parseOptions
    *
    *   @param string[] $options
    *   @return ?int null:succes others:return code
    */
    private function parseOptions(
        array $options,
    ): ?int {
        if (
            !isset($options['c'][0]) ||
            !isset($options['m'][0])
        ) {
            echo 'must be class&method' . PHP_EOL;
            $this->usage();
            return self::RET_INVALID_OPTION;
        }

        if (
            isset($options['h'][0]) ||
            isset($options['help'][0])
        ) {
            $this->usage();
            return self::RET_OK;
        }

        $this->className = $options['c'][0];

        $this->classArgs = isset($options['a']) ?
            (
                is_array($options['a']) ?
                    $options['a'] :
                    [$options['a']]
            ) :
            [];

        $this->methodName = $options['m'][0];

        $this->methodArgs = isset($options['A']) ?
            (
                is_array($options['A']) ?
                    $options['A'] :
                    [$options['A']]
            ) : [];

        return null;
    }

    /**
    *   usage
    *
    *   @return void
    */
    private function usage(): void
    {
        echo <<< 'EOL'
        
        -------------------------------------------------------------
        NAME
            DbgCmd - CLIデバッグヘルパ
        
        SYNOPSIS
            php app.php dbgCmd -- OPTIONS
            phpdbg -e app.php dbgCmd [PHPDBG OPTIONS] -- OPTIONS
        
        DESCRIPTION
            指定したクラスメソッドをラップ実行するヘルパコマンド
        
        OPTIONS
            -cClassName
                実行するクラス名
            
            -mMethodName
                実行するメソッド名
            
            [-aClassArgument]
                実行するクラスのコンストラクタに引数がある場合に指定
                引数が複数の場合、複数回指定する
            
            [-AMethodArgument]
                実行するメソッドに引数がある場合に指定
                引数が複数の場合、複数回指定する
            
            [-bFilePath]
                ブートストラップを指定
            
            [-h]
            [--help]
                ヘルプ
        EXAMPLE
            php app.php dbgCmd -cMyClass -mMyMethod1
        
            php app.php dbgCmd -cMyClass -mMyMethod2 -a"テスト"
        
            php app.php dbgCmd -cMyClass -mMyMethod3 -a"テスト"
                -A90 -AZ

            #phpdbgの場合MyClassのMyMethod3にbreakpoint初期設定
            phpdbg -e app.php dbgCmd -n -- -cMyClass -mMyMethod3 -a"テスト"
                -A97 -Aa
        
            php app.php dbgCmd -cMyClass -mMyMethod4 -bbootstrap.php
        
        EOL;
    }

    /**
    *   callBootstrap
    *
    *   @param string[] $options
    *   @return void
    */
    private function callBootstrap(
        array $options,
    ): void {
        if (isset($options['b'])) {
            if (!file_exists($options['b'][0])) {
                throw new RuntimeException(
                    "bootstrap file not found:{$options['b'][0]}",
                );
            }

            require_once($options['b'][0]);
        }
    }

    /**
    *   setStartBreakPoint
    *
    *   @return void
    */
    private function setStartBreakPoint(): void
    {
        if ($this->sapi === 'phpdbg') {
            phpdbg_break_method(
                $this->className,
                $this->methodName,
            );
        }
    }

    /**
    *   exec
    *
    *   @return int
    */
    private function exec(): int
    {
        $reflectionClass = new ReflectionClass(
            $this->className,
        );

        $newInstance = $reflectionClass
            ->newInstanceArgs($this->classArgs);

        $ret = call_user_func_array(
            [$newInstance, $this->methodName],
            $this->methodArgs,
        );

        return $ret === false ?
            self::RET_EXEC_ERROR :
            self::RET_OK;
    }
}
