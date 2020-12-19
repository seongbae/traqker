<div id="popover-content-{{$user->id}}" class="d-none">
    <div class="row">
        <div class="col-sm-4">
            <img src="{{ config('discuss.user_image_field') == "" ? config('discuss.default_image') : $thread->user->{config('discuss.user_image_field')} }}" class="rounded-circle" width="88px">
        </div>
        <div class="col-sm-8">
            <div class="name">
                {{ $user->name }}
            </div>
            <div class=" mt-1">
                Joined {{$user->created_at}}
            </div>
            <div class="mt-1 small">
                <div class="float-right mr-3">
                    @if($user->linkedin_url)
                        <a href="{{$user->linkedin_url}}" target="_blank"><i class="fab fa-linkedin"></i></a>
                    @endif
                    @if($user->youtube_url)
                        <a href="{{$user->youtube_url}}" target="_blank"><i class="fab fa-youtube-square"></i></a>
                    @endif
                    @if($user->twitter_url)
                        <a href="{{$user->twitter_url}}" target="_blank"><i class="fab fa-twitter-square"></i></a>
                    @endif
                    @if($user->facebook_url)
                        <a href="{{$user->facebook_url}}" target="_blank"><i class="fab fa-facebook-square"></i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
