<?php

/**
*   定義方法
*       基準となる許可
*       'default' => 'allow|deny',
*       URLをPATH/queryで分割した文字列に対するマッチ条件
*       'allow|deny|null' =>
*           'kengen_sm|null' =>
*               'URL project名|null' =>
*                       'URL PATH/queryの正規表現|null'で再帰的に表現
*/

return [
    'default' => 'deny',
    'allow' => [
        '4' => '.+',
        
        '1' => [
            'my_page2' => [
                'my_page_disp.php' => 'cd_type=[8-9]',
            ],
        ],
        
        '2' => [
            'my_page2' => [
                'my_page_disp.php' => 'cd_type=[1-3,5,8-9]',
            'wf_new2' => [
                'wf_new_.+.php => ''    
            ],
                
                
            ],
        ],
        
        
        
        
    ],
    'deny' => [
    ],
];
