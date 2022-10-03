<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PaypalPayoutsSDK\Core\PayPalHttpClient;
use PaypalPayoutsSDK\Core\ProductionEnvironment;
use PaypalPayoutsSDK\Core\SandboxEnvironment;
use PaypalPayoutsSDK\Payouts\PayoutsPostRequest;


class PaypalPayoutController extends AppBaseController
{
    public function payout(Request $request)
    {
        $referenceId = $request->get('reference_id');
        $email = $request->get('email');
        $amount = $request->get('amount');
        $currency = $request->get('currency');

        $clientId = getSettingValue('paypal_client_id');
        $clientSecret = getSettingValue('paypal_secret');
        $mode = getSettingValue('paypal_mode');

        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }

        $client = new PayPalHttpClient($environment);
        $request = new PayoutsPostRequest();

        $body = json_decode(
            '{
                "sender_batch_header":
                {
                  "email_subject": "SDK payouts test txn"
                },
                "items": [
                {
                  "recipient_type": "EMAIL",
                  "receiver": '.json_encode($email).',
                  "note": "Your 1$ payout",
                  "sender_item_id": '.json_encode($referenceId).',
                  "amount":
                  {
                    "currency": '.json_encode($currency).',
                    "value": '.json_encode($amount).'
                  }
                }]
              }',
            true);
        $request->body = $body;
        $response = $client->execute($request);

        return $this->sendSuccess(__('messages.placeholder.withdrawal_request_send'));
    }
}
