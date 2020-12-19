@extends('canvas::admin.layouts.app')
@section('content')
    @include('teams.menus')
    <div class="card">
        <div class="card-body">
		<div class="row">
			<div class="col-lg-2">
                <a class="btn btn-primary  btn-sm w-100 mb-2" href="/discuss/new?team={{$channel->slug }}" ><i class="fas fa-plus"></i> Start a Discussion</a>
                <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss/{{$channel->slug}}" >View All Threads</a>
                @auth
                <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss/{{$channel->slug}}?user=me" >My Threads</a>
                    @if (request()->is('discuss/*'))
                        <hr>
{{--                    <a class="btn btn-outline-primary btn-sm w-100 mb-2" href="/discuss?user=me" >Subscribe to Channel</a>--}}
                        <channel-subscribe :channel="{{$channel}}" :user="{{Auth::user()}}" :subscribed="'{{ $subscribed }}'"></channel-subscribe>
                        @endif
                @endauth


            </div>
			<div class="col-lg-10">
                @if (count($threads)>0)
				<div class="card card-default">
					<div class="card-body">
						@foreach ($threads as $thread)
						@if ($thread->user != null)
						<article>
							<div style="float:left;" class="mr-4">
								<img src="{{ config('discuss.user_image_field') == "" ? config('discuss.default_image') : config('discuss.user_image_path').$thread->user->{config('discuss.user_image_field')} }}" class="rounded-circle" width="50px">
							</div>
							<div style="float:left;">
								<a href="{{ $thread->path() }}" style="font-size:1.2em;color:#22292f;">
									{{ $thread->title }}
								</a>
                                <div style="color:grey;" class="mt-1">
                                    <a href="#" tabindex="0" role="button" data-placement="right" data-toggle="popover" data-container="body" type="button" data-html="true" id="login" data-id="{{$thread->user->id}}">{{ $thread->user->name}}</a>
                                    created {{ $thread->created_at->diffForHumans() }}

                                    @include('discuss::threads._partials.userpopover',['user'=>$thread->user])
                                </div>
							</div>
							<div class="text-muted" style="text-align:right;">
								@if (count($thread->replies) > 0)
								<i class="fas fa-comment" title="Replies"></i> {{count($thread->replies)}}

								@endif
                                <i class="ml-2 far fa-eye" title="View count"></i> {{$thread->view_count}}
							</div>
							<br style="clear: left;" />
						</article>
						<hr>
						@endif
						@endforeach

                        {{ $threads->links() }}
					</div>
				</div>
                @endif
			</div>
		</div>
        </div>
    </div>
@stop
