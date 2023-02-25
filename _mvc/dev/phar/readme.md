#

## 230225

- git project の bin/phpunitでprophecyを使うには?
    - 現在別途composerでphpunitをinstallしてunit testしている

- pharにしたらどうなる?
    - clue/phar-composer  build(install) [project] でphar作成
    - require phar してもエラー

- 単純にzipを読み込んでspl_autoloadにてclassをcallできないか?
    - dev/phar test1.php zipファイル読み込みテスト
        - compress.zlib//でアーカイブは読めそう
        - 中のファイルが読めない



