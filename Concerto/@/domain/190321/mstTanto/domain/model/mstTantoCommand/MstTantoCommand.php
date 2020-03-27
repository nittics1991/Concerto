<?php


mutatorはdomain objectで行うので不要


abstract class ModelBase
{
    protected $containers = [];
    
    protected $properties = [];
    
    protected $writables = [];
    
    protected $inited = false;
    
    public function __construct(array $dataset)
    {
        $this->fromArray($dataset);
        $this->inited = true;
    }
    
    public function fromArray(array $dataset)
    {
        foreach ($dataset as $key => $val) {
            if (!array_key_exists($key, $this->properties)) {
                continue;
            }
            
            if ($this->inited &&
                !in_array($key, $this->writables)
            ) {
                continue;
            }
            
            if (class_exists($this->properties[$key])) {
                $this->containers[$key] =
                    new $this->properties[$key]($val);
                continue;
            }
            
            $this->containers[$key] =
                settype($val, $this->properties[$key]);
        }
    }
    
    public function toArray()
    {
    }
    
    public function __call(string $name, array $args)
    {
        $methodName = ucfirst($name);
        
        if (array_key_exists($methodName, $this->properties) {
            return $this->containers[$methodName]?? null;
        }
        
        throw new BadMethodCallException(
            "not defined method:{$name}"
        );
    }
}









//fromArray,toArrayを持つabstractClassの継承?
class MstTantoCommand //extends RootDomainObject //implemetns Domainable
{
    
    //各properyのDomian Objectを作る?
    
    //Syain entityにまとめる?
    private $syainId;   //SyainId
    
    private $syainName; //SyainName
    
    
    //Syainの一部?
    
    private $yomikana;  //ValueObject? Yomikana::class?
    
    private $touituUserId;
    
    
    //連絡先?  commonではない?
    
    private $mailAddress;   //EmailAddress or SyainMailAddress(VO)
    
    
    //DB上Syainに含むが・・・
    
    private $syozokuKikan;  //SyozokuKikan(UseCase or VO)
    
    
    //Syain has
    
    private $genkaBumonCode;    //GenkaBumonCode
    
    
    //ユーザ設定の集合? or 列挙? ==>集合しても何かするわけではない?
    
    private $mstTantoSetting
    
    
    //認可レベル
    //Syain has ==>汎化できないか?
    
    private $authLevel;
}
