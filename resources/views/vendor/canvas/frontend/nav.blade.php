<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
  <div class="container">
    <a class="navbar-brand js-scroll-trigger logo-title" href="/">{{ config('app.name')}}</a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
    Menu
    <i class="fas fa-bars"></i>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav text-uppercase ml-auto">
        <!-- <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#">Pricing</a>
        </li>
        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link js-scroll-trigger" href="#">Contact</a>
        </li> -->
        @guest
        <li class="nav-item">
          <a class="btn btn-primary btn-sm my-1" href="/login">Login</a>
        </li>
        @endguest
        @auth
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img src="{{Auth::user()->photo}}" class="rounded-circle mr-2" style="width:25px;">
            {{Auth::user()->name}}
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @can('access-admin-ui')
            <a class="dropdown-item nav-link" href="/dashboard/" target="_blank">
              <i aria-hidden="true" class="fas fa-fw fa-user-shield mr-2"></i> Dashboard
            </a>
            <div class="dropdown-divider"></div>
            @endcan
            <a class="dropdown-item nav-link" href="/account/">
              <i aria-hidden="true" class="fas fa-user-circle mr-2"></i> My Account
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt mr-2"></i> Log out
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              {{ csrf_field() }}
            </form>


          </div>
        </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
