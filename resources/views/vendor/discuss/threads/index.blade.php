@extends('canvas::admin.layouts.app')
@section('content')

    @include('teams.menus')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    @auth
                        <a class="btn btn-primary  btn-sm w-100 mb-2" href="#" data-target="#newThread" data-toggle="modal"><i class="fas fa-plus"></i> Start a Discussion</a>
                    @endauth
                </div>
                <div class="col-lg-6">
                    @if (count($threads)>0)
                        <div class="card card-default">
                            <div class="card-body">
                                @foreach ($threads as $thread)
                                    @if ($thread->user != null)
                                        <article>
                                            <div style="float:left;" class="mr-4">
                                                <img src="/storage/{{$thread->user->photo}}" class="rounded-circle" width="50px">
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
        });
    </script>
@endpush
