# DbgCmd 

CLIデバッグヘルパ

指定したクラスメソッドをラップ実行するヘルパコマンド

```cmd

#php cli
php DbgCmd.php -cMyClass -mMyMethod1

#phpdbg
phpdbg -e DbgCmd.php -- -cMyClass -mMyMethod1

#ヘルプ表示
php DbgCmd.php --help

````

-----------------------------------------------------

