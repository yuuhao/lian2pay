<?php


namespace Yoka\LianLianPay\Account;


use Yoka\LianLianPay\Core\AbstractAPI;
use Yoka\LianLianPay\Exceptions\HttpException;
use Yoka\LianLianPay\Support\Collection;

class Client extends AbstractAPI
{

    /**
     * 绑定手机验证码申请
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/regphone-verifycode-apply.html
     * @param $userId
     * @param $regPhone
     * @param null $timestamp
     * @return Collection|null
     * @throws HttpException
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
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/regphone-verifycode-verify.html
     * @param $userId // 用户在商户系统中的唯一编号
     * @param $regPhone // 绑定手机号。用户开户注册绑定手机号
     * @param $verifyCode // 绑定手机号验证码 通过绑定手机验证码申请接口申请发送给用户绑定手机的验证码
     * @throws HttpException
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
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/openacct-apply-individual.html
     * @param $params
     * @return Collection|null
     * @throws HttpException
     */
    public function personOpenAcctApply($params)
    {
        $params['oid_partner'] = $params['oid_partner'] ?? $this->config['oid_partner'];

        return $this->parse($this->url('acctmgr/openacct-apply-individual'), $params);
    }

    /**
     * 个人用户开户验证
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/openacct-verify-individual.html
     */
    public function personOpenAcctVerify($params)
    {
        return $this->parse($this->url('acctmgr/openacct-verify-individual'), $params);
    }

    /**
     * 企业用户开户申请
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/openacct-apply-enterprise.html
     * @param $params
     * @return Collection|null
     * @throws HttpException
     */
    public function enterpriseOpenAcctApply($params)
    {
        return $this->parse($this->url('acctmgr/openacct-apply-enterprise'), $params);
    }

    /**
     * 企业用户开户验证
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/openacct-verify-enterprise.html
     * @param $params
     * @return Collection|null
     * @throws HttpException
     */
    public function enterpriseOpenAcctVerify($params)
    {
        return $this->parse($this->url('acctmgr/openacct-verify-enterprise'), $params);
    }

    /**
     * 文件上传
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/upload.html
     * @param $params
     * @return Collection|null
     * @throws HttpException
     */
    public function upload($params)
    {
        $production = $this->getConfig()->get('production');
        if ($production) {
            $url = 'https://accpfile.lianlianpay.com/v1/documents/upload';
        } else {
            $url = 'https://accpfile-ste.lianlianpay-inc.com/v1/documents/upload';
        }
        return $this->parse($url, $params);
    }

    /**
     * 上传照片
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/upload-photos.html
     * @param $params
     * @return Collection|null
     * @throws HttpException
     */
    public function uploadPhotos($params)
    {
        return $this->parse($this->url('acctmgr/upload-photos'), $params);
    }

    /**
     * 用户开户申请(页面接入)
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/openacct-apply.html
     * @param array $params
     * @return Collection|null
     * @throws HttpException
     */
    public function openAcctApply(array $params)
    {
        return $this->parse($this->url('acctmgr/openacct-apply'), $params);
    }

    /**
     * 个人用户信息修改
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/modify-userinfo-individual.html
     * @param array $params
     * @return Collection|null
     * @throws HttpException
     */
    public function modifyPersonUserInfo(array $params)
    {
        return $this->parse($this->url('acctmgr/modify-userinfo-individual'), $params);
    }

    /**
     * 企业用户信息修改
     * @doc https://open.lianlianpay.com/docs/accp/accpstandard/modify-userinfo-enterprise.html
     * @param array $params
     * @return Collection|null
     * @throws HttpException
     */
    public function modifyEnterpriseUserInfo(array $params)
    {
        return $this->parse($this->url('acctmgr/modify-userinfo-enterprise'), $params);
    }
}