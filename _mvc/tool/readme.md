# tool

簡易的なツールのエントリーポイント

-----------------------------------------------------
## Usage

```cmd
php app.php toolname [...options]
```

|Name|Description|
|:--|:--|
|DbgCmd|CLIデバッグヘルパ|
|DumpFunctionList|関数一覧表示|
|TypeHintChecker|タイプヒントチェック|

-----------------------------------------------------
## DbgCmd 

- CLIデバッグヘルパ
- 指定したクラスメソッドをラップ実行するヘルパコマンド
-

### Usage

```cmd

#php cli appcmd
php app.php dbgCmd -- -cMyClass -mMyMethod1

#phpdbg
phpdbg -e app.php dbgCmd -- -cMyClass -mMyMethod1

````

### src

- DbgCmd.php
- DbgCmdRunner.php
- DbgAssertion.php
- bootstrap.php

## Notice

- OPTIONSの-aや-Aは文字列または数値のみ対応

-----------------------------------------------------
## DumpFunctionList 

### Description

- phpファイルを読み込み、使用されている関数をダンプする
- ディレクトリ指定可能
- PhpTokenizerオブジェクトをvar_dumpでダンプする

### Usage

```cmd
php app.php DumpFunctionListRunner PATH
```

### src

- DumpFunctionList.php
- DumpFunctionListRunner.php

-----------------------------------------------------
## TypeHintChecker

### Description

- phpファイルを読み込み、タイプヒントをチェックする
- プロパティ、メソッドにタイプヒントが無い場合メッセージ出力する
- ソースファイルはPSR1,4準拠である事
- 1file 1class/interface/trait/enumに制限

### Usage

```cmd
php app.php TypeHintCheckerRunner PHPFILE
```

### src

- TypeHintChecker.php
- TypeHintCheckerRunner.php

### Notice

- __construct()のようにはreturn typeが無いとチェックに引っかかる
- WmiPrvSE.exeが重くなる

-----------------------------------------------------
