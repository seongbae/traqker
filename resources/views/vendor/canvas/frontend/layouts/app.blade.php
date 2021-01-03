<!DOCTYPE html>
<html lang="en">
<head>
    <title>traqker - Online Project Management Tool</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ option('site_description')}}">
    <meta name="author" content="Seong Bae">
    <link rel="icon" type="image/png" href="{{ asset('/img/traqker-icon.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,500,700|Nunito:400,600,700" rel="stylesheet">

    <!-- FontAwesome JS-->
    <script defer src="/assets/fontawesome/js/all.min.js"></script>

    <link href="{{ asset('canvas/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>




    <header class="header">

        <div class="branding">

            <div class="container position-relative">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <a class="navbar-brand" href="/"><img src="/img/traqker-logo.png" alt="logo" style="width:140px;"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbar">
                        <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                            <li class="nav-item active">
                                <a class="nav-link " href="/login">Login</a>
                            </li>
                        </ul>

                    </div>
                </nav>
            </div><!--//container-->

        </div><!--//branding-->


    </header><!--//header-->


    @yield('content')


    <!-- <footer class="footer theme-bg-primary">

      <div class="footer-bottom text-center">
          /* This template is free as long as you keep the footer attribution link. If you'd like to use the template without the attribution link, you can buy the commercial license via our website: themes.3rdwavemedia.com Thank you for your support. :) */
            <small class="copyright">traqker is built by <a href="https://www.github.com/seongbae">Seong Bae</a>.  This bootstrap theme is based on Nova by <a href="http://themes.3rdwavemedia.com" target="_blank">Xiaoying Riley</a>.</small>
      </div>

    </footer> -->
    <section class="copyright">
    <div class="container">
    <div class="row my-3">
    <div class="col-md-6 col-sm-7">
        <strong><a href="https://github.com/seongbae/traqker" target="_blank">traqker</a></strong> - Open source project management tool
    </div>
    <div class="col-md-6 col-sm-5 text-right">
        v0.1
    </div>
    </div>
    </div>
    </section>


    <!-- Javascript -->
    <script type="text/javascript" src="/assets/plugins/jquery-3.4.1.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/popper.min.js"></script>
    <script type="text/javascript" src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>

    <!-- Page Specific JS -->
    <script type="text/javascript" src="/assets/plugins/jquery-flipster/dist/jquery.flipster.min.js"></script>
    <script type="text/javascript" src="/assets/js/flipster-custom.js"></script>


</body>
</html>

