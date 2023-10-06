<?php

//5C文字のファイル操作
//表示関係は概ねOK
//ファイル操作系や中身を・・は×
//  file_get_contents
// copy は5Cでaddslashes使用する
//5C処理不要 rename,mkdir,rmdir

//ComFunc.php
ini_set('mbstring.internal_encoding', 'UTF-8');
ini_set('mbstring.detect_order', 'UTF-8,SJIS,EUC-JP,JIS,ASCII');
setlocale(LC_ALL, 'jpn_jpn');


$file = __DIR__ . "\\予定表.txt";
$sjis = mb_convert_encoding($file, 'SJIS', 'UTF8');

echo "ファイル名(UTF8)=";
var_dump($file);
echo "<hr>";
echo "ファイル名(SJIS)=";
var_dump($sjis);
echo "<hr>";

echo "basename=";
var_dump(mb_convert_encoding(basename($sjis), 'UTF8', 'SJIS'));
echo "<hr>";
echo "file_exists=";
var_dump(file_exists($sjis));
echo "<hr>";

echo "★fopen=";
var_dump(fopen($sjis, 'r'));
echo "<hr>";   //addslashesで処置

echo "fileatime=";
var_dump(date('Ymd His', fileatime($sjis)));
echo "<hr>";
echo "filectime=";
var_dump(date('Ymd His', filectime($sjis)));
echo "<hr>";
echo "filemtime=";
var_dump(date('Ymd His', filemtime($sjis)));
echo "<hr>";

echo "filegroup=";
var_dump(filegroup($sjis));
echo "<hr>";
echo "fileowner=";
var_dump(fileowner($sjis));
echo "<hr>";
echo "fileperms=";
var_dump(sprintf("%x", fileperms($sjis)));
echo "<hr>";

echo "filesize=";
var_dump(filesize($sjis));
echo "<hr>";
echo "filetype=";
var_dump(filetype($sjis));
echo "<hr>";

echo "★fnmatch(表)=";
var_dump(fnmatch('*表*', $sjis));
echo "<hr>";
echo "★fnmatch(定)=";
var_dump(fnmatch('*定*', $sjis));
echo "<hr>";

echo "glob=";
var_dump(glob($sjis));
echo "<hr>";

echo "is_dir=";
var_dump(is_dir($sjis));
echo "<hr>";
echo "is_executable=";
var_dump(is_executable($sjis));
echo "<hr>";
echo "is_file=";
var_dump(is_file($sjis));
echo "<hr>";
echo "is_link=";
var_dump(is_link($sjis));
echo "<hr>";
echo "is_readable=";
var_dump(is_readable($sjis));
echo "<hr>";
echo "is_uploaded_file=";
var_dump(is_uploaded_file($sjis));
echo "<hr>";
echo "is_writable=";
var_dump(is_writable($sjis));
echo "<hr>";

echo "lstat=";
var_dump(lstat($sjis));
echo "<hr>";
echo "pathinfo=";
var_dump(pathinfo($sjis));
echo "<hr>";

echo "★readfile=";
var_dump(readfile($sjis));
echo "<hr>";

echo "realpath=";
var_dump(realpath($sjis));
echo "<hr>";

echo "stat=";
var_dump(stat($sjis));
echo "<hr>";

echo "★scandir=";
var_dump(scandir($sjis));
echo "<hr>";





$copy = __DIR__ . '\\copy.txt';
echo "★copyを実行=";   //addslashesで処置
copy($sjis, $copy);
var_dump(file_exists($copy));
echo "<hr>";

//ダミーデータ作成
$file2 = __DIR__ . '\\予定表申請.txt';
$sjis2 = mb_convert_encoding($file2, 'SJIS', 'UTF8');
exec("copy /Y {$sjis} {$sjis2}");

$rename = __DIR__ . '\\rename.txt';
echo "renameを実行=";
rename($sjis2, $rename);
var_dump(file_exists($rename));
echo "<hr>";

rename($rename, $sjis2);
echo "unlinkを実行(false)=";
unlink($sjis2);
var_dump(file_exists($rename));
echo "<hr>";

echo "★file_get_contents=";   //addslashesで処置
var_dump(mb_convert_encoding(file_get_contents($sjis), 'UTF8', 'SJIS'));
echo "<hr>";


echo "★finfo=";   //addslashesで処置
$finfo = new finfo();
var_dump($finfo->file($sjis));
echo "<hr>\r\n";


echo "★SplFileInfo=";
$obj = new SplFileInfo($sjis);
var_dump($obj->getFilename());
echo "<hr>\r\n";




///////////////////////////////////////////////////////////////////////////////////////////////////

$dir = __DIR__ . "\\表示\\予定表.txt";
$dirs = mb_convert_encoding($dir, 'SJIS', 'UTF8');

echo "dirname=";
var_dump(mb_convert_encoding(dirname($dirs), 'UTF8', 'SJIS'));
echo "<hr>\r\n";

echo "★chdir=";
var_dump(chdir($dirs));
echo "<hr>\r\n";

$dir2 = __DIR__ . "\\表示盤";
$dirs2 = mb_convert_encoding($dir2, 'SJIS', 'UTF8');
echo "mkdir=";
var_dump(mkdir($dirs2));
echo "<hr>\r\n";

rmdir($dirs2);
echo "rmdir(false)=";
var_dump(file_exists($dirs2));
echo "<hr>\r\n";


///////////////////////////////////////////////////////////////////////////////////////////////////

$dir = __DIR__ . "\\表示";
$dirs = mb_convert_encoding($dir, 'SJIS', 'UTF8');
$dirss = addslashes($dirs);


echo "★opendir =";
var_dump(opendir($dirs));
echo "<hr>\r\n";

echo "★dir =";
var_dump(dir($dirs));
echo "<hr>\r\n";

echo "★scandir =";
var_dump(scandir($dirs));
echo "<hr>\r\n";


echo "chdir =";
var_dump(chdir($dirs));
echo "<hr>\r\n";

echo "getcwd =";
var_dump(mb_convert_encoding(getcwd(), 'UTF8', 'SJIS'));
echo "<hr>\r\n";
