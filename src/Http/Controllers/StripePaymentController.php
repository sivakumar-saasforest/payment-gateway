<?php

namespace SyncMaster\PaymentGateway\Http\Controllers;

use SyncMaster\PaymentGateway\Facades\SyncMasterPaymentGateway;
use Illuminate\Http\Request;

class StripePaymentController extends Controller
{
    public function charge_customer(Request $request)
    {
        try {
            $stripe_session = SyncMasterPaymentGateway::stripe()->charge_customer_from_controller([
                'amount' => $request->amount,
                'charge_amount' => $request->charge_amount,
                'title' => $request->title,
                'description' => $request->description,
                'ipn_url' => $request->ipn_url,
                'order_id' => $request->order_id,
                'track' => $request->track,
                'cancel_url' => $request->cancel_url,
                'success_url' => $request->success_url,
                'email' => $request->email,
                'name' => $request->name,
                'payment_type' => $request->payment_type,
                'secret_key' => $request->secret_key,
                'currency' => $request->currency,
            ]);

            return response()->json(['id' => $stripe_session['id']]);
        } catch (\Exception $e) {
            return response()->json(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }


    public function stripePayment($id)
    {
        $payment = Sub::find($id);
        $user = $payment->business->users->first();
        $stripePrice = (object) json_decode($payment->plan->payment_data, true);

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $session = \Stripe\Checkout\Session::create([
            'success_url' => route('payment.stripe.success', $payment->business->id) . '/?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.failed'),
            'mode' => 'subscription',
            'customer_email' => $user->email,
            'line_items' => [
                [
                    'price' => $stripePrice->stripe['id'],
                    'quantity' => 1,
                ]
            ],
            'subscription_data' => [
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $stripePrice->stripe['id']
                ]
            ]
        ]);

        return redirect()->to($session->url);
    }
}
