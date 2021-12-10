<?php


namespace Yoka\LianLianPay\Core;


use Yoka\LianLianPay\Support\Collection;

class Config extends Collection
{
    public function getInstantPayPrivateKey(): string
    {
        return <<<s
-----BEGIN RSA PRIVATE KEY-----
{$this->get('private_key')}
-----END RSA PRIVATE KEY-----
s;
    }

    public function getInstantPayPublicKey(): string
    {
        return <<<s
-----BEGIN PUBLIC KEY-----
{$this->get('public_key')}
-----END PUBLIC KEY-----
s;
    }

    public function getInstantPayLianLianPublicKey(): string
    {
        return <<<s
-----BEGIN PUBLIC KEY-----
{$this->get('ll_public_key')}
-----END PUBLIC KEY-----
s;
    }
}