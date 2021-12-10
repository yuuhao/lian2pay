<?php


namespace Yoka\LianLianPay\Account;


use Yoka\LianLianPay\Core\AbstractAPI;

class Client extends AbstractAPI
{

    /**
     * 绑定手机验证码申请
     * @param $userId
     * @param $regPhone
     * @param null $timestamp
     * @return \Yoka\LianLianPay\Support\Collection|null
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function phoneVerifyCodeApply($userId, $regPhone, $timestamp = null)
    {
        $params = [
            'timestamp' => $timestamp ?: $this->timestamp,
            'oid_partner' => $this->config['oid_partner'],
            'user_id' => $userId,
            'reg_phone' => $regPhone
        ];
        return $this->parse($this->url('acctmgr/regphone-verifycode-apply'), $params);
    }

    /**
     * 绑定手机验证码验证
     * @param $userId // 用户在商户系统中的唯一编号
     * @param $regPhone // 绑定手机号。用户开户注册绑定手机号
     * @param $verifyCode // 绑定手机号验证码 通过绑定手机验证码申请接口申请发送给用户绑定手机的验证码
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function phoneVerifyCodeVerify($userId, $regPhone, $verifyCode, $timestamp = null)
    {
        $params = [
            'timestamp' => $timestamp ?: $this->timestamp,
            'oid_partner' => $this->config['oid_partner'],
            'user_id' => $userId,
            'verify_code' => $verifyCode,
            'reg_phone' => $regPhone
        ];

        return $this->parse($this->url('acctmgr/regphone-verifycode-verify'), $params);
    }

    /**
     * 个人用户开户申请
     * @param $params
     * @return \Yoka\LianLianPay\Support\Collection|null
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function personOpenAcctApply($params)
    {
        $params['oid_partner'] = $params['oid_partner'] ?? $this->config['oid_partner'];

        return $this->parse($this->url('acctmgr/openacct-apply-individual'), $params);
    }

    /**
     * 个人用户开户验证
     */
    public function personOpenAcctVerify($params)
    {
        return $this->parse($this->url('acctmgr/openacct-verify-individual'), $params);
    }

    /**
     * 企业用户开户申请
     * @param $params
     * @return \Yoka\LianLianPay\Support\Collection|null
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function enterpriseOpenAcctApply($params)
    {
        return $this->parse($this->url('acctmgr/openacct-apply-enterprise'), $params);
    }

    /**
     * 企业用户开户验证
     * @param $params
     * @return \Yoka\LianLianPay\Support\Collection|null
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function enterpriseOpenAcctVerify($params)
    {
        return $this->parse($this->url('acctmgr/openacct-verify-enterprise'), $params);
    }
}