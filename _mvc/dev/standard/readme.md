#

##230101 session

test1 SessionFileHanlder動作確認

- session_wite_cloeseやsession_gc()が動かない
- session.gc_probability&gc_divisorによりgc()がcallする場合がある
- session_start()するとopen()&read()[&gc()]&destroy()がcallされる
- gc()が確率に的中するとsession_start()時にread()後にcallされた
- session_start()しないと$_SESSIONはNULLで＄＿SESSION['a']が代入できない
- __construct&__desutuctは動作する
- max_life_timeは1440の初期値だった事から

これらから

- open()で保存先pathを取得
- read()でファイル名を取得
- gc()がcallされた場合有効期間で古いファイルを削除
- __desutuct()でwrite()を内部的にcallしファイルを保存する


