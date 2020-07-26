<?php

/**
*   MandatoryPortalGate
*
*   @version 200725
*/

namespace Concerto\gate\rules;

use InvalidArgumentException;
use Concerto\conf\Config;
use Concerto\gate\GateInterface;

class MandatoryPortalGate implements GateInterface
{
    /**
    *   config
    *
    *   @var Config
    */
    private Config $config;
    
    /**
    *   __construct
    *
    *   @param Config $config
    */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    
    
    
    
    //$idを使わないがどうする?
    /**
    *   {inherit}
    *
    */
    public function allowed($id, ...$context):bool
    {
        if (!$this->isValidArguments($context)) {
            throw new InvalidArgumentException(
                "argument must be AuthUser, url"
            );
        }
        
        $execution_order = ($this->config['default'] == 'allow')?
            ['allow', 'deny',]:
            ['deny', 'allow',];
        
        $authority = ($context[0])->kengenMac;
        $splited_url = $this->splitUrl($context[1]);
        
        return array_redule(
            $execution_order,
            function($carry, $rule){
                return $carry
                    && call_user_func_array(
                        [$this, "{$rule}Judge"],
                        [$splited_url, $authority,]
                    );
            },
            true
        );
    }
    
    /**
    *   {inherit}
    *
    */
    public function denied($id, ...$context):bool
    {
        return !$this->allowed($id, $context);
    }
    
    /**
    *   isValidArguments
    *
    *   @param array $arguments
    *   @return bool
    */
    private function isValidArguments(array $arguments):bool
    {
        return isset($arguments)
            && is_array($arguments)
            && count($arguments) === 2
            && $arguments[0] instanceof AuthUser
            && is_string($arguments[1])
            && mb_strlen($arguments[1]) > 0
            ;
    }
    
    
    /**
    *   splitUrl
    *
    *   @param string $url
    *   @return array
    */
    private function splitUrl(string $url):array
    {
        $splited_by_path = mb_split('/', $url);
        //システム名除外
        array_shift($splited_by_path);
        //query取出し
        $query = array_pop($splited_by_path);
        
        $splited_by_query = mb_split(
            '&',
            mb_substr($query, 1)
        );
        
        return array_merge($splited_by_path, $splited_by_query);
    }
    
    /**
    *   許可判定
    *
    *   @param array $splited_url
    *   @param string $authority
    *   @return bool
    */
    private function allowJudge(
        array $splited_url,
        string $authority
    ):bool{
        return $this->doJudge(
            'allowed',
            $splited_url,
            $authority
        );
    }
    
    /**
    *   禁止判定
    *
    *   @param array $splited_url
    *   @param string $authority
    *   @return bool
    */
    private function denyJudge(
        array $splited_url,
        string $authority
    ):bool{
        return !$this->doJudge(
            'denied',
            $splited_url,
            $authority
        );
    }
    
    /**
    *   判定実行
    *
    *   @param string $rule_type
    *   @param array $splited_url
    *   @param string $authority
    *   @return bool
    */
    private function doJudge(
        string $rule_type,
        array $splited_url,
        string $authority
    ):bool{
    ):bool {
        $all_rules = $this->config[$rule_type];
        
        if (!is_array($all_rules)) {
            throw new RuntimeException(
                "invalid rule"
            );
        }
        
        
        
        
        
        
        
    }
    
    /**
    *   再帰
    *
    *   @param array $arguments
    *   @return bool
    */
    private function doJudge()
    {
        
        
    }
    
    
    
    
    
    
}
