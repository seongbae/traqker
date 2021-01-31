<?php

namespace App\Http\Controllers;

use App\DeviceToken;
use App\Http\Datatables\DeviceTokenDatatable;
use App\Http\Requests\DeviceTokenRequest;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = DeviceToken::query();
        $datatables = DeviceTokenDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('device_tokens.index', $datatables->html());
    }

    public function create()
    {
        return view('device_tokens.create');
    }

    public function store(DeviceTokenRequest $request)
    {
        $deviceToken = DeviceToken::where('device_token', $request->device_token)->first();

        if ($deviceToken)
        {
            $deviceToken->user_id = $request->user_id;
            $deviceToken->save();
        }
        else
            DeviceToken::create($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('device_tokens.create')
            : redirect()->route('device_tokens.index');
    }

    public function show(DeviceToken $device_token)
    {
        return view('device_tokens.show', compact('device_token'));
    }

    public function edit(DeviceToken $device_token)
    {
        return view('device_tokens.edit', compact('device_token'));
    }

    public function update(DeviceTokenRequest $request, DeviceToken $device_token)
    {
        $device_token->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('device_tokens.edit', $device_token->id)
            : redirect()->route('device_tokens.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(DeviceToken $device_token)
    {
        $device_token->delete();

        return redirect()->route('device_tokens.index');
    }
}
