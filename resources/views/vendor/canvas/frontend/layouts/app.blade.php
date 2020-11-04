<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{ option('site_name')}} - Online Project Management Tool</title>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ option('site_description')}}">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Maven+Pro:400,500,700|Nunito:400,600,700" rel="stylesheet">

    <!-- FontAwesome JS-->
  <script defer src="/assets/fontawesome/js/all.min.js"></script>

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="/assets/plugins/jquery-flipster/dist/jquery.flipster.min.css">


    <!-- Theme CSS -->
    <link id="theme-style" rel="stylesheet" href="/assets/css/theme.css">

</head>

<body>


    <header class="header">

        <div class="branding">

            <div class="container position-relative">

        <nav class="navbar navbar-expand-lg" >
                    <h1 class="site-logo"><a class="navbar-brand" href="/"><img class="logo-icon" src="/img/traqker-icon.svg" alt="logo"> <span class="logo-text">traqker</span></a></h1>
        </nav>

        <!-- // Free Version ONLY -->
        <ul class="social-list list-inline mb-0 position-absolute">
                <li class="list-inline-item"><a class="btn btn-outline-primary" href="/login">Login</a></li>
              </ul><!--//social-list-->

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
    <div class="row">
    <div class="col-md-6 col-sm-7">
        <strong><a href="https://github.com/seongbae/traqker" target="_blank">traqker</a></strong> - Open source project management tool</a>
    </div>
    <div class="col-md-6 col-sm-5">
    <ul>
    <li><a href="#" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
    <li class="ml-3"><a href="#" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
    <li class="ml-3"><a href="#" title="google plus"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
    <li class="ml-3"><a href="#" title="linked in"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
    <li class="ml-3"><a href="#" title="tumblr"><i class="fa fa-tumblr" aria-hidden="true"></i></a></li>
    <li class="ml-3"><a href="#" title="youtube"><i class="fa fa-youtube"></i></a></li>
    </ul>
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

