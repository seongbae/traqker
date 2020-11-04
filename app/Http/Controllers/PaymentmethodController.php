<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class PaymentmethodController extends Controller
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

        $stripeCustomer = $user->createOrGetStripeCustomer();

        $user->addPaymentMethod($request->get('payment_id'));

        if (count($user->paymentMethods()) == 1) {
            $user->updateDefaultPaymentMethod($request->get('payment_id'));
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $stripeCustomer = $user->createOrGetStripeCustomer();

        if ($request->get('action') == 'make_default')
            $user->updateDefaultPaymentMethod($id);

        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentSettings(Request $request)
    {
        $user = Auth::user();

        if ($request->get('paypal_email'))
            $user->paypal_email = $request->get('paypal_email');

       if ($request->get('paypal_phone'))
            $user->paypal_phone = $request->get('paypal_phone');

        if ($request->get('paypal_client_id'))
            $user->paypal_client_id = $request->get('paypal_client_id');

        if ($request->get('paypal_secret'))
            $user->paypal_secret = $request->get('paypal_secret');

        $user->save();
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $paymentMethod = $user->findPaymentMethod($id);
        $paymentMethod->delete();

        if (count($user->paymentMethods()) == 1)
            $user->updateDefaultPaymentMethod($user->paymentMethods()->first()->id);

        return redirect()->back();
    }
}
