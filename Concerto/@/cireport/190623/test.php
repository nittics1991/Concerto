
全体のカウント数リスト
http://aaa/phpcs/totalcounts/

全体のカウント数のchart?
http://aaa/phpcs/totalcounts/chart

ID回の詳細
http://aaa/phpcs/ID

ID回のfile[x]のerrorsリスト
http://aaa/phpcs/ID/FileId


////////////////////////////////

folder構成

jenkins参考

/phpcs
	phpcs.xml
	script.sh=>ユーザが設定
	screen.log	=> shのstdout
	
	totalcounts.cache => エラー数のチャート用データ
	
	workspace/	==>履歴dirにコピー
		checkstyle.xml	解析結果の保存先
	
	1/
		screen.log	
		workspace/
			checkstyle.xml
	2/
	3/

////////////////////////////////

checkstyle fileのwrapper domain class

phpcs
	file[0]
		error[0]

各データ　dom　elementと同じ　SimpleXml？
wrapper class作る?

////////////////////////////////

chart data domain
チャートに送るデータ1個分

class
{
	$id	//実行No
	$count	//回数
	
	//特にmethodなし?
}

////////////////////////////////

chart dataset domain agg
チャートに送るデータ全体

class
{
	$array
	
	//特にmethodなし?
}

////////////////////////////////

チャートデータcache
totalcounts.cacheファイルrepository

class
{
	public function　__construct(
		$basepath,
		$相対pass workspace/checkstyle.xml
		
		$parser 移譲class
	)
	
	public function all()
	
	public function findById($id)
	
	//project dirを全読み込み
	//totalcounts.cacheファイルに保存
	public function create()
	
	//totalcounts.cache最終行のIDを読込
	//未読み込みのcheckstyle.xml読込
	//totalcounts.cacheファイルに保存
	public function update()
	
	//totalcounts.cacheファイル削除
	public function delete()
	
	
}

////////////////////////////////


////////////////////////////////

data mapper model class
//DBと違って不要? VOとして存在?

class
{
	$id
	$count
}

////////////////////////////////


////////////////////////////////

////////////////////////////////







////////////////////////////////









