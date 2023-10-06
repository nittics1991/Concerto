# pgperf


## 220501

- CQRS
    - pgperfではCommandなし
        - CommandドメインのModelは無し
    - Queryは、画面OBJECTを考慮する
        - class TableListsやUserTables(画面とDB Tableがたまたま一緒)など

- Actionの処理
    - $UserTables->findBy($sid)という感じで
    - $UserTable->schemaname(property? method?) みたいなアクセス

- Presentation-ViewModel-DomainModel
    - UIはリスト(Sigmagrid etc)　チャート(JqChart etc) CSV などが考えられる
        - UIは結局　数値、文字列くらい?
    - UIライブラリに合わせたViewModel
        - UserTablesSigamgrigViewModel extends SigamgrigViewModel
            - DomainModelから値を取り出して、UIに合わせて加工する
            - $domainModel->stasus(ENUM)
            - $domainModel->valuum_date(VacuumDateTime)　
    - ViewModeに関係なく、Requestに対応したDomainModel
        - 日時 DateTimeをwrapした VacuumeDateTimeなど
        - 文字列 MbStringObject? をwrap
        - 数値 BcMathObject?  をwrap
        - 状態|フラグ Enum をwrap

- Repository
    - RepositoryInterfaceをwrapした(例えばfind())Cacheで高速化
    - cache-Repository-DataMapperInterface(PosgreやSqliteをwrap)-DataMapper()
    - Cacheの更新
        - 通常はEventを利用
        - pgperfはinsertなどのcommandは無い為、別手段を考える
            - cacheは、例えばテーブルリスト
    - Domeinとの受け渡しは、arrayで
        - 結局PDOStatementのfetchは添字配列が便利そう
            - StdClassとした場合、array渡しでUserTabelsCollection?

- UserTablesをCollectionとした場合のメリットは?
    - メモリは少ないかもしれない
        - ArrayObject->constructはobjectも受付けるので、内部で利用するか?
            - 継承はせず移譲での内部保持用
            


## 220430

vendorパッケージやjs/cssのCDN利用を考慮する
ただし、切り替え出来るように考える


- model
    - DBテーブルと同じカラム名で
    - スカラ値+DateTime?
- collection
    - get(sid)
    - get(table_name)

- PDO
    - 上手くfetchでmodelやcollectionを作りたい
    - 
- 


## 220427

- http://xxx/index.php?type=snap&data=user_table&out=chart
	- type snap:生データ diff:差分
	- data モデル名
	- out list:表 bar:棒グラブ line:線グラフ csv:CSV

-
	bin/
		setup.sh
	log/
	public/
		css/
			
		js/
			
		index.php
	src/
		boot/
			bootstrap.php
			iniset.php
		config/
			
			
			
		model/
			UserTablesModel.php
		request/
			Query.php
		template/
			Template.php
		
		
		
		
	test/
	tmp/
	vendor/
	composer.json


##

[github uptimejp/pgperf](https://github.com/uptimejp/pgperf)
[docs Postgres Toolkit](https://postgres-toolkit-ja.readthedocs.io/ja/latest/index.html)
