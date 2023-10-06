#

- プロジェクト画面のDB負担を減らしたい
- 一覧表などのデータのキャッシュをできないか
- データはjsonして保存
- 部門などのキーで別のキャッシュである必要
- DB各テーブル更新でキャッシュクリアが必要
- そうするとEvent機能が良いのか?
- テーブル名でリセットするにはSQLを覚える?
- 正規表現などでSQLの部分一致でリセット
- model内でsql, bindValue をキーに json(fetchAll())をキャッシュ?
- キャッシュというより Facade DB っぽいが・・・
- 登録キー検索用キャッシュとデータ用キャッシュを別々?
- DBテーブルなら1テーブル
- has(key)ではDBテーブル名の正規表現一致できない
- DBとした場合、負荷集中が・・・


## 230305

yiiサンプルのpsr-14イベント

- 現在のソフトの影響を最小限にしてeventを実装したい
    - static classにして model内部でnew無しにcallしたい
    - StandardEvent でstatic callしてEventを登録・実行できるか?
- 引数がobjectという事は、 StandardEvent で EventObject組み立てる?
- addListenerキーはは __CLASS__,__METHOD__,$argv,[before/after]のような文字?
- addListenerもfireEventも簡単に理解でき 調べられるルールが必要

```php

StandardEvent::add(fn($this) => echo date('Ymd His'));

StandardEvent::dispatch($this);


### 資料

[sample](https://github.com/yiisoft/event-dispatcher])
