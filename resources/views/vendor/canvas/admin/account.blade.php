<?php
define('CLIENT_ID', '');
define('API_KEY', '');

define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
define('AUTHORIZE_URI', 'https://connect.stripe.com/express/oauth/authorize');

if (isset($_GET['code'])) { // Redirect w/ code
  $code = $_GET['code'];

  $token_request_body = array(
    'client_secret' => API_KEY,
    'grant_type' => 'authorization_code',
    'client_id' => CLIENT_ID,
    'code' => $code,
  );

  $req = curl_init(TOKEN_URI);
  curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($req, CURLOPT_POST, true );
  curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

  // TODO: Additional error handling
  $respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
  $resp = json_decode(curl_exec($req), true);
  curl_close($req);

  echo $resp['access_token'];
} else if (isset($_GET['error'])) { // Error
  echo $_GET['error_description'];
} else { // Show OAuth link
  $authorize_request_body = array(
    'response_type' => 'code',
    'scope' => 'read_write',
    'client_id' => CLIENT_ID
  );

  $url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);
  //echo "<a href='$url'>Connect with Stripe</a>";
}
?>
@extends('canvas::admin.layouts.app')


@section('content')

<div class="row">
    <div class="col-12">
        <div class="card card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="account-tab" data-toggle="tab" href="#account" role="tab" aria-controls="account" aria-selected="true">My Account</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="availability-tab" data-toggle="tab" href="#availability" role="tab" aria-controls="availability" aria-selected="false">Availability</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="teams-tab" data-toggle="tab" href="#teams" role="tab" aria-controls="teams" aria-selected="false">Teams</a>
              </li>
                <li class="nav-item">
                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="teams" aria-selected="false">Settings</a>
                </li>

            </ul>
            <div class="tab-content mt-4" id="myTabContent">
              <div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <form action="/admin/users/{{$user->id}}" method="POST" enctype="multipart/form-data" style="display:inline;" autocomplete="off">
                                {{ csrf_field() }}
                                 @method('PUT')
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="{{$user->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" value="{{$user->email}}">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirm">Password</label>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="Confirm Password">
                                </div>
                                 <div class="form-group">
                                    <label for="password_confirm">Timezone</label>
                                    {!! $timezone_select !!}
                                </div>
                                <div class="form-group">
                                    <img src="/storage/{{Auth::user()->photo}}" style="width:80px;" class="img-circle elevation-2">
                                    <input type="file" class="form-control-file" id="file" name="file">
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Save</button>
                                <a href="{{ URL::previous() }}" class="btn btn-default  mr-2">Cancel</a>

                            </form>
                        </div>
                    </div>

              </div>
{{--              <div class="tab-pane fade" id="payentmethods" role="tabpanel" aria-labelledby="payentmethods-tab">--}}
{{--                Payout methods are for sending payment to others. You can use credit card or PayPal.--}}

{{--                <div class="mt-4">--}}
{{--                  <form method="POST" action="/account/payment">--}}
{{--                      @csrf--}}
{{--                      <div class="form-group">--}}
{{--                        <label form="paypal_client_id">PayPal Client ID</label>--}}
{{--                        <input type="text" name="paypal_client_id" value="{{ old('paypal_client_id', $user->paypal_client_id ?? '')}}" class="form-control">--}}
{{--                      </div>--}}
{{--                      <div class="form-group">--}}
{{--                        <label form="paypal_secret">PayPal Secret</label>--}}
{{--                        <input type="text" name="paypal_secret" value="{{ old('paypal_secret', $user->paypal_secret ?? '')}}" class="form-control">--}}
{{--                      </div>--}}
{{--                      <button type="submit" class="btn btn-primary mr-2">Save</button>--}}

{{--                  </form>--}}

{{--                  </div>--}}
{{--              </div>--}}

{{--              <div class="tab-pane fade" id="payoutmethods" role="tabpanel" aria-labelledby="payoutmethods-tab">--}}
{{--                Payout methods are for receiving payment. You can use Stripe or PayPal to receive payment from others.--}}
{{--                <div class="mt-4">--}}
{{--                <form method="POST" action="/account/payment">--}}
{{--                    @csrf--}}
{{--                    <div class="form-group">--}}
{{--                      <label form="paypal_email">PayPal E-mail</label>--}}
{{--                      <input type="text" name="paypal_email" value="{{ old('paypal_email', $user->paypal_email ?? '')}}" class="form-control">--}}
{{--                    </div>--}}
{{--                    <button type="submit" class="btn btn-primary mr-2">Save</button>--}}

{{--                </form>--}}
{{--              </div>--}}

{{--                <a href="{{$url}}" class="btn btn-primary" style="display:none;">Connect with Stripe</a>--}}

{{--              </div>--}}
              <div class="tab-pane fade" id="availability" role="tabpanel" aria-labelledby="availability-tab">
                <p>My timezone: {{ Auth::user()->timezone }}</p>
                <div id="dp"></div>
              </div>
              <div class="tab-pane fade" id="teams" role="tabpanel" aria-labelledby="teams-tab">
                  <div class="col-md-auto mb-3 mb-md-0">
                    <table class="table">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Access</th>
                          <th>Title</th>
                        </tr>
                      </thead>
                      <tbody>
                      @foreach(Auth::user()->teams as $team)
                      <tr>
                        <td><a href="{{ route('teams.show', $team) }}">{{$team->name}}</a></td>
                        <td>{{$team->pivot->access }}</td>
                        <td>{{$team->pivot->title}}</td>
                      </tr>
                      @endforeach
                    </tbody>
                    </table>
                      <a href="{{ route('teams.create') }}" class="btn btn-primary">{{ __('Create Team') }}</a>
                  </div>
              </div>
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <form action="/user/{{Auth::user()->id}}/settings" method="POST" style="display:inline;" autocomplete="off">
                            {{ csrf_field() }}
                            @method('PUT')
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="dailyReminderEmail" name="daily_reminder_email" value="1" {{ Auth::user()->setting('daily_reminder_email') == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="dailyReminderEmail">Receive daily task reminder emails</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="browser_notification" name="browser_notification" value="1" {{ Auth::user()->setting('browser_notification') == 1 ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="browser_notification">Receive browser notification</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm mr-2">Save</button>

                        </form>

                    </div>
                </div>
{{--              <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">--}}
{{--                <div class="row">--}}
{{--                  <div class="col-lg-3 col-6">--}}
{{--                    <!-- small card -->--}}
{{--                    <div class="small-box bg-success">--}}
{{--                      <div class="inner">--}}
{{--                        <h3>${{ number_format(Auth::user()->getTotalEarned()) }}</h3>--}}
{{--        --}}
{{--                        <p>Total Earned</p>--}}
{{--                      </div>--}}
{{--                      <div class="icon">--}}
{{--                        <i class="fas fa-dollar-sign"></i>--}}
{{--                      </div>--}}
{{--        --}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                  <div class="col-lg-3 col-6">--}}
{{--                    <!-- small card -->--}}
{{--                    <div class="small-box bg-info">--}}
{{--                      <div class="inner">--}}
{{--                        <h3>{{ Auth::user()->getTotalTimeSpent() }} <sup style="font-size: 16px">hours</sup></h3>--}}
{{--        --}}
{{--                        <p>Total Time Spent</p>--}}
{{--                      </div>--}}
{{--                      <div class="icon">--}}
{{--                        <i class="far fa-clock"></i>--}}
{{--                      </div>--}}
{{--        --}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                  <!-- ./col -->--}}
{{--                  <!-- ./col -->--}}
{{--                  <div class="col-lg-3 col-6">--}}
{{--                    <!-- small card -->--}}
{{--                    <div class="small-box bg-warning">--}}
{{--                      <div class="inner">--}}
{{--                        <h3>{{ Auth::user()->getOnTimeCompletionRate() }} <sup style="font-size: 16px">%</sup></h3>--}}
{{--        --}}
{{--                        <p>On-Time Completion Rate</p>--}}
{{--                      </div>--}}
{{--                      <div class="icon">--}}
{{--                        <i class="fas fa-history"></i>--}}
{{--                      </div>--}}
{{--        --}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                  <!-- ./col -->--}}
{{--                  <div class="col-lg-3 col-6">--}}
{{--                    <!-- small card -->--}}
{{--                    <div class="small-box bg-danger">--}}
{{--                      <div class="inner">--}}
{{--                        <h3>{{ Auth::user()->getEfficiencyRate() }} <sup style="font-size: 16px">%</sup></h3>--}}
{{--        --}}
{{--                        <p>Efficiency Rate</p>--}}
{{--                      </div>--}}
{{--                      <div class="icon">--}}
{{--                        <i class="fas fa-user-ninja"></i>--}}
{{--                      </div>--}}
{{--        --}}
{{--                    </div>--}}
{{--                  </div>--}}
{{--                  <!-- ./col -->--}}
{{--                </div>--}}
{{--                  <div class="col-md-auto mb-3 mb-md-0">--}}
{{--                    <table class="table">--}}
{{--                      <thead>--}}
{{--                        <tr>--}}
{{--                          <th>Date</th>--}}
{{--                          <th>Amount</th>--}}
{{--                          <th>From</th>--}}
{{--                          <th>To</th>--}}
{{--                          <th>Method</th>--}}
{{--                          <th>Transaction ID</th>--}}
{{--                        </tr>--}}
{{--                      </thead>--}}
{{--                      <tbody>--}}
{{--                      @foreach(Auth::user()->transactions as $payment)--}}
{{--                      <tr>--}}
{{--                        <td>{{$payment->created_at->format('Y-m-d')}}</a></td>--}}
{{--                        <td>${{$payment->amount}}</td>--}}
{{--                        <td><img src="/storage/{{ $payment->payer->photo }}" class="profile-small img-circle"> {{$payment->payer->name}}</a></td>--}}
{{--                        <td><img src="/storage/{{ $payment->payee->photo }}" class="profile-small img-circle"> {{$payment->payee->name }}</td>--}}
{{--                        <td>{{$payment->method}}</td>--}}
{{--                        <td>{{$payment->external_transaction_id}}</td>--}}
{{--                      </tr>--}}
{{--                      @endforeach--}}
{{--                    </tbody>--}}
{{--                    </table>--}}
{{--                  </div>--}}
{{--              </div>--}}
            </div>

        </div>
    </div>


</div>
<!-- Modal -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form method="POST" action="/account/paymentmethods" id="paymentform">
            @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add New Payment Method</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="firstName">Name on Card</label>
                  <input type="text" class="form-control" id="card-holder-name" name="card_name" placeholder="" value="" required="">
                  <div class="invalid-feedback">
                    Valid name is required.
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="firstName">Credit card number</label>
                    <div id="card-element" class="form-control">
                    <!-- A Stripe Element will be inserted here. -->
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="card-button">Save changes</button>
          </div>
        </form>
    </div>
  </div>
</div>

   {!! $html->scripts() !!}
@stop

@push('scripts')

<script src="https://js.stripe.com/v3/"></script>
<script src="/js/daypilot-all.min.js?v=2018.2.232" type="text/javascript"></script>
<script>
     const stripe = Stripe('{{config('cashier.key')}}');

    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const cardHolderName = document.getElementById('card-holder-name');
    const cardButton = document.getElementById('card-button');

    cardButton.addEventListener('click', async (e) => {
        const { paymentMethod, error } = await stripe.createPaymentMethod(
            'card', cardElement, {
                billing_details: { name: cardHolderName.value }
            }
        );

        if (error) {
            // Display "error.message" to the user...
        } else {
            var payment_id = paymentMethod.id;
            createPayment(payment_id);
        }


    });

    var form = document.getElementById('paymentform');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
    });

    function createPayment(payment_id) {
        // Insert the token ID into the form so it gets submitted to the server
          var form = document.getElementById('paymentform');
          var hiddenInput = document.createElement('input');
          hiddenInput.setAttribute('type', 'hidden');
          hiddenInput.setAttribute('name', 'payment_id');
          hiddenInput.setAttribute('value',payment_id);
          form.appendChild(hiddenInput);
          // Submit the form

          form.submit();

    }
</script>
<script>
    $(document).ready(() => {
      let url = location.href.replace(/\/$/, "");

      if (location.hash) {
        const hash = url.split("#");
        $('#myTab a[href="#'+hash[1]+'"]').tab("show");
        url = location.href.replace(/\/#/, "#");
        history.replaceState(null, null, url);
        setTimeout(() => {
          $(window).scrollTop(0);
        }, 400);
      }

      $('a[data-toggle="tab"]').on("click", function() {
        let newUrl;
        const hash = $(this).attr("href");
        if(hash == "#home") {
          newUrl = url.split("#")[0];
        } else {
          newUrl = url.split("#")[0] + hash;
        }
        newUrl += "/";
        history.replaceState(null, null, newUrl);
      });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('sw.js').then(function(reg) {
                console.log('Service Worker Registered!', reg);

                reg.pushManager.getSubscription().then(function(subscription) {
                    if (subscription === null) {
                        // Update UI to ask user to register for Push
                        console.log('Not subscribed to push service!');
                    } else {
                        // We have a subscription, update the database
                        // console.log('Subscription object: ', subscription);

                        const key = subscription.getKey('p256dh')
                        const token = subscription.getKey('auth')
                        const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]

                        const data = {
                            endpoint: subscription.endpoint,
                            publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                            authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                            contentEncoding
                        }

                        $.ajax({
                            type:'POST',
                            url:'/subscriptions',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: data,

                            success:function(data) {
                                //alert('success');
                            }
                        });
                    }
                });
            })
                .catch(function(err) {
                    console.log('Service Worker registration failed: ', err);
                });
        }

        function subscribeUser() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.ready.then(function(reg) {
                    const options = { userVisibleOnly: true }
                    const vapidPublicKey = window.Laravel.vapidPublicKey

                    if (vapidPublicKey) {
                        options.applicationServerKey = this.urlBase64ToUint8Array(vapidPublicKey)
                    }

                    reg.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: vapidPublicKey
                    }).then(function(sub) {
                        // console.log('Endpoint URL: ', sub.endpoint);
                    }).catch(function(e) {
                        if (Notification.permission === 'denied') {
                            console.warn('Permission for notifications was denied');
                        } else {
                            console.error('Unable to subscribe to push', e);
                        }
                    });
                })
            } else {
                console.log('serviceWorker not in navigator');
            }
        }

        function unsubscribeUser() {
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.ready.then(function(reg) {
                    reg.pushManager.getSubscription().then(subscription => {
                        if (!subscription) {
                            return
                        }

                        subscription.unsubscribe().then(() => {
                            $.ajax({
                                type:'POST',
                                url:'/subscriptions/delete',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                data: {
                                    endpoint: subscription.endpoint
                                },
                                success:function(data) {
                                    //alert('success');
                                }
                            });
                        }).catch(e => {
                            console.log('Unsubscription error: ', e)
                        })
                    }).catch(e => {
                        console.log('Error thrown while unsubscribing.', e)
                    })
                })
            } else {
                console.log('serviceWorker not in navigator');
            }
        }

        $('#browser_notification').change(function() {
            if(this.checked) {
                subscribeUser();
            } else {
                unsubscribeUser();
            }
        });
    });

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4)
        const base64 = (base64String + padding)
            .replace(/\-/g, '+')
            .replace(/_/g, '/')

        const rawData = window.atob(base64)
        const outputArray = new Uint8Array(rawData.length)

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i)
        }

        return outputArray
    }
</script>
<script type="text/javascript">

    var dp = new DayPilot.Calendar("dp");

    // view
    dp.startDate = "2020-05-03";
    dp.viewType = "Week";
    dp.heightSpec = "Full";

    // event creating
    dp.onTimeRangeSelected = function (args) {
        var name = "available"; //prompt("New event name:", "Event");
        if (!name) return;
        var e = new DayPilot.Event({
            start: args.start,
            end: args.end,
            id: DayPilot.guid(),
            text: name
        });
        dp.events.add(e);

        // Send to server
        // alert(args.start + ' ' + args.end);
        // var start = e.start;

        dp.clearSelection();

        var jqxhr = $.ajax({
             type:'POST',
             url:'/availability',
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
             data: {
              guid: e.id,
              start: e.start,
              end: e.end,
              name: "available"
             },

             success:function(data) {
                //alert('success');
             }
          });
    };

    dp.onEventResized = function (args) {
      var jqxhr = $.ajax({
             type:'POST',
             method: 'PUT',
             url:'/availability/'+args.e.data.id,
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
             data: {
              start: args.e.start,
              end: args.e.end,
              name: "available"
             },

             success:function(data) {
                //alert('success');
             }
          });
    };

    dp.onEventMoved = function (args) {
      var jqxhr = $.ajax({
             type:'POST',
             method: 'PUT',
             url:'/availability/'+args.e.data.id,
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
             data: {
              start: args.e.start,
              end: args.e.end,
              name: "available"
             },

             success:function(data) {
                //alert('success');
             }
          });
    };

    dp.onEventClick = function(args) {
        //alert("clicked: " + args.e.id());
        if (confirm('Delete?')){
          dp.events.remove(args.e);

          //console.log(args.e);

          var jqxhr = $.ajax({
             type:'DELETE',
             url:'/availability/'+args.e.data.id,
             headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },

             success:function(data) {
                //alert('success');
             }
          });
        }
    };

    dp.headerDateFormat = "dddd";
    dp.init();

    $.ajax({
         url: '/user/'+{{Auth::id()}}+'/availability/',
         type: 'get',
         dataType: 'json',
         success: function(response){

            if(response['data'] != null){
               len = response['data'].length;

               if(len > 0){
                 for(var i=0; i<len; i++){
                  //console.log(response['data'][i]);

                  var dt = "2020-05-03T";
                  switch(response['data'][i].dayofweek) {
                    case 0:
                      // code
                      break;
                    case 1:
                      dt = "2020-05-04T";
                      break;
                    case 2:
                      dt = "2020-05-05T";
                      break;
                    case 3:
                      dt = "2020-05-06T";
                      break;
                    case 4:
                      dt = "2020-05-07T";
                      break;
                    case 5:
                      dt = "2020-05-08T";
                      break;
                    case 6:
                      dt = "2020-05-09T";
                      break;
                    default:
                      // code block
                  }

                  var e = new DayPilot.Event({
                      start: new DayPilot.Date(dt+response['data'][i].start),
                      end: new DayPilot.Date(dt+response['data'][i].end),
                      id: response['data'][i].id,
                      text: response['data'][i].name
                  });
                  dp.events.add(e);
                }
              }
            }
        }
      });

</script>
@endpush
