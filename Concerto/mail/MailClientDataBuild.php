<?php

/**
*   Mail Client
*
*   @version 200722
*/

declare(strict_types=1);

namespace Concerto\mail;

use Concerto\database\MailInfData;
use Concerto\mail\MailClientDataBuildFactoryInterface;
use Concerto\standard\Session;

class MailClientDataBuild
{
    /**
    *   factory
    *
    *   @var MailClientDataBuildFactoryInterface
    */
    protected $factory;

    /**
    *   configデータ
    *
    *   @var mixed[]
    */
    protected $config;

    /**
    *   template用データ
    *
    *   @var ?array
    */
    protected $variable;

    /**
    *   基準メール検索条件
    *
    *   @var MailInfData
    */
    protected $mail_condition;

    /**
    *   メッセージ本文最大長
    *
    *   @var int
    */
    protected $message_max_length;

    /**
    *   基準メールデータ
    *
    *   @var MailInfData
    */
    protected $mail_data;

    /**
    *   全メールデータ
    *
    *   @var MailInfData[]
    */
    protected $mail_data_all;

    /**
    *   引継ぎデータ
    *
    *   @var Session
    */
    protected $takeover_data;

    /**
    *   token
    *
    *   @var string
    */
    protected $token;

    /**
    *   __construct
    *
    *   @param MailClientDataBuildFactoryInterface $factory
    *   @param mixed[]|null $config configメッセージ定義
    *   @param mixed[]|null $variable template用データ
    *   @param MailInfData|null $mail_condition メールID
    *   @param int $message_max_length メッセージ本文最大長
    */
    public function __construct(
        MailClientDataBuildFactoryInterface $factory,
        array $config = null,
        array $variable = null,
        MailInfData $mail_condition = null,
        $message_max_length = 1000
    ) {
        $this->factory = $factory;
        $this->config = $config;
        $this->variable = $variable;
        $this->mail_condition = $mail_condition;
        $this->message_max_length = $message_max_length;

        $this->setConfig();
        $this->setId();
        $this->getMailInfData();
        $this->setAddress();
        $this->setCcAddAddress();
        $this->setSubject();
        $this->setMessage();
        $this->setAfter();

        $session = $this->factory->getSession('mailClient');
        $session->unsetAll();
        $this->token = $property = uniqid();
        $session->$property = $this->takeover_data;
    }

    /**
    *   config設定
    *
    */
    protected function setConfig()
    {
        $this->takeover_data['config'] = $this->config;
    }

    /**
    *   ID(mailCondition)設定
    *
    */
    protected function setId()
    {
        $this->takeover_data['id'] = serialize($this->mail_condition);
    }

    /**
    *   ID設定及び基準メール取得
    *
    */
    protected function getMailInfData()
    {
        if ($this->config['type'] == 'new') {
            return;
        }
        $mailInf = $this->factory->getMailInf();
        $result = $mailInf->select($this->mail_condition, 'ins_date');

        if (count($result) == 0) {
            return;
        }

        $this->mail_data_all = $result;

        if ($this->config['type'] == 'top') {
            $this->mail_data = $result[0];
            return;
        }
        $this->mail_data = $result[count($result) - 1];
    }

    /**
    *   アドレス設定
    *
    */
    protected function setAddress()
    {
        if (empty($this->mail_data)) {
            return;
        }

        switch ($this->config['type']) {
            case 'return':
                $first_mail_data = $this->mail_data_all[0];

                $this->takeover_data['to'] = $first_mail_data->from_adr;
                $this->takeover_data['cc'] =
                    "{$this->mail_data->cc_adr};{$this->mail_data->to_adr}";
                break;
            case 'forward':
                $this->takeover_data['cc'] =
                    "{$this->mail_data->cc_adr};{$this->mail_data->to_adr};"
                    . $this->mail_data->from_adr;
                break;
        }
    }

    /**
    *   CC追加アドレス設定
    *
    */
    protected function setCcAddAddress()
    {
        if (!is_numeric($this->config['mail_cc'])) {
            return;
        }

        $adr = '';

        if (!empty($this->mail_data)) {
            $adr .= ";{$this->mail_data->from_adr}";
        }

        $mailCcInfData = $this->factory->getMailCcInfData();
        $mailCcInfData->cd_type = $this->config['mail_cc'];

        $globalSession = $this->factory->getSession();

        $mailCcInfData->cd_system = $globalSession->cd_system;

        $mailCcInf = $this->factory->getMailCcInf();
        $result = $mailCcInf->getUserList($mailCcInfData);


        foreach ((array)$result as $list) {
            $adr .= ";{$list['mail_add']}";
        }
        $this->takeover_data['add'] = mb_substr($adr, 1);
    }

    /**
    *   subject設定
    *
    */
    protected function setSubject()
    {
        if (
            empty($this->config['subject'])
            || !file_exists($this->config['subject'])
        ) {
            return;
        }
        $view = $this->factory->getViewStandard();

        if (is_array($this->variable)) {
            $view->fromArray($this->variable);
        }
        $this->takeover_data['subject'] =
            $view->cache($this->config['subject']);

        if (
            !empty($this->mail_data->nm_title)
            && ($this->config['type'] == 'return'
            || $this->config['type'] == 'forward'
            )
        ) {
            $this->takeover_data['subject'] .=
                "({$this->mail_data->nm_title})";
        }
    }

    /**
    *   message設定
    *
    */
    protected function setMessage()
    {
        if (
            empty($this->config['message'])
            || !file_exists($this->config['message'])
        ) {
            return;
        }
        $view = $this->factory->getViewStandard();

        if (is_array($this->variable)) {
            $view->fromArray($this->variable);
        }
        $this->takeover_data['message'] =
            $view->cache($this->config['message']);

        if (
            !empty($this->mail_data->nm_comment)
            && ($this->config['type'] == 'return'
            || $this->config['type'] == 'forward'
            )
        ) {
            $this->takeover_data['message'] .=
                "\n\n----------以下前回本文一部引用----------\n";

            $this->takeover_data['message'] .=
                "{$this->mail_data->nm_comment}";

            $this->takeover_data['message'] = mb_substr(
                $this->takeover_data['message'],
                0,
                $this->message_max_length
            );
        }
    }

    /**
    *   after設定
    *
    */
    protected function setAfter(): void
    {
        if (
            !empty($this->config['after'])
            && file_exists($this->config['after'])
        ) {
            $this->takeover_data['after'] = $this->config['after'];
        }
    }

    /**
    *   token取得
    *
    *   @return string
    */
    public function getToken()
    {
        return $this->token;
    }
}
