<?php

namespace BookPanda\PaymentGateway\Base;

use BookPanda\PaymentGateway\Base\Gateways\AuthorizeDotNetPay;
use BookPanda\PaymentGateway\Base\Gateways\BillPlzPay;
use BookPanda\PaymentGateway\Base\Gateways\CashFreePay;
use BookPanda\PaymentGateway\Base\Gateways\CinetPay;
use BookPanda\PaymentGateway\Base\Gateways\FlutterwavePay;
use BookPanda\PaymentGateway\Base\Gateways\InstamojoPay;
use BookPanda\PaymentGateway\Base\Gateways\Iyzipay;
use BookPanda\PaymentGateway\Base\Gateways\KineticPay;
use BookPanda\PaymentGateway\Base\Gateways\MidtransPay;
use BookPanda\PaymentGateway\Base\Gateways\MolliePay;
use BookPanda\PaymentGateway\Base\Gateways\PagaliPay;
use BookPanda\PaymentGateway\Base\Gateways\PayFastPay;
use BookPanda\PaymentGateway\Base\Gateways\PaymobPay;
use BookPanda\PaymentGateway\Base\Gateways\PaypalPay;
use BookPanda\PaymentGateway\Base\Gateways\PaystackPay;
use BookPanda\PaymentGateway\Base\Gateways\PayTabsPay;
use BookPanda\PaymentGateway\Base\Gateways\PaytmPay;
use BookPanda\PaymentGateway\Base\Gateways\PayUmoneyPay;
use BookPanda\PaymentGateway\Base\Gateways\RazorPay;
use BookPanda\PaymentGateway\Base\Gateways\SaltPay;
use BookPanda\PaymentGateway\Base\Gateways\Senangpay;
use BookPanda\PaymentGateway\Base\Gateways\SitesWayPay;
use BookPanda\PaymentGateway\Base\Gateways\SquarePay;
use BookPanda\PaymentGateway\Base\Gateways\StripePay;
use BookPanda\PaymentGateway\Base\Gateways\MercadoPagoPay;
use BookPanda\PaymentGateway\Base\Gateways\Toyyibpay;
use BookPanda\PaymentGateway\Base\Gateways\TransactionCloudPay;
use BookPanda\PaymentGateway\Base\Gateways\WiPay;
use BookPanda\PaymentGateway\Base\Gateways\ZitoPay;

/**
 * @see SquarePay
 * @method  setApplicationId();
 * @method  setAccessToken();
 * @method  setLocationId();
 */

class PaymentGatewayHelpers
{

    public function stripe(): StripePay
    {
        return new StripePay();
    }
    public function paypal(): PaypalPay
    {
        return new PaypalPay();
    }
    public function midtrans(): MidtransPay
    {
        return new MidtransPay();
    }
    public function paytm(): PaytmPay
    {
        return new PaytmPay();
    }
    public function razorpay(): RazorPay
    {
        return new RazorPay();
    }
    public function mollie(): MolliePay
    {
        return new MolliePay();
    }
    public function flutterwave(): FlutterwavePay
    {
        return new FlutterwavePay();
    }
    public function paystack(): PaystackPay
    {
        return new PaystackPay();
    }

    public function payfast(): PayFastPay
    {
        return new PayFastPay();
    }
    public function cashfree(): CashFreePay
    {
        return new CashFreePay();
    }
    public function instamojo(): InstamojoPay
    {
        return new InstamojoPay();
    }
    // deprecated
    public function mercadopago(): MercadoPagoPay
    {
        return new MercadoPagoPay();
    }
    public function payumoney(): PayUmoneyPay
    {
        return new PayUmoneyPay();
    }
    public function squareup(): SquarePay
    {
        return new SquarePay();
    }
    public function cinetpay(): CinetPay
    {
        return new CinetPay();
    }
    public function paytabs(): PayTabsPay
    {
        return new PayTabsPay();
    }
    public function billplz(): BillPlzPay
    {
        return new BillPlzPay();
    }

    public function zitopay(): ZitoPay
    {
        return new ZitoPay();
    }
    public function toyyibpay(): Toyyibpay
    {
        return new Toyyibpay();
    }
    public function pagalipay(): PagaliPay
    {
        return new PagaliPay();
    }
    public function authorizenet(): AuthorizeDotNetPay
    {
        return new AuthorizeDotNetPay();
    }
    public function sitesway(): SitesWayPay
    {
        return new SitesWayPay();
    }
    public function wipay(): WiPay
    {
        return new WiPay();
    }
    public function kineticpay(): KineticPay
    {
        return new KineticPay();
    }
    public function transactionclud(): TransactionCloudPay
    {
        return new TransactionCloudPay();
    }

    public function senangpay(): Senangpay
    {
        return new Senangpay();
    }
    public function saltpay(): SaltPay
    {
        return new SaltPay();
    }

    public function paymob(): PaymobPay
    {
        return new PaymobPay();
    }

    public function iyzipay(): Iyzipay
    {
        return new Iyzipay();
    }

    public function all_payment_gateway_list(): array
    {
        return [
            'zitopay', 'billplz', 'paytabs', 'cinetpay', 'squareup',
            'mercadopago', 'instamojo', 'cashfree', 'payfast',
            'paystack', 'flutterwave', 'mollie', 'razopay', 'paytm',
            'midtrans', 'paypal', 'stripe', 'toyyibpay', 'pagali', 'authorizenet',
            'sitesway', 'transactionclud', 'wipay', 'kineticpay', 'senangpay', 'saltpay', 'paymob',
            'iyzipay'
            //            'payumoney',
        ];
    }
    public function script_currency_list()
    {
        return GlobalCurrency::script_currency_list();
    }

    public static function wrapped_id($id): string
    {
        return random_int(11111, 99999) . $id . random_int(11111, 99999);
    }
    public static function unwrapped_id($id): string
    {
        return substr($id, 5, -5);
    }
    public static function get_current_file_url($Protocol = 'http://')
    {
        return $Protocol . $_SERVER['HTTP_HOST'] . str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(__DIR__));
    }
}
