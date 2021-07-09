<?php

//getter setterどうする
//toArrayは欲しい

//EntityClass
class MstTantoModel implements EntityInterface
{
    use EntityIdTrait;

    private $syainNo;

    private $syainName;

    private $yomikana;

    private $biko;      //ValueObject

    private $touituUserId;

    private $mailAddress;   //EmailAddress or SyainEmailAddress (@glb.toshiba.cojp)

    private $entryDate; //Yyyymm

    private $leavingDate;   //Yyyymm

    private $useEmail;  //bool or Bool?

    private $authLevel; //AuthLevelEnum

    public function isSyozoku()
    {
        return (DatePointToPoint(
            $this->entryDate,
            $this->leavingDate
        ))->withIn(
            new DateTime('today'),
            true,
            false
        );
    }
}
