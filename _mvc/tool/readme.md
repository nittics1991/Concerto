#tool

簡易的なツールのエントリーポイント

##Usage

```cmd
php app.php toolfile.php [...options]
```

---


##TypeHintChecker

###Description

- phpファイルを読み込み、タイプヒントをチェックする
- プロパティ、メソッドにタイプヒントが無い場合メッセージ出力する
- ソースファイルはPSR1,4準拠である事
- 1file 1class/interface/trait/enumに制限

###Usage

```cmd
php app.php TypeHintCheckerRunner PHPFILE
```
###Notice

- WmiPrvSE.exeが重くなる

