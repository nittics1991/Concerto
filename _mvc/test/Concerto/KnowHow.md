# phpunit

## KnowHow

### error native_function_overwrite

- namespaceを使う
- @see HttpFileUploadTest


### error headers_already_sent

- テストのアノテーションに
- @runInSeparateProcessを付ける
- 定数defineのテストも同様

### テスト対象からの除外

-各テストクラスのdocコメントアノテーションに下記を追加
	- @group excludes
- phpunit 実行時　--exclude-group=excludes で実行



