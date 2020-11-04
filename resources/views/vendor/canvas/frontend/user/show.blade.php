@extends('canvas::frontend.layouts.app')

@section('content')
<section>
    <div class="container">
        <!-- Application Dashboard -->
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
                      aria-selected="true">Profile</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="edit-profile-tab" data-toggle="tab" href="#edit-profile" role="tab" aria-controls="edit-profile"
                      aria-selected="false">Edit</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="password-tab" data-toggle="tab" href="#password" role="tab" aria-controls="password"
                      aria-selected="true">Update Password</a>
                  </li>
                </ul>
                <div class="tab-content m-4" id="myTabContent">
                  <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                      @include('canvas::frontend.user.partials.profile')
                  </div>
                  <div class="tab-pane fade" id="edit-profile" role="tabpanel" aria-labelledby="edit-profile-tab">
                      @include('canvas::frontend.user.partials.profile_edit')
                  </div>
                  <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                      @include('canvas::frontend.user.partials.password')
                  </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection

