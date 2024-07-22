<?php

namespace SyncMaster\PaymentGateway\Base;

interface RecurringSupport
{
    public function charge_customer_recurring(array $args);
    public function ipn_response_recurring(array $args = []);
}
