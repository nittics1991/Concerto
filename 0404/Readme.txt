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


Database
    phpのsql buidを考えると日付は文字列
    従ってTEXT,int(場合によってintX)が主
    floatをどうする?=>numericが基本==>なるべくfloat使わない
    Concertoでは直課時間ぐらい
    bcmath.scaleを使うか? float計算はBCMathのWrapperClass?



ReflectAttributeTrait作成中
    現在のConcerto\accessorは凝っている
    簡単化できないか?
    
Postの組み立て　上記定義を実現できるか
UsecasePostで　子クラスの実現を確認する
