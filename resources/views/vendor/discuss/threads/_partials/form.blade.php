<div class="row mb-2">
    <div class="col-sm-12">
        <input type="text" name="title" placeholder="Title" class="form-control" id="title" value="{{ $thread ? $thread->title : "" }}" focus/>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-12">
        <textarea name="body" id="body" rows="8"  required="required" class="form-control" placeholder="Message...">{{ $thread ? $thread->body : "" }}</textarea>
    </div>
</div>
<div class="row mb-2">
    <div class="col-sm-12 text-right">
        <input type="hidden" name="channel_id" value="{{$team->channel_id}}">
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-sm btn-primary">Submit</button>
    </div>
</div>
