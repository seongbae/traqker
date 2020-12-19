<reply :attributes="{{$reply}}" inline-template>
	<div id="reply-{{$reply->id}}" class="card mt-2" v-if="showReply" >
	    <div class="card-body">
            <div class="mb-4">
                <div class="float-left">
                    <img src="{{ config('discuss.user_image_field') == "" ? config('discuss.default_image') : config('discuss.user_image_path').$reply->user->{config('discuss.user_image_field')} }}" class="rounded-circle mr-2" width="40px">
                    <a href="#" v-b-popover.hover.right="'{{Auth::user()->name}}'" title="{{Auth::user()->name}}">{{ $reply->user->name }}</a> posted {{ $reply->created_at->diffForHumans() }}
                </div>
                @if (Auth::user()->can('manage-discussion') || Auth::id() == $reply->user_id)
                <div class="float-right">
                    <div class="dropdown show">
                        <a href="#" type="text" class="text-muted" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>
                        <div class="dropdown-menu  dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" id="threadEditBtn" @click="editing = true">Edit</a>
                            <a class="dropdown-item" href="#" @click="deleteItem">Delete</a>
                        </div>
                    </div>
                </div>
                @endif
                <div style="clear: both;"></div>
            </div>
	    	<div v-if="editing">
	    		<div class="form-group">
		    		<textarea name="body" id="body" class="form-control" rows="3" v-model="body"></textarea>
		    	</div>
		    	<button class="btn btn-primary btn-xs" @click="update">Update</button>
		    	<button class="btn btn-xs" @click="editing = false">Cancel</button>
	    	</div>

	    	<div v-else v-text="body" style="white-space: pre-line;"></div>
	    </div>
	</div>
</reply>
