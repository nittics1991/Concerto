<?php
declare(type_stricts=1);

namespace usecase;

use Concerto\standard\Post;

class UsecasePost extends Post
{
    protected string $id;
    protected string $section_name;
    protected array $members;
    
    //POSTに　ArrayAccessTrait 追加
    //QUERYの場合　さらに 初期値　追加設定method追加
    
    
}

