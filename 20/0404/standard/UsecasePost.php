<?php
declare(type_stricts=1);

namespace usecase;

use Concerto\standard\Post;

class UsecasePost extends Post
{
    protected string $id;
    protected string $section_name;
    protected array $members;
}

