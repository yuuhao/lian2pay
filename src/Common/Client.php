<?php


namespace Yoka\LianLianPay\Common;


use Yoka\LianLianPay\Core\AbstractAPI;
use Yoka\LianLianPay\Support\Log;

class Client extends AbstractAPI
{

    /** 获取随机因子
     * @param string $userId
     * @param string $flagChnl
     * @param string|null $pkgName
     * @param string|null $appName
     * @param string|null $encryptAlgorithm
     * @return \Yoka\LianLianPay\Support\Collection|null
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function getRandom(string $userId, string $flagChnl, string $pkgName = null, string $appName = null, string $encryptAlgorithm = null)
    {
        $params = [
            'timestamp' => $this->timestamp,
            'oid_partner' => $this->config['oid_partner'],
            'user_id' => $userId,
            'flag_chnl' => $flagChnl,
            'pkg_name' => $pkgName,
            'app_name' => $appName,
            'encrypt_algorithm' => $encryptAlgorithm
        ];
        return $this->parse($this->url('acctmgr/get-random'), $params);
    }

    /**
     * 异步通知验签
     * @param string $signatureData 签名值
     * @param string|null $payload 包体
     * @return bool
     */
    public function verifyNotifySignature(string $signatureData, string $payload = null): bool
    {
        $payload = $payload ?: file_get_contents("php://input");

        $pubKey = $this->getConfig()->getInstantPayLianLianPublicKey();
        $res = openssl_get_publickey($pubKey);
        // 调用openssl内置方法验签，返回bool值
        $result = (bool) openssl_verify(md5($payload), base64_decode($signatureData), $res, OPENSSL_ALGO_MD5);

        Log::debug('Verify Signature Result:', compact('signatureData', 'payload'));

        // 释放资源
        openssl_free_key($res);
        return $result;
    }

    /**
     * 交易二次短信验证
     * https://open.lianlianpay.com/docs/accp/accpstandard/validation-sms.html
     * @param array $params
     * @return \Yoka\LianLianPay\Support\Collection|null
     * @throws \Yoka\LianLianPay\Exceptions\HttpException
     */
    public function validationSms(array $params)
    {
        return $this->parse($this->url('txn/validation-sms'), $params);
    }
}