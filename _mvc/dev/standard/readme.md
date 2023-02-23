#

## 230223 session

test2 SessionFileHandlerの動作確認

- session_start()でread()がcallされる
- session_wite_close()後 再度 session_start()
    -Session cannot be started after headers have already been sent
    @session_start()で抑制が必要
- session_id("XXXX")で設定
    - 新規ID=新規ファイルではsession_id()=XXXX
    - 再度実行=既存ファイルではsession_id=empty




test1 標準session動作確認

- CLIでもsession自体は動作する
    - session_wite_cloese()でデータ保存はする
    - ただし毎回idは変わる=保存ファイルも変わる
- session_start()前にsession_status()=1するとwarning
    - その後sessionは動作しない status=1 ID=empty
- session_start()後にsession_start()=2
    - 従って session_statusは実行しないほうが良い
- $_SESSION session_start()前 NULL 後 []
- session_regenerate_id() は warning
    - id　は変わらない

## 230101 session

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


