@extends('canvas::admin.layouts.app')
@section('content')

    @include('teams.menus')

    <div class="card">
        <div class="container">
            <div class="card-body">
                <div class="row text-right">
                    <div class="col-lg-10">
                    <a class="btn btn-link btn-sm mb-2 {{Auth::user()->subscribedTo($channel) ? "" : "d-none" }}" href="#" id="unsubscribe"><i class="fas fa-bell" title="Subscribed"></i></a>
                    <a class="btn btn-link btn-sm mb-2 {{Auth::user()->subscribedTo($channel) ? "d-none" : ""}}" href="#" id="subscribe"><i class="far fa-bell" title="Unsubscribed"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        @auth
                            <a class="btn btn-primary  btn-sm mb-2" href="#" data-target="#newThread" data-toggle="modal"><i class="fas fa-plus"></i> Start a Discussion</a>
                                {{--                            <form method="post" action="{{ route('subscription.destroy', ['user'=>Auth::user()]) }}" id="delete_subscription_form" class="d-none">--}}
                                {{--                                @csrf--}}
                                {{--                                @method('delete')--}}
                                {{--                                <input type="hidden" name="type" value="channel">--}}
                                {{--                                <input type="hidden" name="id" value="{{$channel->id}}">--}}
                                {{--                            </form>--}}

                                {{--                            <form method="post" action="{{ route('subscription.store', ['user'=>Auth::user()]) }}" id="create_subscription_form" class="d-none">--}}
                                {{--                                @csrf--}}
                                {{--                                <input type="hidden" name="type" value="channel">--}}
                                {{--                                <input type="hidden" name="id" value="{{$channel->id}}">--}}
                                {{--                            </form>--}}

                        @endauth
                    </div>
                    <div class="col-lg-8">
                        @if (count($threads)>0)
                            <div class="card card-default">
                                <div class="card-body">
                                    @foreach ($threads as $thread)
                                        @if ($thread->user != null)
                                            <article>
                                                <div style="float:left;" class="mr-4">
                                                    <img src="/storage/{{$thread->user->photo}}" class="rounded-circle" width="40px">
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
                        {{ $thread = null }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @auth
        <div class="modal fade" id="newThread" tabindex="-1" role="dialog" aria-labelledby="newThreadModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel">New Thread</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{route('discuss.store')}}">
                            @csrf
                            @include('discuss::threads._partials.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endauth
@stop

@push('scripts')

    <script>
        $(document).ready(function(){
            $('#newThread').on('shown.bs.modal', function() {
                $('#title').val("");
                $('#title').trigger('focus');
            });

            $("#subscribe").click(function (){
                axios.post('{{route('subscription.store', ['user'=>Auth::user()])}}', {
                    type: 'channel',
                    id: '{{$channel->id}}'
                }).then(res => {
                    $("#subscribe").addClass("d-none");
                    $("#unsubscribe").removeClass("d-none");
                }).catch(e => {
                });
            })

            $("#unsubscribe").click(function (){
                axios.delete('{{route('subscription.destroy', ['user'=>Auth::user()])}}', {
                    data: {
                        type: 'channel',
                        id: '{{$channel->id}}'
                    }
                }).then(res => {
                    $("#subscribe").removeClass("d-none");
                    $("#unsubscribe").addClass("d-none");
                }).catch(e => {

                });
            })

        });
    </script>
@endpush
