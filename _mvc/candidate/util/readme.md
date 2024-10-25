# 230216

Concertoのprojectで使用していそうな処理を簡易に記述するclass
作成した下記classのみプロジェクトに反映していく計画で作成
unit test OK

- DateUtil
	- よく使用していると思われる処理を抜粋したclass object
- NumUtil
	- よく使用していると思われる処理を抜粋したstatic class
- StrUtil
	- mbstringのエラーをthowするmagic method class

-----------------------------------------------------

# 211007

DocComment @version追加
DateObject use DateTimeInterface 追加

phpunit covorege error

> message==>DateTimeInterface can't be implemented by user classes
> file==>E:\www\ConcertoDev3\project\_mvc\Concerto\util\implement\DateObject.php
> line==>18


# 210917

- StringInterface
    - Symfonyとstringyをベースにmethodを定義
    
    - https://packagist.org/packages/danielstjules/stringy
    - https://symfony.com/doc/current/components/string.html
        - https://github.com/symfony/symfony/tree/5.4/src/Symfony/Component/String
    - https://github.com/illuminate/support

