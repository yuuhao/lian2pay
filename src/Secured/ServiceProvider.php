<?php


namespace Yoka\LianLianPay\Secured;


use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['secured'] = function ($pimple) {
            return new Client($pimple['config']);
        };
    }
}