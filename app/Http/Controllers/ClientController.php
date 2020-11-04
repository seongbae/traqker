<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Datatables\ClientDatatable;
use App\Http\Requests\ClientRequest;
use Illuminate\Http\Request;
use Auth;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Client::where('user_id', AUth::id());
        $datatables = ClientDatatable::make($query);

        return $request->ajax()
            ? $datatables->json()
            : view('clients.index', $datatables->html());
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientRequest $request)
    {
        Client::create(array_merge($request->all(), ['user_id'=>Auth::id()]));

        return $request->input('submit') == 'reload'
            ? redirect()->route('clients.create')
            : redirect()->route('clients.index');
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->all());

        return $request->input('submit') == 'reload'
            ? redirect()->route('clients.edit', $client->id)
            : redirect()->route('clients.index');
    }

    /** @noinspection PhpUnhandledExceptionInspection */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index');
    }
}
