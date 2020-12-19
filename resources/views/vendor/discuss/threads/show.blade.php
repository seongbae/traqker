@extends('canvas::admin.layouts.app')

@section('content')
    <section>
        <div class="container">
            <thread :thread='{{$thread}}' :channels="{{$channels}}" :user="{{$user}}" :subscribed="{{ $subscribed  }}" :imagefield="'{{ config('discuss.user_image_field') }}'" :imagepath="'{{ config('discuss.user_image_path') }}'" :defaultimage="'{{ config('discuss.default_image') }}'">

            </thread>

            @if (count($thread->replies)>0)
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    @foreach ($thread->replies as $reply)
                        @include ('discuss::threads.reply')
                    @endforeach
                </div>
            </div>
            @endif

            @if (auth()->check())
                <div class="row mt-4">
                    <div class="col-md-8 offset-md-2">
                        <form method="POST" action="{{ $thread->path() . '/replies' }}">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <textarea name="body" id="body" class="form-control"
                                          placeholder="Have something to say?" rows="5" required></textarea>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="1" id="defaultCheck1" checked name="subscribe">
                                <label class="form-check-label" for="defaultCheck1">
                                    Get notified on new replies
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Post</button>

                        </form>
                    </div>
                </div>
            @else
                <p class="text-center">Please <a href="{{ route('login') }}">sign in</a> to participate in this
                    discussion.</p>
            @endif
        </div>
    </section>
@endsection
