<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\AppointmentTransaction;
use App\Models\Currency;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Vcard;
use App\Repositories\AppointmentRepository;
use App\Repositories\SubscriptionRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Laracasts\Flash\Flash;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalHttp\HttpException;
use PayPalHttp\IOException;

class PaypalController extends AppBaseController
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     * @throws IOException
     *
     * @throws HttpException
     */
    public function onBoard(Request $request)
    {
        $plan = Plan::with('currency')->findOrFail($request->planId);

        if ($plan->currency->currency_code != null && !in_array(strtoupper($plan->currency->currency_code),
                getPayPalSupportedCurrencies())) {
            return $this->sendError(__('messages.placeholder.this_currency_is_not_supported'));
        }

        $data = $this->subscriptionRepository->manageSubscription($request->get('planId'));


        if (!isset($data['plan'])) { // 0 amount plan or try to switch the plan if it is in trial mode
            // returning from here if the plan is free.
            if (isset($data['status']) && $data['status'] == true) {
                return $this->sendSuccess($data['subscriptionPlan']->name.' '.__('messages.subscription_pricing_plans.has_been_subscribed'));
            } else {
                if (isset($data['status']) && $data['status'] == false) {
                    return $this->sendError(__('messages.placeholder.cannot_switch_to_zero'));
                }
            }
        }

        $subscriptionsPricingPlan = $data['plan'];
        $subscription = $data['subscription'];

        $clientId = config('paypal.paypal.client_id');
        $clientSecret = config('paypal.paypal.client_secret');
        $mode = config('paypal.mode');

        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }

        $client = new PayPalHttpClient($environment);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent"              => "CAPTURE",
            "purchase_units"      => [
                [
                    "reference_id" => $subscription->id,
                    "amount"       => [
                        "value"         => $data['amountToPay'],
                        "currency_code" => $subscription->plan->currency->currency_code,
                    ],
                ],
            ],
            "application_context" => [
                "cancel_url" => route('paypal.failed'),
                "return_url" => route('paypal.success'),
            ],
        ];

        $order = $client->execute($request);

        session(['payment_type' => request()->get('payment_type')]);

        return response()->json($order);
    }

    /**
     * @param $userId
     * @param $vcard
     * @param $input
     *
     * @throws HttpException
     * @throws IOException
     *
     * @return JsonResponse
     */
    public function userOnBoard($userId, $vcard, $input): JsonResponse
    {
        $amount = $input['amount'];
        $currencyCode = $input['currency_code'];

        $clientId = getUserSettingValue('paypal_client_id', $userId);
        $clientSecret = getUserSettingValue('paypal_secret', $userId);
        $mode = getUserSettingValue('paypal_mode', $userId);
        
        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            "intent"              => "CAPTURE",
            "purchase_units"      => [
                [
                    "reference_id" => $vcard->id,
                    "amount"       => [
                        "value"         => $amount,
                        "currency_code" => $currencyCode,
                    ],
                ],
            ],
            "application_context" => [
                "cancel_url" => route('user.paypal.failed'),
                "return_url" => route('user.paypal.success'),
            ],
        ];

        $order = $client->execute($request);
        session()->put(['appointment_details' => $input]);
        session(['vcard_user_id' => $userId, 'tenant_id'=> $vcard->tenant->id , 'vcard_id' => $vcard->id]);
        
        return response()->json($order);
    }

    /**
     * @param Request $request
     *
     * @throws IOException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     *
     * @return Application|RedirectResponse|Redirector|void
     */
    public function userSuccess(Request $request)
    {
        $userId = session()->get('vcard_user_id');
        $clientId = getUserSettingValue('paypal_client_id', $userId);
        $clientSecret = getUserSettingValue('paypal_secret', $userId);
        $mode = getUserSettingValue('paypal_mode', $userId);

        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        // Here, OrdersCaptureRequest() creates a POST request to /v2/checkout/orders
        $request = new OrdersCaptureRequest($request->get('token'));
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            $vcardId = $response->result->purchase_units[0]->reference_id;
            $tenantId = session()->get('tenant_id');
            $amount = $response->result->purchase_units[0]->amount->value;
            $currencyCode = $response->result->purchase_units[0]->amount->currency_code;
            $currencyId = Currency::whereCurrencyCode($currencyCode)->first()->id;
            $transactionId = $response->result->id;
            $vcard = Vcard::with('tenant.user')->where('id', $vcardId)->first();
            
            $transactionDetails = [
                'vcard_id'       => $vcardId,
                'transaction_id' => $transactionId,
                'currency_id'    => $currencyId,
                'amount'         => $amount,
                'tenant_id'      => $tenantId,
                'type'           => Appointment::PAYPAL,
                'status'         => Transaction::SUCCESS,
                'meta'           => json_encode($response),
            ];

            $appointmentTran = AppointmentTransaction::create($transactionDetails);
            $appointmentInput = session()->get('appointment_details');
            session()->forget('appointment_details');
            $appointmentInput['appointment_tran_id'] = $appointmentTran->id;
            
            
            /** @var AppointmentRepository $appointmentRepo */
            $appointmentRepo = App::make(AppointmentRepository::class);
            $vcardEmail = is_null($vcard->email) ? $vcard->tenant->user->email : $vcard->email;
            $appointmentRepo->appointmentStoreOrEmail($appointmentInput, $vcardEmail);

            session()->forget(['vcard_user_id', 'tenant_id', 'vcard_id']);

            Flash::success(__('messages.placeholder.payment_done'));

            return redirect(route('vcard.show',[$vcard->url_alias, __('messages.placeholder.appointment_created')]));
        } catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
    }

    /**
     *
     *
     * @return Application|RedirectResponse|Redirector
     */
    public function userFailed()
    {
        $vcardId = session('vcard_id');

        session()->forget('appointment_details');
        session()->forget(['vcard_user_id', 'tenant_id', 'vcard_id']);

        Flash::error('Your Payment is Cancelled');

        return redirect(route('vcard.show', $vcardId));
    }


    /**
     * @param Request $request
     * @throws IOException
     * @return Application|Factory|View|void
     */
    public function success(Request $request)
    {
        $clientId = config('paypal.paypal.client_id');
        $clientSecret = config('paypal.paypal.client_secret');
        $mode = config('paypal.paypal.mode');

        if ($mode == 'live') {
            $environment = new ProductionEnvironment($clientId, $clientSecret);
        } else {
            $environment = new SandboxEnvironment($clientId, $clientSecret);
        }
        $client = new PayPalHttpClient($environment);

        // Here, OrdersCaptureRequest() creates a POST request to /v2/checkout/orders
        $request = new OrdersCaptureRequest($request->get('token'));
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $client->execute($request);

            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            $subscriptionId = $response->result->purchase_units[0]->reference_id;
            $subscriptionAmount = $response->result->purchase_units[0]->amount->value;
            $transactionID = $response->result->id;     // $response->result->id gives the orderId of the order created above

            Subscription::findOrFail($subscriptionId)->update(['status' => Subscription::ACTIVE]);
            // De-Active all other subscription
            Subscription::whereTenantId(getLogInTenantId())
                ->where('id', '!=', $subscriptionId)
                ->update([
                    'status' => Subscription::INACTIVE,
                ]);

            $transaction = Transaction::create([
                'transaction_id' => $transactionID,
                'type'           => session('payment_type'),
                'amount'         => $subscriptionAmount,
                'status'         => Subscription::ACTIVE,
                'meta'           => json_encode($response),
            ]);

            // updating the transaction id on the subscription table
            $subscription = Subscription::findOrFail($subscriptionId);
            $subscription->update(['transaction_id' => $transaction->id]);

            return view('sadmin.plans.payment.paymentSuccess');

        } catch (HttpException $ex) {
            echo $ex->statusCode;
            print_r($ex->getMessage());
        }
    }

    /**
     *
     *
     * @return Application|Factory|View
     */
    public function failed()
    {
        return view('sadmin.plans.payment.paymentcancel');

    }

}
