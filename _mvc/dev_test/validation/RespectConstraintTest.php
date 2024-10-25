<?php

namespace test\Concerto\validation;

use Datetime;
use StdClass;
use Respect\Validation\RespectConstraintServiceProvider;
use test\Concerto\ConcertoTestCase;
use dev\container\ServiceContainer;
use dev\container\ServiceProviderContainer;
use dev\validation\Validator;
use dev\validation\respect\RespectValidationServiceProvider;

class RespectConstraintTest extends ConcertoTestCase
{
    public function ruleset()
    {
        return [
            'age' => 'Age,10,50',
            'alnum' => 'alnum,-',
            'alpha' => 'alpha',
            'alwaysValid' => 'alwaysValid',
            //'alwaysInValid' => 'alwaysInValid',
            'arrayVal' => 'arrayVal',
            'Arr' => 'ArrayType',
            'attr' => 'Attribute,foo',
            'between' => 'Between,3,7',
            'boolType' => 'boolType',
            'bsn' => 'bsn',
            //'call' => 'call:function($val) {return $val;}',
            'callableType' => 'callableType',
            'callback' => 'callback,is_int',
            'charset' => 'charset,ASCII',
            'cnh' => 'cnh',
            'cnpj' => 'cnpj',
            'Cntrl' => 'Cntrl',
            'consonant' => 'consonant',
            'contains' => 'contains,banana',
            'containsArray' => 'contains,banana',
            'countable' => 'countable',
            'countryCode' => 'countryCode',
            'cpf' => 'cpf',
            'creditCard' => 'digit|creditCard',
            'date' => 'date,Y-m-d',
            'digit' => 'digit',
            'directory' => 'directory',
            'domain' => 'domain',
            'email' => 'email',
            'endsWith' => 'endsWith,banana',
            'equals' => 'equals,banana',
            'even' => 'even',
            'exists' => 'exists',
            //'cxecutable' => 'cxecutable',
            //'extension' => 'extension,png',
            'factor' => 'factor,4',
            'False' => 'FalseVal',
            'fibonacci' => 'fibonacci',
            //'file' => 'file',
            // 'filtervar' => 'filtervar,FILTER_VALIDATE_URL,FILTER_FLAG_PATH_REQUIRED',
            'finite' => 'finite',
            'floatVal' => 'floatValue',  //use alias
            'floatType' => 'floatType',
            'graph' => 'graph',
            'hexRgbColor' => 'hexRgbColor',
            'identical' => 'identical,abc',
            //'image' => 'image',
            'in' => 'in,lorem ipsum',
            'infinite' => 'infinite',
            'instance' => 'instance,DateTime',
             'intVal' => 'intValue',  //use alias
            'intType' => 'IntType',
            //'ip' => 'ip',
            'iterableType' => 'iterableType',
            'json' => 'json',
            'key' => 'key,foo',
            // 'keyNested' => 'keyNested,foo.nested',
            'languageCode' => 'languageCode',
            'leapDate' => 'leapDate,Y-m-d',
            'leapYear' => 'leapYear',
            'length' => 'length,5,10',
            'lowercase' => 'lowercase',
            'macAddress' => 'macAddress',
            'max' => 'max,10',
            //mimeType => 'mimeType,image/png',
            'min' => 'min,10',
            'minimumAge' => 'minimumAge,18,"Y-m-d"',
            'multiple' => 'multiple,3',
            'negative' => 'negative',
            'no' => 'no',
            'notBlank' => 'notBlank',
            'notEmpty' => 'notEmpty',
            'notOptional' => 'notOptional',
            'noWhitespace' => 'noWhitespace',
            'nullType' => 'nullType',
            'numeric' => 'numeric',
            'objectType' => 'objectType',
            'odd' => 'odd',
            'perfectSquare' => 'perfectSquare',
            'positive' => 'positive',
            // 'postalCode' => 'postalCode,JP',
            'primeNumber' => 'primeNumber',
            'prnt' => 'prnt',
            'punct' => 'punct',
            'readable' => 'readable',
            'regex' => 'regex,/5/',
            'resourceType' => 'resourceType',
            'roman' => 'roman',
            'scalarVal' => 'scalarVal',
            'size' => 'size,1KB,1MB',
            'slug' => 'slug',
            'space' => 'space,b',
            'startsWith' => 'startsWith,test',
            'String' => 'StringType',
            //'subdivisionCode' => '13',
            //'symbolicLink' =>'symbolicLink',
            'tld' => 'tld',
            'True' => 'TrueVal',
            'type' => 'type,object',
            //'uploaded' => 'uploaded',
            'uppercase' => 'uppercase',
            'url' => 'url',
            'version' => 'version',
            'video1' => 'videoUrl',
            'vimeo' => 'videoUrl,vimeo',
            'vowel' => 'vowel',
            'writable' => 'writable',
            'xdigit' => 'xdigit',
            'yes' => 'yes',
        ];
    }

    public function dataset()
    {
        $attr = new \StdClass();
        $attr->foo = 'bar';

        $keys = [
            'foo' => 'bar',
            'nested' => [
                'bas' => 'bee'
            ]
        ];

        return [
            'age' => '11 years ago',
            'alnum' => 'banana-123 ',
            'alpha' => 'banana',
            'alwaysValid' => '@#$_',
            //'alwaysInValid' => '@#$_',
            'arrayVal' => new \ArrayObject(),
            'Arr' => ['Brazil'],
            'attr' => $attr,
            'between' => 5,
            'boolType' => is_int(2),
            'bsn' => '612890053',
            //'call' => true,
            'callableType' => 'trim',
            'callback' => 20,
            'charset' => 'acucar',
            'cnh' => '02650306461',
            'cnpj' => '68518321000116',
            'Cntrl' => "\r\n\t",
            'consonant' => 'dcfg',
            'contains' => 'www banana jfk http',
            'containsArray' => ['www', 'banana', 'jfk', 'http'],
            'countable' => new \ArrayObject(),
            'countryCode' => 'BR',
            'cpf' => '22205417118',
            'creditCard' => '5555666677778884',
            'date' => '2018-12-21',
            'digit' => '120129  21212',
            'directory' => __DIR__,
            'domain' => 'google.com.br',
            'email' => 'example@google.com',
            'endsWith' => 'pera banana',
            'equals' => 'banana',
            'even' => 8,
            'exists' => __FILE__,
            //'cxecutable' => 'foo.sh',
            //'extension' => 'img:png',
            'factor' => 2,
            'False' => false,
            'fibonacci' => 34,
            //'file' => __FILE__,
            //'filtervar' => 'http://example.com/path',
            'finite' => 999,
            'floatVal' => '9.8',
            'floatType' => 9.8,
            'graph' => 'LKM@#$%4;',
            'hexRgbColor' => '#ff00aa',
            'identical' => 'abc',
            //'image' => 'image.png',
            'in' => 'ipsum',
            'infinite' => INF,
            'instance' => new Datetime(),
            'intVal' => 9,
            'intType' => 9,
            //'ip' => '192.168.0. 1',
            'iterableType' => new \ArrayObject(),
            'json' => '{"file":"laravel.php"}',
            'key' => $keys,
            //'keyNested' => $keys,
            'languageCode' => 'en',
            'leapDate' => '1988-02-29',
            'leapYear' => '1988',
            'length' => 'abcdefgh',
            'lowercase' => 'brazil',
            'macAddress' => '00:11:22:33:44:55',
            'max' => 9,
            //'mimeType' => 'image.png',
            'min' => 11,
            'minimumAge' => '1999-1-1',
            'multiple' => '9',
            'negative' => '-10',
            'no' => 'N',
            'notBlank' => 'aa',
            'notEmpty' => 'aa',
            'notOptional' => '　',
            'noWhitespace' => 'laravelBrazil',
            'nullType' => null,
            'numeric' => '179.9',
            'objectType' => new stdClass(),
            'odd' => 3,
            'perfectSquare' => 25,
            'positive' => 1,
            //'postalCode' =>'183-0041',
            'primeNumber' => 7,
            'prnt' => 'LMKA0$% _123',
            'punct' => '&,.;[]',
            'readable' => __FILE__,
            'regex' => '5',
            'resourceType' => fopen(__FILE__, 'r'),
            'roman' => 'VI',
            'scalarVal' => 'ABC',
            'size' => __FILE__,
            'slug' => 'laravel-brazil',
            'space' => '              b      ',
            'startsWith' => 'test case',
            'String' => 'ABCD',
            //'subdivisionCode' => 'subdivisionCode,JP',
            //'symbolicLink' =>'/path/to/link',
            'tld' => 'com',
            'True' => true,
            'type' => new stdClass(),
            //'uploaded' => 'path to file',
            'uppercase' => 'BRAZIL',
            'url' => 'http://www.google.com',
            'version' => '1.0.0',
            'video1' => 'https://youtu.be/l2gLWaGatFA',
            'vimeo' => 'http://vimeo.com/33677985',
            'vowel' => 'aeiou',
            'writable' => __FILE__,
            'xdigit' => 'abc123',
            'yes' => 'Y',
        ];
    }

    public function firstProvider()
    {
        return array_map(
            function ($data, $rule) {
                return [[$data], [$rule]];
            },
            $this->dataset(),
            $this->ruleset()
        );
    }

    /**
    *   @test
    *   @dataProvider firstProvider
    */
    public function first($inputs, $ruleset)
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(true, $validator->isValid());
    }

    /**
    *   @test
    */
    public function innerRule()
    {
        $this->markTestIncomplete();

        $container = new ServiceContainer();
        $container->delegate(new ServiceProviderContainer());
        $container->addServiceProvider(RespectValidationServiceProvider::class);

        $ruleset = [
            'allOf' => 'AllOf,IntVal,Between,30,40,Fibonacci',   //表現できない
        ];

        $inputs = [
            'allOf' => 34,
        ];

        $validator = new Validator(
            $container->get('validation.RuleResolver'),
            $inputs,
            $ruleset
        );

        $this->assertEquals(true, $validator->isValid());
    }
}
