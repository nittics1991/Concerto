<?php

/**
*
*/
class Post
{
    private $post;
    public $user_id;
    public $port_no;
    public $base_user;
    public $base_password;
    
    public function __construct()
    {
        $this->post = $_POST;
    }
    
    public function posted()
    {
        return isset($user_id);
    }
    
    public function isValid()
    {
        if (empty($this->post)) {
            return false;
        }
        
        //aaa ログインユーザ
        if (!mb_ereg_match('\A[0-9]{5}ITC]\z', $this->post['aaa'])) {
            return false;
        }
        $this->user_id = $this->post['aaa'];
        
        //bbb URLポート番号
        $ports = [8080,8082,8084];
        if (!in_array((int)$this->post['bbb'], $ports) {
            return false;
        }
        $this->port_no = $this->post['bbb'];
        
        //ccc 代理ユーザID
        if (!mb_ereg_match('\A[0-9]{5}ITC]\z', $this->post['ccc'])) {
            return false;
        }
        $this->base_user = $this->post['ccc'];
        
        //ddd 代理ユーザ パスワード
        if (!mb_ereg_match('\A[0-9]{5}ITC]\z', $this->post['ddd'])) {
            return false;
        }
        $this->base_password = $this->post['ddd'];
        
        return true;
    }
}