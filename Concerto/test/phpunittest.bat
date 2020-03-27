echo off

cd %~dp0

set PHPCI_PATH=E:\var\phpci
set SETTING_PATH=%PHPCI_PATH%\env

call punit.bat -c %SETTING_PATH%\phpunit.xml --bootstrap  %PHPCI_PATH%\autoload.php --testsuite main %*

