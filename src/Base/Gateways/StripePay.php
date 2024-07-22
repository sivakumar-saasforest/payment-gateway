<?php

namespace SyncMaster\PaymentGateway\Base\Gateways;

use  SyncMaster\PaymentGateway\Base\PaymentGatewayBase;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\StripeClient;
use Stripe\Checkout\Session;
use SyncMaster\PaymentGateway\Traits\CurrencySupport;
use SyncMaster\PaymentGateway\Traits\PaymentEnvironment;

class StripePay extends PaymentGatewayBase
{

    use PaymentEnvironment, CurrencySupport;

    protected $secret_key;
    protected $public_key;

    public function setSecretKey($secret_key)
    {
        $this->secret_key = $secret_key;

        return $this;
    }
    private function getSecretKey()
    {
        return $this->secret_key;
    }
    public function setPublicKey($public_key)
    {
        $this->public_key = $public_key;
        return $this;
    }
    private function getPublicKey()
    {
        return $this->public_key;
    }

    public function charge_amount($amount)
    {
        $return_amount = $amount;
        if (in_array($this->getCurrency(), $this->supported_currency_list(), true)) {
            if (in_array($this->getCurrency(), $this->zero_decimal_currencies())) {
                return $return_amount;
            }
            return $amount * 100;
        }
    }

    private function zero_decimal_currencies()
    {
        return [
            'BIF', 'CLP', 'DJF', 'GNF', 'JPY', 'KMF', 'KRW', 'MGA', 'PYG', 'RWF', 'UGX', 'VND', 'VUV', 'XAF', 'XOF', 'XPF'
        ];
    }

    /**
     *
     * @param array $args
     * required param list
     *
     * @return string[]
     * @throws \Stripe\Exception\ApiErrorException
     * @since 0.0.1
     */
    public function ipn_response(array $args = []): array
    {
        $stripe_session_id = session()->get('stripe_session_id');
        session()->forget('stripe_session_id');
        $stripe_order_id = session()->get('stripe_order_id');
        session()->forget('stripe_order_id');

        $stripe = new StripeClient($this->getSecretKey());
        $response = $stripe->checkout->sessions->retrieve($stripe_session_id, []);
        $payment_intent = $response['payment_intent'] ?? '';
        $payment_status = $response['payment_status'] ?? '';

        $capture = $stripe->paymentIntents->retrieve($payment_intent);
        if (!empty($payment_status) && $payment_status === 'paid' && $capture->status === 'succeeded') {
            $transaction_id = $payment_intent;
            if (!empty($transaction_id)) {
                return $this->verified_data([
                    'transaction_id' => $transaction_id,
                    'order_id' => $stripe_order_id
                ]);
            }
        }

        return ['status' => 'failed', 'order_id' => $stripe_order_id];
    }

    public function charge_customer(array $args)
    {
        return $this->stripe_view($args);
    }

    public function stripe_view($args)
    {
        return view('payment-gateway::stripe', ['stripe_data' => array_merge($args, [
            'public_key' => $this->getPublicKey(),
            'currency' => $this->getCurrency(),
            'secret_key' => base64_encode($this->getSecretKey()),
            'charge_amount' => ceil($this->charge_amount($args['amount'])),
        ])]);
    }

    public function charge_customer_from_controller(array $args)
    {
        Stripe::setApiKey(base64_decode($args['secret_key']));

        $payment_types = ['card'];

        if (strtolower($args['currency']) === "myr") {
            $payment_types[] = 'fpx';
        }

        $session = Session::create([
            'payment_method_types' => $payment_types,
            'line_items' => [[
                'price_data' => [
                    'currency' => $args['currency'],
                    'product_data' => [
                        'name' => $args['title'],
                        'description' => $args['description']
                    ],
                    'unit_amount' => $args['charge_amount'],
                ],
                'quantity' => 1
            ]],
            'mode' => 'payment',
            'success_url' => $args['ipn_url'],
            'cancel_url' => $args['cancel_url'],
        ]);

        session()->put('stripe_session_id', $session->id);
        session()->put('stripe_order_id', $args['order_id']);

        return ['id' => $session->id];
    }

    public function supported_currency_list(): array
    {
        return [
            'USD', 'EUR', 'INR', 'IDR', 'AUD', 'SGD', 'JPY', 'GBP', 'MYR', 'PHP', 'THB', 'KRW', 'NGN', 'GHS', 'BRL', 'BIF', 'CAD', 'CDF', 'CVE', 'GHP', 'GMD', 'GNF', 'KES', 'LRD', 'MWK', 'MZN', 'RWF', 'SLL', 'STD', 'TZS', 'UGX', 'XAF', 'XOF', 'ZMK', 'ZMW', 'ZWD', 'AED', 'AFN', 'ALL', 'AMD', 'ANG', 'AOA', 'ARS', 'AWG', 'AZN', 'BAM', 'BBD', 'BDT', 'BGN', 'BMD', 'BND', 'BOB', 'BSD', 'BWP', 'BZD', 'CHF', 'CNY', 'CLP', 'COP', 'CRC', 'CZK', 'DJF', 'DKK', 'DOP', 'DZD', 'EGP', 'ETB', 'FJD', 'FKP', 'GEL', 'GIP', 'GTQ', 'GYD', 'HKD', 'HNL', 'HRK', 'HTG', 'HUF', 'ILS', 'ISK', 'JMD', 'KGS', 'KHR', 'KMF', 'KYD', 'KZT', 'LAK', 'LBP', 'LKR', 'LSL', 'MAD', 'MDL', 'MGA', 'MKD', 'MMK', 'MNT', 'MOP', 'MRO', 'MUR', 'MVR', 'MXN', 'NAD', 'NIO', 'NOK', 'NPR', 'NZD', 'PAB', 'PEN', 'PGK', 'PKR', 'PLN', 'PYG', 'QAR', 'RON', 'RSD', 'RUB', 'SAR', 'SBD', 'SCR', 'SEK', 'SHP', 'SOS', 'SRD', 'SZL', 'TJS', 'TRY', 'TTD', 'TWD', 'UAH', 'UYU', 'UZS', 'VND', 'VUV', 'WST', 'XCD', 'XPF', 'YER', 'ZAR'
        ];
    }

    public function charge_currency()
    {
        return $this->getCurrency();
    }

    public function gateway_name(): string
    {
        return 'stripe';
    }

    public function create_product($planName, $amount, $interval)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $product = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $amount,
            'recurring' => ['interval' => $interval],
            'product_data' => ['name' => $planName],
        ]);

        return $product;
    }

    public function create_customer($data)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $customer = $stripe->customers->create($data);

        return $customer;
    }

    public function search_customer($email)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));

        $customer = $stripe->customers->search([
            'query' => 'email:' . $email,
        ]);

        return $customer;
    }

    public function subscription($user, $priceId, $successURL, $failedURL)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $session = \Stripe\Checkout\Session::create([
            'success_url' => $successURL,
            'cancel_url' => $failedURL,
            'mode' => 'subscription',
            'customer_email' => $user->email,
            'line_items' => [
                [
                    'price' => $priceId,
                    'quantity' => 1,
                ]
            ],
            'subscription_data' => [
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $priceId
                ]
            ]
        ]);

        return $session->url;
    }

    public function sessions_retrieve($sessionId)
    {
        $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
        $response = $stripe->checkout->sessions->retrieve($sessionId, []);

        return $response;
    }
}
