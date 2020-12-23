@extends('canvas::admin.layouts.app')

@section('content')

    @include('teams.menus')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-2">
                    <a class="btn btn-outline-primary  btn-sm w-100 mb-2" href="{{route('discuss.index', ['slug'=>$thread->channel->slug])}}"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back to discussion</a>
                    <hr>
                </div>
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="float-left">
                                    <img src="{{getUserImage($thread->user, config('discuss.user_image_field'), config('discuss.user_image_path'), config('discuss.default_image'))}}" class="rounded-circle mr-2" width="40px" title="{{$thread->user->name}}">
                                    <a href="#" title="{{$thread->user->name}}">{{ $thread->user->name }}</a> posted {{ $thread->created_at_human_readable }}</a>
                                </div>
                                @if (Auth::id() == $thread->user->id)
                                    <div class="float-right">
                                        <div class="dropdown show">
                                            <a href="#" type="text" class="text-muted" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </a>
                                            <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                                <a class="dropdown-item" id="threadEditBtn" href="#" data-target="#editThread" data-toggle="modal">Edit</a>
                                                <a class="dropdown-item" href="#">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div style="clear: both;"></div>
                            <div class="mt-4">
                                <p><strong><span>{{$thread->title}}</span></strong></p>
                                <span>{{$thread->body}}</span>
                            </div>
                            <form action="{{$thread->path()}}" method="post" id="deleteForm">
                                @csrf
                                @method('delete')
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
    </div>

<div class="modal fade" id="editThread" tabindex="-1" role="dialog" aria-labelledby="editThreadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Edit Thread</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{route('discuss.update', ['channel'=>$thread->channel, 'thread'=>$thread])}}">
                    @csrf
                    @method('patch')
                    @include('discuss::threads._partials.form')
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editReply" tabindex="-1" role="dialog" aria-labelledby="editReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Edit Reply</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    @csrf
                    @method('patch')
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <textarea name="body" id="body" rows="8"  required="required" class="form-control" placeholder="Message..."></textarea>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-12 text-right">
                            <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

    <script>
        $(document).ready(function(){
            $("#replyEditBtn").click(function (){
                var body = $(this).data('body');
                var action = $(this).data('action');

                $('#editReply form').attr('action', action);
                $('#editReply #body').val(body);
            })

            $('#editThread').on('shown.bs.modal', function() {
                $('#title').trigger('focus');
            });

            $('#editReply').on('shown.bs.modal', function(e) {
                $('#editReply #body').trigger('focus');
            });
        });
    </script>
@endpush
