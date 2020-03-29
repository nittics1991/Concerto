<?php









///////////////////////////////////////////////////////////

//Martin Fowler's Blikiの日本語翻訳サイト
http://bliki-ja.github.io/

//よくわかるSOLID原則
https://note.com/erukiti/n/n67b323d1f7c5

//Repositoryパターンのアンチパターン
https://qiita.com/mikesorae/items/ff8192fb9cf106262dbf

//プログラミング中級者に読んでほしい良いコードを書くための20箇条
https://anopara.net/2014/04/11/%E3%83%97%E3%83%AD%E3%82%B0%E3%83%A9%E3%83%9F%E3%83%B3%E3%82%B0%E4%B8%AD%E7%B4%9A%E8%80%85%E3%81%AB%E8%AA%AD%E3%82%93%E3%81%A7%E3%81%BB%E3%81%97%E3%81%84%E8%89%AF%E3%81%84%E3%82%B3%E3%83%BC%E3%83%89/

///////////////////////////////////////////////////////////
// domainのbase classとdataMapperのbase classの構造は違う
//domainはできるだけimmutable　で cast/mutatoは 使わない
//datamapperは　PDOからのfetchで書き込まれるので、型変換が必要

//VOはarrayが該当 new DomainClass($vo->toArray())
//その場合、型変換はどうする?
// new DomainClass(['prop1'=>new AAA($vo->prop1), ...]
//これだと面倒 どうする?
//DTOだと Assenbler でarrayを作っている
//CQRS　だと 専用処理でユースケースに従ったValueObjectになる
//DTO/DPOでは　専用ではなく汎用
//更新時は コマンドという更新の指示をまとめた入れ物を　App=>Domainになる
//という事は CQRS で 専用class が良さそう ==>現状のイメージ

//MstTantoの登録とPassword変更では 同じEmployeeでも構造が違う
//単一責任の原則では　classやmethodは　単一のアクターにのみ機能を提供する
//アクターは オペレータや管理者 自動処理、概念、機械

///////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////
//TypedPropertyのAccessor

//書いてみたけど　まずは Domain データ構造を考える
//後から 共通的な処理を use trait したい

/**
*   aaaa
*
*   @version 190515
*/

declare(strict_types=1);

namespace Concerto\accessor;

use InvalidArgumentException;
use ReflectionClass;

class AAA
{
    /*
    protected bool $bool = false;
    protected int $int = 0;
    protected float $float = 0.0;
    protected string $string = '';
    protected array $array = [];
    protected object $object;
    protected iterable $iterable = [];
    protected self $self;
    protected MyClass $myClass;
    protected ?bool $nullable = null;
    
    
    
    */
    
    ///////////////////////////////////////////////////////////
    //  immutable
    ///////////////////////////////////////////////////////////
    
    /*
    * 
    */
    public function __isset(string $name)
    {
        $reflection = new ReflectionClass(static::class);
        return $reflection->hasProperty($name);
    }
    
    /*
    * 
    */
    public function __get(string $name)
    {
        $reflection = new ReflectionClass(static::class);
        if ($reflection->hasProperty($name)) {
            return $this->$name;
        }
        throw new InvalidArgumentException(
            "not defined property:{$name}"
        );
    }
    
    /*
    * 
    * 
    * 
    */
    protected function fromArray(array $data)
    {
        $reflection = new ReflectionClass(static::class);
        
        foreach ($data as $name => $val) {
            if (!$reflection->hasProperty($name)) {
                throw new InvalidArgumentException(
                    "not defined property:{$name}"
                );
            }
            
            //mutator
            $mutator = "set" . MbString::toUpperCamel($name);
            if ($reflection->hasMethod($name)) {
                $this->$name($val);
                continue;
            }
            
            $this->$name = $val;
        }
        return $this;
    }
    
    
    
    ///////////////////////////////////////////////////////////
    //  mutator
    ///////////////////////////////////////////////////////////
    
    
    
    
    
    
    
    
    
}
