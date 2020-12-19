<form method="POST" action="/discuss">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="title">Title:</label>
        <input type="text" class="form-control" id="title" name="title" value="{{old('title')}}" required>
    </div>

    <div class="form-group">
        <label for="body">Body:</label>
        <textarea name="body" id="body" class="form-control" rows="8" value="{{old('body')}}" required></textarea>
    </div>

    <input type="hidden" name="channel_id" value="{{ (\Seongbae\Discuss\Models\Channel::where('slug', app('request')->input('team')))->value('id') }}">
    <button type="submit" class="btn btn-primary">Publish</button>
    <a href="{{ URL::previous() }}" class="btn btn-outline-secondary">Cancel</a>
</form>
