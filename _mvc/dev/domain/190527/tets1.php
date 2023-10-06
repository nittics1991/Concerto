<?php

view
    htmlをrender / text化する
    html escape

viewModel
    今のControllerModel
    dateのformat
    Enumのformat
    numberのformat
    FiscalYearのformat

    formatする処理をset ?

    $viewModel->addFormatter($methodNane, callable $x);
    $viewModel内で
        $this->$methodName($val, $context);

        $this->dateFormat($yyyymm);




class ViewModel
{
    //addFormatterで追加したmethod //addHelperMethod()にするか?
    protected $helpers = [];

    public function __call($name, ...$args)
    {
        if (array_key_exists($name, $this->helpers) {
            return call_user_func_array($this->helpers[$name], $args);
        }
    }
}

    //injectionするcallable
    //型はcallableで自由度を高くする?
    //戻り値をinterfaceで定義できると良いような...
    dateFormat(DateTimeInterface $val, array $context = []) : string
    {
if (isset($context[0]) && is_string($context[0])) {
    return $val->format($context[0])
}

            //渡されたdata型で判別
elseif ($val instanceof DateObject) {
    return $val->format($this->define[DateObject::class]);
}

///////////////////////////////////////////

Validator

respectのruleのみ利用
拡張はRuleInterface
名前空間をfactoryに追加して解決 == > FileServiceProvider不要

処理の分類
    判定する役割のisIntなど
    結果を加工するand / everyなど
    requireは何に当たるか ?

処理について
    falseの結果をstack(Rule::classで記憶)
    メッセージはconfigで持つ

RuleFalseのException
    動的にRuleExceptionのclassが作れないか ?
        ==  > 無理
        なのでExeptionを拡張し、ruleClass | classNameを保持
        i18nでメッセージを出す処理で名前を見てメッセージをconfigから抽出
        ==  > messageBugみないなもの ?


factory
class Factory
{
    private $namespaces = []

    __construct(array $namespaces = [])

    addNamespace(string $namespace)

    create(Rule or Rule::class) : RuleInterface

        //namespacesに名前空間を追加
        //Ruleのある名前空間を探し
        //見つからなければthow
    foreach ($this->namespaces as $n) {
        try {
            return new() "{$name}\\{$rule}";
        }
    } catch (NotFoundException $e) {
        continue;
    } catch (Exception $e) {
        throw $e;
    }
}
            throw $e;    //NotFoundException
    }

ルール定義
    php処理っぽく書くか ?
    'name' => 'isInt*range(0,100)',
    でもカッコが入った場合わかりにくいか ?
    'name' => 'every((isInt*range(0,100)+(isBool*isNull)), isFloat*range(0,10))',
    Laravelっぽく ?
    'name' => 'every((isInt*range:0,100+(isBool*isNull)), isFloat*range:0,10)',
    演算子を関数にする
    'name' => 'every(AND(isInt,range(0,100))' ...難しい


パーサー
    range : 0,10
        => new RangeValidator(0, 1)
        $validator->validate($target);
