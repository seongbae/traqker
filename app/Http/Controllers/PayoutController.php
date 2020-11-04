<?php

namespace App\Http\Controllers;

use App\Payout;
use App\Http\Datatables\PayoutDatatable;
use App\Http\Requests\PayoutRequest;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Payout::query();
        $datatables = PayoutDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('payouts.index', $datatables->html());
    }

    public function create()
    {
        return view('payouts.create');
    }

    public function store(PayoutRequest $request)
    {
        Payout::create($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('payouts.create')
            : redirect()->route('payouts.index');
    }

    public function show(Payout $payout)
    {
        return view('payouts.show', compact('payout'));
    }

    public function edit(Payout $payout)
    {
        return view('payouts.edit', compact('payout'));
    }

    public function update(PayoutRequest $request, Payout $payout)
    {
        $payout->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('payouts.edit', $payout->id)
            : redirect()->route('payouts.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Payout $payout)
    {
        $payout->delete();

        return redirect()->route('payouts.index');
    }
}
