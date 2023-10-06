
#210808

##

正確度と精度
https://ja.wikipedia.org/wiki/%E6%AD%A3%E7%A2%BA%E5%BA%A6%E3%81%A8%E7%B2%BE%E5%BA%A6

有効数字と数値の丸めについて
http://www.kobe-kosen.ac.jp/~kasai/append-1-2.pdf

桁落ちとは(正規化)
https://medium-company.com/%E6%A1%81%E8%90%BD%E3%81%A1%E3%81%A8%E3%81%AF/


##

実装で下記を準備
    BcMath,GMP,int,float 

NumberObject で 処理を作成?
    上記実装をinject? factory?

__construct() で scale を設定
static method で　__construct を callして 生成可能に

int/float <==> string の変換 専用class 準備?
    __construct で int/float 受け入れ?

div()のゼロ割はthrow
    OutObBoundsException? ZeroXXXException?

