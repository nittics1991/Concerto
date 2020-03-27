<?php

/**
*   データベース認証
*
*   @version 190731
*/

namespace Concerto\auth;

use DateTime;
use DateTimeImmutable;
use Concerto\auth\AuthConst;
use Concerto\auth\AuthDBBase;
use Concerto\auth\AuthDbFactoryInterface;
use Concerto\auth\AuthInterface;
use Concerto\Validate;

class AuthDB extends AuthDBBase implements AuthInterface
{
    /**
    *   ログイン
    *
    *   @param string id
    *   @param string パスワード
    *   @return integer
    */
    public function login($user = null, $password = null)
    {
        if (!empty($this->session->user)) {
            return AuthConst::AUTHENTICATED;
        }
        
        if (empty($user) || empty($password)) {
            return AuthConst::DATAEMPTY;
        }
        
        if (!mb_ereg_match('^[0-9]{5}ITC$', $user)) {
            return AuthConst::FAILURE;
        }
        
        $ans = AuthConst::FAILURE;
        $this->mstTantoData->cd_tanto = $user;
        
        if ($this->mstTantoData->isValid() && Validate::isAscii($password, 1)) {
            $result = $this->mstTanto->select($this->mstTantoData);
            $data = $result[0] ?? null;
            
            if (
                !empty($data->cd_tanto)
                && password_verify($password, $data->cd_hash)
                && $this->confirmPasswordExpiration(new DateTimeImmutable($data->dt_hash))
                && (trim($data->kb_group) != "")
                && (trim($data->mail_add) != "")
            ) {
                $this->session->changeID();
                $this->session->user = $data->cd_tanto;
                $this->setLoginLog($data->cd_tanto, $data->nm_tanto);
                // $this->session->user = $data->cd_tanto;
                $ans =  AuthConst::SUCCESS;
            }
        }
        return $ans;
    }
}
