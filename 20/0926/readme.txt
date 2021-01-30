#Document Block Tool

##既存のライブラリ
<https://github.com/mbrowniebytes/PHP-Docblock-Generator>

- php5まで
- methodとclassはOK
- type hintはだめ
- property/constantはなし
- 1ファイルなのは簡単で良い
- とりあえず改造して使うのもアリ(MIT license)
- 
- 

##
file/dirを引数に読み込んだphpソースファイルを解析し、DocBlockを生成、新ファイルに出力する

##欲しい機能

- 設定ファイル
- filr/dir recurcive読込
- 除外ファイル設定
- ファイルヘッダテンプレート読込・反映({{xxx}}で、class名とかいれる?)
- psr-5/19対応 ==>難しいので最低限に
- 既存の　DocBlock　を上書き/パス
- token_get_contentsが必要 ==> constant,propertyの行位置が分からない
- 
- console=>(configParser)=>finder=>main(analyzer=>DockBulockGenerator=>writer)
- 
- analyzer,generator,writer()
- console,finder,main(reader含む?)
- filetemplatereader/Writer,configReader/Parser
- 
- 
- 
- 

##解析

- Reflectionとtoken_get_contentsの結果をまとめ、Writerに渡す
- reflect->getDocCommentがあるという事は、commentがある場合、コメントを含む?
-　行番号で昇順?降順? 昇順の方がwriteでメモリ食わない
- 解析結果 行番号でsortするからarray?
- パラメータも順番が必要 => tokenで出てきた順番に
- 


##解析結果

- 行番号
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 
- 






