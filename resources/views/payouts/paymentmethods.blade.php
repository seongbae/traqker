<h3>Pay using credit cards</h3>
                @if (Auth::user()->isStripeCustomer())
                    @if (count(Auth::user()->paymentMethods()) > 0)
                    <table class="table" style="max-width:800px;">
                      <thead>
                        <tr>
                          <th scope="col">Card</th>
                          <th scope="col">Exp</th>
                          <th scope="col">Default</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach(Auth::user()->paymentMethods() as $paymentMethod)
                          <tr>
                            <td>
                            ****-****-****-{{ $paymentMethod->card->last4 }} ({{ $paymentMethod->card->brand }})
                            </td>
                            <td> {{ $paymentMethod->card->exp_month }}/{{ $paymentMethod->card->exp_year }} 
                            </td>
                            <td>
                            @if (Auth::user()->defaultPaymentMethod()->id == $paymentMethod->id)
                                Default
                            @else
                                <form action="/account/paymentmethods/{{$paymentMethod->id}}" method="POST" style="display:inline;">
                                {{ csrf_field() }}
                                @method('PUT')
                                <input type="hidden" name="action" value="make_default">
                                <button type="submit" class="btn btn-primary" >Make Default</button>
                            </form>
                            @endif
                            </td>
                            <td>
                             <form action="/account/paymentmethods/{{$paymentMethod->id}}" method="POST" style="display:inline;">
                                {{ csrf_field() }}
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">Delete</button>
                            </form>
                          </td>
                          </tr>
                        @endforeach
                        </tbody>
                      </table>
                    @endif

                    <a href="" class="btn btn-info" data-toggle="modal" data-target="#addPaymentMethodModal">Add Credit/Debit Card</a>
              
                  @endif
                </div>