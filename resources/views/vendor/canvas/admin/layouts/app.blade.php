<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name')}}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" type="image/png" href="{{ asset('/img/traqker-icon.png') }}">

  <link href="{{ asset('canvas/css/all.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('canvas/css/datatables.min.css') }}" rel="stylesheet" >
  <link href="{{ asset('canvas/css/canvas.css') }}" rel="stylesheet" >
  <link href="{{ asset('css/vendor.css') }}" rel="stylesheet" >
    <link href="{{ asset('canvas/css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <!-- Load styles from child views -->
  @stack('styles')

    <script>
        window.Laravel = {!! json_encode([
            'user' => Auth::user(),
            'csrfToken' => csrf_token(),
            'vapidPublicKey' => config('webpush.vapid.public_key'),
            'pusher' => [
                'key' => config('broadcasting.connections.pusher.key'),
                'cluster' => config('broadcasting.connections.pusher.options.cluster'),
            ],
        ]) !!};
    </script>

    <script src="{{ asset('canvas/js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('canvas/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('canvas/js/datatables.min.js') }}"></script>
    <script src="{{ asset('canvas/js/buttons.server-side.js') }}"></script>
    <script src="{{ asset('canvas/js/tagsinput.js') }}"></script>

</head>
<body class="hold-transition sidebar-mini layout-fixed  @if (Cookie::get('toggleState') === 'closed') {{ 'sidebar-collapse' }} @endif skin-blue">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <form class="form-inline ml-3" action="{{ route('search') }}" method="GET">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" name="query" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
    <ul class="navbar-nav ml-auto align-items-center h-100">
        <li class="nav-item dropdown">
            <div class="btn-group">
                <a class="btn btn-default btn-sm" href="/tasks/create"><i class="fas fa-plus"></i></a>
                <button type="button" class="btn btn-sm btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="/tasks/create">Add Task</a>
                    <a class="dropdown-item" href="/projects/create">Add Project</a>
                    <a class="dropdown-item" href="/teams/create">Add Team</a>
                </div>
            </div>
        </li>
      <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
              @if (count($notifications)>0)
                  <i class="fas fa-bell" id="notif-bell-dark"></i>
              @else
                  <i class="far fa-bell" id="notif-bell-light"></i>
              @endif
              <span class="badge badge-danger navbar-badge" id="notif-count-badge" style="display:none;">{{ count($notifications) }}</span>

          </a>
          <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
              <span class="dropdown-item dropdown-header" id="notif-count-dropdown">{{ count($notifications) }} Notifications</span>
              @forelse($notifications as $notification)
                  <div class="notification">
                      <div class="dropdown-divider"></div>

                      <a href="{{$notification->data['link']}}" class="dropdown-item mark-as-read overflow-auto" data-id="{{ $notification->id }}">
                          @if (array_key_exists('image', $notification->data))
                              <img src="{{$notification->data['image']}}" class="img-circle profile-small mr-2">
                          @endif
                          {!! Helper::limitText($notification->data['notif_msg'], 200) !!}
                          <span class="float-right text-muted text-xs">{{ $notification->created_at->diffForHumans(null,true) }}</span>
                      </a>

                  </div>
              @empty

              @endforelse

              <div class="dropdown-divider"></div>
              <div class="dropdown-footer d-flex justify-content-between">
                  <a href="/notifications" class="text-left">See All</a>
                  @if (count($notifications)>0)
                      <a href="#" id="mark-all">
                          Mark all as read
                      </a>
                  @endif
              </div>
          </div>
      </li>
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <img src="{{Auth::user()->photo}}" alt="user" class="rounded-circle" width="30">
            <span class="ml-2 font-medium name">{{Auth::user()->name}}</span>
            <span class="fas fa-angle-down ml-2 carrat"></span>
        </a>

        <div class="dropdown-menu dropdown-menu-right">
          <a href="/my-account" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> My Account
          </a>
            <a class="dropdown-item" href="/notifications/">
                <i class="fas fa-bell mr-2"></i> Notifications
            </a>

        @if (count(Auth::user()->teams)>0)
          <div class="dropdown-divider"></div>
          <span class="dropdown-item text-muted text-sm my-1">My Teams</span>
          @foreach(Auth::user()->teams as $team)
          <a href="{{ route('teams.show', $team) }}" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> {{$team->name}}
          </a>
          @endforeach
          @endif
          <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt mr-2"></i> Log out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/dashboard" class="brand-link site-logo ml-2">
      <img class="logo-icon-admin mr-1 ml-2" src="/img/traqker-icon.png" alt="{{config('app.name')}}" title="{{config('app.name')}}">

     <span class="brand-text font-weight-light">{{ config('app.name')}}</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{Auth::user()->photo}}" class="img-circle elevation-2" alt="{{Auth::user()->name}}">
        </div>
        <div class="info">
          <a href="/my-account" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      @include('canvas::admin.nav')
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="app">
    <!-- Content Header (Page header) -->
    <div class="content-header p-3">
        <!-- <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark"></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard v1</li>
            </ol>
          </div>
        </div> -->
        @if(flash()->message)
            <div class="alert {{ flash()->class }}">
                {{ flash()->message }}
            </div>
        @endif
          <div class="row justify-content-center">
              <div class="col-md-12">
                @if (count($errors) > 0)
                  @foreach ($errors->all() as $error)
                  <div class="alert alert-danger alert-dismissible fade show" id="formMessage" role="alert">
                      {{ $error }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  @endforeach
                @endif
              @yield('content')
              </div>
          </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
      <strong><a href="https://github.com/seongbae/traqker" target="_blank">traqker</a></strong> - Open source project management tool</a>
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> {{option('version')}}
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="{{ asset('js/app.js') }}"></script>

<script>

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js').then(function(reg) {
            // console.log('Service Worker Registered!', reg);

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
    } else {
        console.log('serviceWorker not in navigator')
    }

    function sendMarkRequest(id = null) {
        _token = "{{ csrf_token() }}";

        return $.ajax("{{ route('markNotification') }}", {
            method: 'POST',
            data: {
                _token,
                id
            }
        });
    }

    // function setCookie(cname, cvalue, exdays) {
    //     var d = new Date();
    //     d.setTime(d.getTime() + (exdays*24*60*60*1000));
    //     var expires = "expires="+ d.toUTCString();
    //     document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    // }
    //
    // function getCookie(cname) {
    //     var name = cname + "=";
    //     var decodedCookie = decodeURIComponent(document.cookie);
    //     var ca = decodedCookie.split(';');
    //     for(var i = 0; i <ca.length; i++) {
    //         var c = ca[i];
    //         while (c.charAt(0) == ' ') {
    //             c = c.substring(1);
    //         }
    //         if (c.indexOf(name) == 0) {
    //             return c.substring(name.length, c.length);
    //         }
    //     }
    //     return "";
    // }

    $.AdminLTESidebarTweak = {};

    $.AdminLTESidebarTweak.options = {
        EnableRemember: true,
        NoTransitionAfterReload: false
        //Removes the transition after page reload.
    };

    $(function() {

        function setCookie(value) {
            let name = 'toggleState';
            let days = 365;
            let d = new Date;
            d.setTime(d.getTime() + 24 * 60 * 60 * 1000 * days);
            document.cookie = name + "=" + value + ";path=/;expires=" + d.toGMTString();
        }

        $("body").on("collapsed.lte.pushmenu", function () {
            if ($.AdminLTESidebarTweak.options.EnableRemember) {
                setCookie('closed');
            }
        }).on("shown.lte.pushmenu", function () {
            if ($.AdminLTESidebarTweak.options.EnableRemember) {
                setCookie('opened');
            }
        });

        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));
            request.done(() => {
                $(this).parents('div.notification').remove();
            });
        });
        $('#mark-all').click(function() {
            let request = sendMarkRequest();
            request.done(() => {
                $('div.notification').remove();
                $('#notif-count-badge').hide();
                $('#notif-count-badge').text("0");
                $('#notif-bell-dark').removeClass("fas").addClass("far");
                $('#notif-count-dropdown').text('0 Notification');
            })
        });

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


    });
</script>


@stack('scripts')
</body>
</html>
