
TrowableValidator

validateで失敗はValidateExceptionをthrowする

validatorは ValidateException を catch　する

validator　の設定が exception なら　そのままthrow
    bool なら return false

ユーザにメッセージを構築する場合,traceを使う
    ==>Trace　classが必要 extendsして ValidatorMessege classとする

実際に判定する validator validate($val)を持つ ==> interface　作る

validator service　は　複数の判定をまとめて実行
validator は 汎用と専用の両方があり得る

    [
        name1 => [
            validator11,
            validator12,
        ],
        name1 => [
            validator11,
            validator22,
            validator23,
        ],
        
    
    ]
