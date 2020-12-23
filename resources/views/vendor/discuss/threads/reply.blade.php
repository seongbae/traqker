<div id="reply-{{$reply->id}}" class="card mt-2"  >
    <div class="card-body">
        <div class="mb-4">
            <div class="float-left">
                <img src="{{getUserImage($reply->user, config('discuss.user_image_field'), config('discuss.user_image_path'), config('discuss.default_image'))}}" class="rounded-circle mr-2" width="40px" title="{{$reply->user->name}}">
                <a href="#" v-b-popover.hover.right="'{{$reply->user->name}}'" title="{{$reply->user->name}}">{{ $reply->user->name }}</a> posted {{ $reply->created_at->diffForHumans() }}
            </div>
            @auth
            @if (Auth::user()->can('manage-discussion') || Auth::id() == $reply->user_id)
            <div class="float-right">
                <div class="dropdown show">
                    <a href="#" type="text" class="text-muted" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </a>
                    <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                        <a class="dropdown-item" id="replyEditBtn" data-target="#editReply" data-toggle="modal" data-body="{{$reply->body}}" data-action="/replies/{{$reply->id}}">Edit</a>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); if (confirm('{{ __('Delete this reply?') }}')) $('#delete_reply_{{ $reply->id }}_form').submit();">Delete</a>
                    </div>
                </div>
                <form method="post" action="{{ route('reply.destroy', $reply) }}" id="delete_reply_{{ $reply->id }}_form" class="d-none">
                    @csrf
                    @method('delete')
                </form>
            </div>
            @endif
            @endauth
            <div style="clear: both;"></div>
        </div>

        <div style="white-space: pre-line;">{{$reply->body}}</div>
    </div>
</div>
