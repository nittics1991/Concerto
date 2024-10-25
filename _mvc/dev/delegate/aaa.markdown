
#210808

- implementsする事は、methodを定義することになる
- 引数や戻り値がclass等で変換が必要な場合は、変換用methodを用意する
- 従って、abstract/traitする標準classは作れそうもない




#210722

- static に対応を考えると,基本的なfunctionはstatic?

- original/delegate class name はproperty削除
- abstract static func で取得する

- call()や$this->delegateは traitから除外
- 別途abstract class?


