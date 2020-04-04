POST/QUERY

Requestとしてまとめない
POST/QUERY分ける=>役割が異なる=>POSTで余計なqueryデータを渡さない
queryはtoArray()でSession保存用に出力できる
Psr7Request->body()からobjectを生成
__construct(array $data)
初期値は外部で作成
初期値を設定==>append() methodで 追加
validateを内部で定義
toArray()を持つ
ArrayAccessを持つ


