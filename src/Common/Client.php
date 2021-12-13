<?php


namespace Yoka\LianLianPay\Common;


use Yoka\LianLianPay\Core\AbstractAPI;

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

    public function notify(string $payload = null)
    {
        $payload = $payload ?: file_get_contents("php://input");
        $signatureData = $_SERVER['HTTP_SIGNATURE_DATA'];
        dd(json_decode($payload));
    }
}