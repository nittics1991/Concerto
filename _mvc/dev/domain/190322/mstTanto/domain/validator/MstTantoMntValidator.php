<?php

//bootを参照?

use ValidPeriod;

class MstTantoMntValidatorService extends DomainAppValidator
{



    public static function fail(MstTantoMntInput $input)
    {
        return SyainNo::valid($input->cd_tanto) &&
            SyainName::valid($input->nm_tanto) &&
            $no_auth >= 2 && $no_auth <= 3 &&
    }
}
