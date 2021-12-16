<?php

namespace Yoka\LianLianPay\Payment;

use Yoka\LianLianPay\Core\AbstractAPI;
use Yoka\LianLianPay\Exceptions\HttpException;
use Yoka\LianLianPay\Support\Collection;

class Client extends AbstractAPI
{
    /**
     * 支付统一创单
     * 商户在充值/消费交易模式场景下使用，先通过该接口完成支付统一创单，后续根据业务场景调用不同的支付接口完成付款。
     * https://open.lianlianpay.com/docs/accp/accpstandard/accp-tradecreate.html
     * @param array $params
     * @return Collection|null
     * @throws HttpException
     */
    public function create(array $params)
    {
        return $this->parse($this->url("txn/tradecreate"), $params);
    }

    /**
     * 网关类支付
     * 微信、支付宝、网银类支付申请，通过该接口唤起相应渠道的支付界面，用户确认并支付，支持附加优惠券、余额组合支付。
     * https://open.lianlianpay.com/docs/accp/accpstandard/payment-gw.html
     * @param array $params
     * @return Collection|null
     * @throws HttpException
     */
    public function gateway(array $params)
    {
        return $this->parse($this->url("txn/payment-gw"), $params);
    }

    /**
     * 银行卡快捷支付
     * 银行卡快捷支付接口，支持附加优惠券、余额组合支付；适用于如下几种场景：已开户绑定银行卡的个人用户支付、未注册的匿名用户首次/历次支付
     * https://open.lianlianpay.com/docs/accp/accpstandard/payment-gw.html
     * @param array $params
     * @return Collection|null
     * @throws HttpException
     */
    public function bankCard(array $params)
    {
        return $this->parse($this->url("txn/payment-bankcard"), $params);
    }

    /**
     * 支付单关单
     * 该接口提供对支付处理中的支付单进行关单处理。需要调用关单接口的情况：当用户支付失败了，需要重新发起支付，此时可调用关单接口关闭之前的支付单后再发起新的支付单支付。主要用于商户侧控制用户重复支付的问题。
     * https://open.lianlianpay.com/docs/accp/accpstandard/close-payment.html
     * @param null $accpTxno 可选 ACCP系统交易单号。二选一，建议优先使用ACCP系统交易单号。
     * @param null $txnSeqno 可选 商户系统唯一交易流水号。二选一，建议优先使用ACCP系统交易单号。
     * @return Collection|null
     * @throws HttpException
     */
    public function close($accpTxno = null, $txnSeqno = null)
    {
        $params = [
            'accp_txno' => $accpTxno,
            'txn_seqno' => $txnSeqno,
        ];

        return $this->parse($this->url("txn/close-payment"), $params);
    }

    /**
     * 支付结果查询
     * 该接口提供所有消费/充值场景下的支付订单查询，商户可以通过该接口主动查询订单状态，完成下一步的业务逻辑。 需要调用查询接口的情况：
     * 商户后台、网络、服务器等出现异常，商户最终未接收到支付结果通知；
     * 调用支付接口后，返回系统错误或者未知交易、处理中交易状态情况。
     * https://open.lianlianpay.com/docs/accp/accpstandard/query-payment.html
     * @param null $accpTxno 可选 ACCP系统交易单号。【三选一】，建议优先使用ACCP系统交易单号。
     * @param null $txnSeqno 可选 商户系统唯一交易流水号。【三选一】，建议优先使用ACCP系统交易单号。
     * @param null $subChnlNO 可选 上游渠道流水号。【三选一】，建议优先使用 ACCP 系统交易单号 。
     * @return Collection|null
     * @throws HttpException
     */
    public function query($accpTxno = null, $txnSeqno = null, $subChnlNO = null)
    {
        $params = [
            'accp_txno' => $accpTxno,
            'txn_seqno' => $txnSeqno,
            'sub_chnl_no' => $subChnlNO,
        ];

        return $this->parse($this->url("txn/close-payment"), $params);
    }
}