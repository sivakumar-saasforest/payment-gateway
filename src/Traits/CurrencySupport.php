<?php

namespace SyncMaster\PaymentGateway\Traits;

use SyncMaster\PaymentGateway\Base\Gateways\CashFreePay;

trait CurrencySupport
{
    protected $currency;
    protected $exchange_rate;

    private function getCurrency()
    {
        return  $this->currency;
    }
    public function getExchangeRate()
    {
        return $this->exchange_rate;
    }
    public function setExchangeRate($rate)
    {
        $this->exchange_rate = $rate;
        return $this;
    }
    public function setCurrency($currency = "USD")
    {
        $this->currency = $currency;
        return $this;
    }

    private function is_decimal($n)
    {
        // Note that floor returns a float
        return is_numeric($n) && floor($n) != $n;
    }
}
