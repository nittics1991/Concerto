<?php

/**
*
*/
class User
{
    private $dns = 'pgsql:host=localhost;port=5430;dbname=itc_work;user=concerto;password=manager';
    
    private $post;
    private $pdo;
    
    public function __construct(POST $post)
    {
        $this->post = $post;
        
        $this->pdo = new PDO($this->dns);
        
        $this->pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        
        $this->pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
    }
    
    public function login():bool
    {
        $user_data = $this->findUser($this->post->user_id);
        
        if ($user_data === false) {
            return false;
        }
        
        if (!password_verify(
            $this->post->user_password,
            $user_data['hash_pass']
        ) {
            return false;
        }
        
        if ($user_data['kengen_db'] != '1') {
            return false;
        }
        
        return true;
    }
    
    public function proxy():bool
    {
        $user_data = $this->findUser($this->post->user_id);
        
        if ($user_data === false) {
            return false;
        }
        
        $_SESSION['input_code'] = $user_data['cd_tanto'];
        $_SESSION['input_name'] = $user_data['nm_tanto'];
        
        return true;
    }
    
    private function findUser(string $user_id)
    {
        $sql = "
            SELECT *
            FROM public.mst_tanto
            WHERE cd_tanto = :tanto
        ";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tanto', $user_id);
        $stmt->execute();
        $result = (array)$stmt->fetch();
        
        if (count($result) != 1) {
            return false;
        }
        return $result
    }
}