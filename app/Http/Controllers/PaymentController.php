<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Auth;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Task;
use App\Events\PaymentSent;
use Debugbar;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $stripeCharge = null;

        $paymentMethod = $user->defaultPaymentMethod();

        if ($paymentMethod) {
            $stripeCharge = $user->charge($request->get('amount')*100, $paymentMethod->id);
        }
        else {
            if ($user->hasPaymentMethod()) {
                $stripeCharge = $user->charge($request->get('amount')*100, $user->paymentMethods()->first()->id);
            }
        }

        if ($stripeCharge != null)
        {
            $payerId = Auth::id();
            $payeeId = Auth::id();
            $method = "stripe";

            $payment = new Payment;
            $payment->payer_id = $payerId;
            $payment->payee_id = $payeeId;
            $payment->method = $method;
            $payment->amount = $request->get('amount');
            $payment->transaction_id = $stripeCharge->id;
            $payment->save();
        }


        return redirect()->back();
    }

    public function sendPayment(Request $request)
    {
        $payee = User::find($request->get('payee_id'));
        $payer = User::find($request->get('payer_id'));
        $amount = $request->get('amount');
        $taskIds = $request->get('task_ids');

        // Create a new instance of Payout object
        $payouts = new \PayPal\Api\Payout();

        // This is how our body should look like:
        /*
         * {
                    "sender_batch_header":{
                        "sender_batch_id":"2014021801",
                        "email_subject":"You have a Payout!"
                    },
                    "items":[
                        {
                            "recipient_type":"EMAIL",
                            "amount":{
                                "value":"1.0",
                                "currency":"USD"
                            },
                            "note":"Thanks for your patronage!",
                            "sender_item_id":"2014031400023",
                            "receiver":"shirt-supplier-one@mail.com"
                        }
                    ]
                }
         */

        $senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
        // ### NOTE:
        // You can prevent duplicate batches from being processed. If you specify a `sender_batch_id` that was used in the last 30 days, the batch will not be processed. For items, you can specify a `sender_item_id`. If the value for the `sender_item_id` is a duplicate of a payout item that was processed in the last 30 days, the item will not be processed.

        // #### Batch Header Instance
        $senderBatchHeader->setSenderBatchId(uniqid())
            ->setEmailSubject("You have a Payout!");

        $transactionId = $this->random_strings(8);

        // #### Sender Item
        // Please note that if you are using single payout with sync mode, you can only pass one Item in the request
        $senderItem = new \PayPal\Api\PayoutItem();
        $senderItem->setRecipientType('Email')
            ->setNote('Thank you.')
            ->setReceiver($payee->paypal_email)
            ->setSenderItemId($transactionId)
            ->setAmount(new \PayPal\Api\Currency('{
                                "value": '.$amount.',
                                "currency":"USD"
                            }'));

        $payouts->setSenderBatchHeader($senderBatchHeader)
            ->addItem($senderItem);


        //Log::info(json_encode($payouts));

        // For Sample Purposes Only.
        $request = clone $payouts;

        //Log::info('Payer info: '.json_encode($request));

        // ### Create Payout
        try {
            $paypal_conf = \Config::get('paypal');

            $apiContext = new ApiContext(new OAuthTokenCredential(
                $payer->paypal_client_id,
                $payer->paypal_secret)
            );

            $apiContext->setConfig(
                  $paypal_conf['settings']
            );

            $output = $payouts->create(array('sync_mode' => 'false'), $apiContext);

            $payment = Payment::create(
                        [
                            'payee_id'=>$payee->id,
                            'payer_id'=>$payer->id,
                            'amount'=>$amount,
                            'method'=>'paypal',
                            'transaction_id'=>$transactionId,
                            'external_transaction_id'=>$output->getBatchHeader()->getPayoutBatchId()
                        ]
                        );

            event(new PaymentSent($payer, $payee, $payment, "You received $".$amount." from ".$payer->name));

            $tasks = Task::whereIn('id', array_values(array_unique($taskIds)))->get();
            foreach($tasks as $task)
            {
                $task->paid = true;
                $task->payment_id = $payment->id;
                $task->save();
            }

            flash()->success('You successfully sent a payment.');

        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            //ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);

            Log::info('Payout error: '.json_encode($ex) . '\n'.'Request: '.json_encode($request));
        }

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        // ResultPrinter::printResult("Created Single Synchronous Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output);

        //return $output;
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }





    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }


    private static function buildRequestBody($amount, $email, $currency='USD')
    {
      return array(
        'intent' => 'AUTHORIZE',
        'purchase_units' =>
          array(
            0 =>
              array(
                'amount' =>
                  array(
                    'currency_code' => $currency,
                    'value' => $amount
                  )
              ),
              array(
                'payee' =>
                  array(
                    'email_address' => $email
                  )
              )
          )
      );
    }

    private static function random_strings($length_of_string)
    {

        // String of all alphanumeric character
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Shufle the $str_result and returns substring
        // of specified length
        return substr(str_shuffle($str_result),
                           0, $length_of_string);
    }
}
