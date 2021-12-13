<?php

namespace Yoka\LianLianPay\Payment;

use Yoka\LianLianPay\Core\AbstractAPI;
use Yoka\LianLianPay\Exceptions\HttpException;
use Yoka\LianLianPay\Exceptions\InvalidArgumentException;
use Yoka\LianLianPay\Support\Arr;
use Yoka\LianLianPay\Support\Collection;
use Yoka\LianLianPay\Support\Log;
use Yoka\LianLianPay\Core\LLHelper;

class Client extends AbstractAPI
{
    const FLAG_CARD_PERSON = '0';
    const FLAG_CARD_ORGANIZE = '1';

    protected $baseUrl = 'https://instantpay.lianlianpay.com/';

    /**
     * 生产有效的商户订单号(最好排重)
     * @return string
     */
    public static function findAvailableNoOrder(): string
    {
        return date('YmdHis') . substr(explode(' ', microtime())[0], 2, 6) . rand(1000, 9999);
    }


    /**
     * 发起一笔付款申请
     *
     * @param string $moneyOrder 付款金额保留小数点后2位,单位元
     * @param string $cardNo 收款方银行账号
     * @param string $acctName 收款方姓名
     * @param string $infoOrder 订单描述。说明付款用途，5W以上必传。
     * @param string $memo 收款备注。 传递至银行， 一般作为订单摘要展示。
     * @param string $noOrder 商户订单号。
     * @param string $riskItem 风险控制参数。
     * @param string $notifyUrl 接收异步通知的线上地址。
     * @param string $flagCard 对公对私标志。
     * @param string $bankName 收款银行名称。
     * @param string $prcptcd 大额行号。 可调用大额行号查询接口进行查询。
     * @param string $bankCode 银行编码。 flag_card为1时， 建议选择大额行号+银行编码或开户支行名称+开户行所在市编码+银行编码中的一组传入。
     * @param string $cityCode 开户行所在省市编码， 标准地市编码。
     * @param string $braBankName 开户支行名称
     * @return Collection|null
     * @throws HttpException
     */
    public function payment($moneyOrder, $cardNo, $acctName, $infoOrder, $memo, $noOrder = null, $riskItem = null,
                            $notifyUrl = null, $flagCard = self::FLAG_CARD_PERSON, $bankName = null, $prcptcd = null,
                            $bankCode = null, $cityCode = null, $braBankName = null)
    {
        $params = [
            "oid_partner" => $this->config['oid_partner'],
            "platform" => $this->config['platform'],
            "api_version" => $this->production ? '1.1' : '1.0',
            "sign_type" => self::SIGN_TYPE_RSA,
            "no_order" => $noOrder,
            "dt_order" => date('YmdHis'),
            "money_order" => $moneyOrder,
            "card_no" => $cardNo,
            "acct_name" => $acctName,
            "info_order" => $infoOrder,
            "flag_card" => $flagCard,
            "memo" => $memo,
            "notify_url" => $notifyUrl ?: $this->config['notify_url'],
            "risk_item" => $this->production ? $riskItem : null,
            // 以下是对公打款可选参数
            "bank_name" => $bankName,
            "prcptcd" => $prcptcd,
            "bank_code" => $bankCode,
            "city_code" => $cityCode,
            "brabank_name" => $braBankName,
        ];

        return $this->payload($this->url('paymentapi/payment.htm'), $params);
    }

    /**
     * 确认付款 (疑似重复订单需要确认付款)
     *
     * @param $noOrder
     * @param $confirmCode
     * @param null $notifyUrl
     * @return Collection|null
     * @throws HttpException
     */
    public function confirmPayment($noOrder, $confirmCode, $notifyUrl = null)
    {
        $params = [
            "oid_partner" => $this->config['oid_partner'],
            "platform" => $this->config['platform'],
            "api_version" => '1.0',
            "sign_type" => self::SIGN_TYPE_RSA,
            "no_order" => $noOrder,
            "confirm_code" => $confirmCode,
            "notify_url" => $notifyUrl ?: $this->config['notify_url'],
        ];

        return $this->payload($this->url('paymentapi/confirmPayment.htm'), $params);
    }

    /**
     * @param null $noOrder
     * @param null $oidPayBill
     * @return Collection|null
     * @throws InvalidArgumentException|HttpException
     */
    public function queryPayment($noOrder = null, $oidPayBill = null)
    {
        if (empty($noOrder) && empty($oidPayBill)) {
            throw new InvalidArgumentException('noOrder 和 oidPayBill 不能都为空');
        }

        $params = [
            "oid_partner" => $this->config['oid_partner'],
            "sign_type" => self::SIGN_TYPE_RSA,
            "no_order" => $noOrder,
            "platform" => $this->config['platform'],
            "oid_paybill" => $oidPayBill,
            "api_version" => '1.0',
        ];

        return $this->payload($this->url('paymentapi/queryPayment.htm'), $params);
    }
}