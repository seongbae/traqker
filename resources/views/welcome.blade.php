@extends('canvas::frontend.layouts.app')
@section('content')
<section class="hero-section" style="height:500px;">
      <div class="container h-100">
        <div class="row h-100">
          <div class="col-md-6 pt-3 pt-md-4 my-auto">
            <h2 class="site-headline font-weight-bold">Get things done with traqker</h2>
            <div class="site-tagline mb-3">traqker is an online project management tool that gives you full visibility and control over your tasks. With the help of traqker, managing projects becomes easier.</div>
            <div class="cta-btns">
              <div class="mb-4"><a class="btn btn-primary font-weight-bold theme-btn" href="/register">Create a Free Account</a></div>

            </div>
          </div>
          <div class="col-md-6 pt-3 pt-md-4 my-auto">
            <img src="/img/traqker-screen.png" class="img-fluid">
          </div>
        </div><!--//row-->
      </div>
    </section><!--//hero-section-->
    <hr>
    <section class="logos-section theme-bg-primary py-5 text-center" style="display:none;">
      <div class="container">
        <h3 class="mb-5">Trusted by hundreds of software businesses</h3>
        <div class="logos-row row mx-auto">
              <div class="item col-6 col-md-4 col-lg-2 mb-3 mb-lg-0">
                <div class="item-inner">
                    <img src="assets/images/logos/logo-1.svg" alt="logo">
                </div><!--//item-inner-->
              </div><!--//item-->
              <div class="item col-6 col-md-4 col-lg-2 mb-3 mb-lg-0">
                <div class="item-inner">
                    <img src="assets/images/logos/logo-2.svg" alt="logo">
                </div><!--//item-inner-->
              </div><!--//item-->
              <div class="item col-6 col-md-4 col-lg-2 mb-3 mb-lg-0">
                <div class="item-inner">
                    <img src="assets/images/logos/logo-3.svg" alt="logo">
                </div><!--//item-inner-->
              </div><!--//item-->
              <div class="item col-6 col-md-4 col-lg-2 mb-3 mb-lg-0">
                <div class="item-inner">
                    <img src="assets/images/logos/logo-4.svg" alt="logo">
                </div><!--//item-inner-->
              </div><!--//item-->
              <div class="item col-6 col-md-4 col-lg-2 mb-3 mb-lg-0">
                <div class="item-inner">
                    <img src="assets/images/logos/logo-5.svg" alt="logo">
                </div><!--//item-inner-->
              </div><!--//item-->
              <div class="item col-6 col-md-4 col-lg-2 mb-3 mb-lg-0">
                <div class="item-inner">
                    <img src="assets/images/logos/logo-6.svg" alt="logo">
                </div><!--//item-inner-->
              </div><!--//item-->

          </div>

      </div><!--//container-->
    </section><!--//logo-section-->

    <section class="benefits-section py-5" >

      <div class="container py-lg-5">
        <h3 class="mb-5 text-center font-weight-bold">Project & task tracking is made easy with traqker</h3>
        <div class="row">
            <div class="item col-12 col-lg-4">
            <div class="item-inner text-center p-3 p-lg-5">
              <img class="mb-3" src="assets/images/icon-target.svg" alt="" />
              <h5>Simple to Use</h5>
              <div>traqker is simple to use and, yet, is extensible if needed.</div>
            </div><!--//item-inner-->
          </div><!--//item-->
          <div class="item col-12 col-lg-4">
            <div class="item-inner text-center p-3 p-lg-5">
              <img class="mb-3" src="assets/images/icon-rocket.svg" alt="" />
              <h5>Real-time Communication</h5>
              <div>Receive instant notifications on updates, new tasks, and new comments.</div>
            </div><!--//item-inner-->
          </div><!--//item-->
          <div class="item col-12 col-lg-4">
            <div class="item-inner text-center p-3 p-lg-5">
              <img class="mb-3" src="assets/images/icon-cogs.svg" alt="" />
              <h5>Collaborate Faster</h5>
              <div>Our team features make it easy to collaborate among team members across different timezones.</div>
            </div><!--//item-inner-->
          </div><!--//item-->
        </div><!--//row-->
        <!-- <div class="pt-3 text-center">
          <a class="btn btn-primary theme-btn theme-btn-ghost font-weight-bold" href="https://themes.3rdwavemedia.com/bootstrap-templates/free/nova-bootstrap-landing-page-template-for-mobile-apps/">Learn More</a>
        </div> -->
      </div>

    </section><!--//benefits-section-->

    <section class="features-section py-5" style="display:none;">
      <div class="container py-lg-5">
        <h3 class="mb-3 text-center font-weight-bold section-heading">Feature Highlights</h3>
        <div class="row pt-5 mb-5">

          <div class="col-12 col-md-6 col-xl-5 offset-xl-1 d-none d-md-block">
            <img class="product-figure product-figure-1 img-fluid" src="assets/images/product-figure-1.png" alt="" />
          </div>

            <div class="col-12 col-md-6 col-xl-5 pr-xl-3 pt-md-3">
            <div class="card rounded border-0 shadow-lg  mb-5">
              <div class="card-body p-4">
              <h5 class="card-title"><i class="far fa-chart-bar mr-2 mr-lg-3 text-primary fa-lg fa-fw"></i>Feature Lorem Ipsum</h5>
              <p class="card-text">List one of your product's main features here. The screenshots used in this template are taken from <a href="https://themes.3rdwavemedia.com/bootstrap-templates/product/appify-bootstrap-4-admin-template-for-app-developers/" target="_blank">Bootstrap 4 admin template Appify</a>. </p>
              <a href="#" >Learn more <span class="more-arrow">&rarr;</span></a>
            </div>
            </div><!--//card-->

            <div class="card rounded border-0 shadow-lg mb-5">
              <div class="card-body p-4">
              <h5 class="card-title"><i class="fas fa-laptop-code mr-2 mr-lg-3 text-primary fa-lg fa-fw"></i>Feature Consectetuer</h5>
              <p class="card-text">List one of your product's main features here. The screenshots used in this template are taken from <a href="https://themes.3rdwavemedia.com/bootstrap-templates/product/appify-bootstrap-4-admin-template-for-app-developers/" target="_blank">Bootstrap 4 admin template Appify</a>.</p>
              <a href="#" >Learn more <span class="more-arrow">&rarr;</span></a>
            </div>
            </div><!--//card-->
            <div class="card rounded border-0 shadow-lg">
              <div class="card-body p-4">
              <h5 class="card-title"><i class="far fa-calendar-alt mr-2 mr-lg-3 text-primary fa-lg fa-fw"></i>Feature Lorem Ipsum</h5>
              <p class="card-text">List one of your product's main features here. The screenshots used in this template are taken from <a href="https://themes.3rdwavemedia.com/bootstrap-templates/product/appify-bootstrap-4-admin-template-for-app-developers/" target="_blank">Bootstrap 4 admin template Appify</a>.</p>
              <a href="#" >Learn more <span class="more-arrow">&rarr;</span></a>
            </div>
            </div><!--//card-->
          </div>


        </div>

        <div class="row">
          <div class="col-12 col-md-6 col-xl-5 order-md-2 pr-xl-3 d-none d-md-block">
            <img class="product-figure product-figure-2 img-fluid" src="assets/images/product-figure-2.png" alt="" />
          </div>
          <div class="col-12 col-md-6 col-xl-5 order-md-1 offset-xl-1 pt-xl-5">
            <div class="card rounded border-0 shadow-lg  mb-5">
              <div class="card-body p-4">
              <h5 class="card-title"><i class="fas fa-microphone-alt mr-2 mr-lg-3 text-primary fa-lg fa-fw"></i>Feature Commodo</h5>
              <p class="card-text">List one of your product's main features here. The screenshots used in this template are taken from <a href="https://themes.3rdwavemedia.com/bootstrap-templates/product/appify-bootstrap-4-admin-template-for-app-developers/" target="_blank">Bootstrap 4 admin template Appify</a>. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem. </p>
              <a href="#" >Learn more <span class="more-arrow">&rarr;</span></a>
            </div>
            </div><!--//card-->

            <div class="card rounded border-0 shadow-lg">
              <div class="card-body p-4">
              <h5 class="card-title"><i class="far fa-comments mr-2 mr-lg-3 text-primary fa-lg fa-fw"></i>Feature  Ligula Eget</h5>
              <p class="card-text">List one of your product's main features here. Lorem ipsum dolor sit amet. The screenshots used in this template are taken from <a href="https://themes.3rdwavemedia.com/bootstrap-templates/product/appify-bootstrap-4-admin-template-for-app-developers/" target="_blank">Bootstrap 4 admin template Appify</a>.</p>
              <a href="#" >Learn more <span class="more-arrow">&rarr;</span></a>
            </div>
            </div><!--//card-->
          </div>

        </div>

        <div class="pt-5 text-center">
          <a class="btn btn-primary theme-btn theme-btn-ghost font-weight-bold" href="#">View all features</a>
        </div>
      </div><!--//container-->

    </section><!--//features-section-->
<hr>
    <section class="cta-section py-5 theme-bg-secondary text-center">
      <div class="container">
        <h3 class="font-weight-bold mb-3">Ready to start using traqker?</h3>
        <a class="btn btn-primary mt-4" href="/register">Create a Free Account</a>
      </div>
    </section><!--//cta-section-->
@endsection
