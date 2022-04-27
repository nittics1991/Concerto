#

## 220328

ConfigのConcerto対応を検討

- ConfigをContainerに入れる
	- Configをcontainerから取得し、DotNotation的な取得を考えたい
- Hashのようなライブラリ部分と、Concerto専用の部分を分ける
- 影響を考えると、専用部分はConcerto\standardにする?
- 


### ディレクトリ内の設定を追加する


```php

class DirectoryConfig
{
	
	
	
	
	//再帰的にPATH以下を追加
	//regexでフィルタ
	public function xxx(
		$path,	//対象PATH
		$regex,	//PATHフィルター(includeとexcludeを同時にするため)
	)
	{
		//
		$itelater = new RecursiveDirectoryItelater(
			new RegexIteater(
				new FilesystemItelater($path),
				$regex,
			)
		);
	}
	
	//ファイルbaseName+配列のarrayDotNotation化した名前とする
	
	public function get($name)
	{
		$baseName = baseName($path);
		
		
		
		
		
	
	}
}

```

### psr/containerとの連携

- ServiceProviderでcontainerから取得できるようにする
- 複数のDirectoryConfigを指定できるようにする
	- Concertoの_config\のcommonとitc_workXがあるので
	- 現在のreplace()機能も必要
- containeのconfigのデータ取得は、"config.xxx.yyy...."としたい
	- config.common.system.database.dns.symphony
	- config.seiban_kanri2.cyunyu_inf_disp.kb_nendo.color.warning



```php

class ConfigServiceProvider
{
	
	//DirectoryConfigを読み込み、baseNameからの名前とする
	public function xxx(
		$baseName,	//containeデータ名前空間のprefix
		$basePath,	//config dirのベースPATH　ここ以下のDIRを対象
		$filterRegex,	//DirectoryConfigのフィルタregex
	)
	{
	}
}

```

### Concerto\standard\factoryのcontainer利用

- 現在のgetXXX()を__callで省略可能にしたい
	- reflectionで引数の解決が必要
	- GetAccessetTraitを作成
		- SetAccesserTraitの共通に(Exclude)AccesserTraitを作成
			- 配列で対象propertyを除外|指定|両方
	
	
	
	